import * as Y from 'yjs'
import { WebsocketProvider } from 'y-websocket'
import { useEffect, useRef, useState, useCallback } from 'react'

const PRESENCE_COLORS = [
    '#24695c', '#3eb59f', '#ba895d', '#1b4c43',
    '#2c7873', '#6a994e', '#d22d3d', '#e2a015',
]
function presenceColor(userId) {
    return PRESENCE_COLORS[Math.abs(Number(userId) || 0) % PRESENCE_COLORS.length]
}

export function useCollab(projectId, wsUrl, initRows, initContingency = 15, userInfo = {}) {
    const docRef        = useRef(null)
    const providerRef   = useRef(null)
    const yrowsRef      = useRef(null)
    const ymetaRef      = useRef(null)
    const saveTimers    = useRef({})
    const hasSyncedRef  = useRef(false)   // true after the first successful sync

    const [rows, setRows]                      = useState(() => initRows || [])
    const [connected, setConnected]            = useState(false)
    const [synced, setSynced]                  = useState(false)
    const [contingency, setContingencyState]   = useState(initContingency ?? 15)
    const [onlineUsers, setOnlineUsers]        = useState([])
    const [lastRemoteEditor, setLastRemoteEditor] = useState(null)

    // Build/cache doc once
    if (!docRef.current) {
        docRef.current   = new Y.Doc()
        yrowsRef.current = docRef.current.getMap('rows')
        ymetaRef.current = docRef.current.getMap('meta')
    }

    const ydoc  = docRef.current
    const yrows = yrowsRef.current
    const ymeta = ymetaRef.current

    // Compute display row array from yrows
    const snapshot = useCallback(() => {
        const out = []
        yrows.forEach((yrow, uid) => {
            out.push({ uid, ...Object.fromEntries(yrow) })
        })
        return out
    }, [yrows])

    useEffect(() => {
        const provider = new WebsocketProvider(wsUrl, `estimate-${projectId}`, ydoc)
        providerRef.current = provider

        // Set local presence (name + discipline + colour)
        if (userInfo.userId) {
            provider.awareness.setLocalStateField('user', {
                userId:     userInfo.userId,
                name:       userInfo.name || 'Unknown',
                discipline: userInfo.discipline || '',
                color:      presenceColor(userInfo.userId),
            })
        }

        // Build presence list whenever any client joins/leaves/updates
        const syncPresence = () => {
            const users = []
            provider.awareness.getStates().forEach((state, clientId) => {
                if (state.user) {
                    users.push({
                        ...state.user,
                        clientId,
                        isLocal: clientId === provider.awareness.clientID,
                    })
                }
            })
            // Put the current user first, then sort the rest by name
            users.sort((a, b) => {
                if (a.isLocal) return -1
                if (b.isLocal) return 1
                return (a.name || '').localeCompare(b.name || '')
            })
            setOnlineUsers(users)
        }
        provider.awareness.on('change', syncPresence)

        provider.on('status', ({ status }) => {
            setConnected(status === 'connected')
        })

        provider.on('sync', (isSynced) => {
            if (!isSynced) return
            setSynced(true)

            // Track whether anything actually changed in Yjs so we can skip setRows
            // when the sync is a no-op (e.g., normal WebSocket reconnect with unchanged data).
            // Calling setRows on every reconnect gives AG Grid a new rowData reference,
            // which triggers an autoHeight recalculation that briefly desynchronises the
            // pinned-right column container — the visible "broken column" flash.
            let rowsChanged = false

            ydoc.transact(() => {
                const dbUids = new Set((initRows || []).map(r => r.uid).filter(Boolean))

                // Remove Yjs rows that no longer exist in DB
                yrows.forEach((_, uid) => {
                    if (!dbUids.has(uid)) {
                        yrows.delete(uid)
                        rowsChanged = true
                    }
                })

                // Upsert DB rows — skip fields that are already at the correct value
                ;(initRows || []).forEach(row => {
                    if (!row.uid) return
                    const existing = yrows.get(row.uid)
                    if (existing) {
                        Object.entries(row).forEach(([k, v]) => {
                            if (k === 'uid') return
                            const next = v ?? ''
                            if (existing.get(k) !== next) {
                                existing.set(k, next)
                                rowsChanged = true
                            }
                        })
                    } else {
                        const yrow = new Y.Map()
                        Object.entries(row).forEach(([k, v]) => {
                            if (k !== 'uid') yrow.set(k, v ?? '')
                        })
                        yrows.set(row.uid, yrow)
                        rowsChanged = true
                    }
                })
            }, 'init')

            const dbContingency = initContingency ?? 15
            ydoc.transact(() => {
                if (ymeta.get('contingency') !== dbContingency) {
                    ymeta.set('contingency', dbContingency)
                }
            }, 'init')

            // First sync: always initialise React state (server may have sent state
            // before our 'init' transaction ran, so React state could be stale).
            // Subsequent syncs (reconnects): only re-render if Yjs data actually changed.
            if (!hasSyncedRef.current || rowsChanged) {
                setRows(snapshot())
            }
            hasSyncedRef.current = true
            // Functional form avoids a re-render when value is already correct
            setContingencyState(prev => (prev !== dbContingency ? dbContingency : prev))
        })

        // React to row changes (local and remote).
        // Skip 'init'-origin transactions — the sync handler calls setRows itself
        // after the transaction, so triggering it here too causes a double render.
        yrows.observeDeep((events, transaction) => {
            if (transaction.origin === 'init') return
            setRows(snapshot())
        })

        // React to meta changes from OTHER clients only
        const onMetaChange = (events, transaction) => {
            if (transaction.local) return
            const val = ymeta.get('contingency')
            if (val != null) setContingencyState(val)
            const lastEdit = ymeta.get('lastEdit')
            if (lastEdit?.by) setLastRemoteEditor({ by: lastEdit.by, ts: lastEdit.ts })
        }
        ymeta.observeDeep(onMetaChange)

        return () => {
            provider.awareness.off('change', syncPresence)
            provider.destroy()
            ymeta.unobserveDeep(onMetaChange)
            Object.values(saveTimers.current).forEach(t => {
                if (typeof t === 'number') clearTimeout(t)
            })
        }
    // eslint-disable-next-line react-hooks/exhaustive-deps
    }, [projectId, wsUrl])

    // Mutate a row field (triggers CRDT sync + autosave)
    const setField = useCallback((uid, field, value) => {
        const yrow = yrows.get(uid)
        if (!yrow) return
        ydoc.transact(() => {
            yrow.set(field, value)
            ymeta.set('lastEdit', { by: userInfo.name || 'Unknown', ts: Date.now() })
        }, 'local')
    }, [ydoc, yrows, ymeta, userInfo.name])

    // Mutate multiple fields in a single transaction (one broadcast)
    const setFields = useCallback((uid, fields) => {
        const yrow = yrows.get(uid)
        if (!yrow) return
        ydoc.transact(() => {
            Object.entries(fields).forEach(([k, v]) => yrow.set(k, v))
            ymeta.set('lastEdit', { by: userInfo.name || 'Unknown', ts: Date.now() })
        }, 'local')
    }, [ydoc, yrows, ymeta, userInfo.name])

    // Set contingency — applies locally immediately, then syncs via Yjs to all clients
    const setContingency = useCallback((val) => {
        setContingencyState(val)           // optimistic: no lag on the editing client
        ydoc.transact(() => {
            ymeta.set('contingency', val)  // broadcasts to all other clients
        }, 'local')
    }, [ydoc, ymeta])

    // Add a brand-new row
    const addRow = useCallback((uid, rowData) => {
        const yrow = new Y.Map()
        Object.entries(rowData).forEach(([k, v]) => {
            if (k !== 'uid') yrow.set(k, v)
        })
        ydoc.transact(() => { yrows.set(uid, yrow) }, 'local')
    }, [ydoc, yrows])

    // Remove a row
    const removeRow = useCallback((uid) => {
        ydoc.transact(() => { yrows.delete(uid) }, 'local')
    }, [ydoc, yrows])

    // Get a single row as plain object
    const getRow = useCallback((uid) => {
        const yrow = yrows.get(uid)
        if (!yrow) return null
        return { uid, ...Object.fromEntries(yrow) }
    }, [yrows])

    // Schedule debounced save for a uid
    const scheduleSave = useCallback((uid, saveFn) => {
        clearTimeout(saveTimers.current[uid])
        saveTimers.current[uid] = setTimeout(() => {
            const row = getRow(uid)
            if (row) saveFn(row)
            delete saveTimers.current[uid]
        }, 800)
    }, [getRow])

    return {
        rows, connected, synced,
        setField, setFields, addRow, removeRow, getRow, scheduleSave, yrows,
        contingency, setContingency,
        onlineUsers, lastRemoteEditor,
    }
}
