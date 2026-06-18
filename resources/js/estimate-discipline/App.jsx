import React, { useState, useCallback, useRef, useEffect } from 'react'
import { useCollab } from './collab'
import EstimateGrid from './Grid'
import AddRowModal from './AddRowModal'
import PublishModal from './PublishModal'
import * as api from './api'

function generateId() {
    if (window.crypto?.randomUUID) return crypto.randomUUID()
    return Math.random().toString(36).slice(2, 15) + Math.random().toString(36).slice(2, 15)
}

function fmt(val) {
    if (!val || isNaN(val)) return '0,00'
    const [int, dec] = Number(val).toFixed(2).split('.')
    return int.replace(/\B(?=(\d{3})+(?!\d))/g, '.') + ',' + dec
}

function computeTotal(row) {
    const lf  = Number(row.labourFactorial)    || 1
    const ef  = Number(row.equipmentFactorial) || 1
    const mf  = Number(row.materialFactorial)  || 1
    const vol = Number(row.volume) || 1
    return ((Number(row.laborRate) || 0) * lf
          + (Number(row.toolRate)  || 0) * ef
          + (Number(row.materialRate) || 0) * mf) * vol
}

function computeGrandTotal(rows, contingencyPct) {
    const sub = rows.reduce((acc, r) => acc + computeTotal(r), 0)
    const cont = sub * (Number(contingencyPct) / 100 || 0)
    return { sub, cont, grand: sub + cont }
}

function computeByDiscipline(rows, wbsOptions) {
    const wbsById = {}
    ;(wbsOptions || []).forEach(opt => { wbsById[opt.wbs_level3_id] = opt })

    // Seed every WBS discipline so cards appear even before rows are added
    const map = {}
    ;(wbsOptions || []).forEach(opt => {
        if (opt.discipline) {
            const key = opt.discipline.toLowerCase()
            if (!(key in map)) map[key] = 0
        }
    })

    // Use WBS-resolved discipline (reliable) over workScope (may be stale/empty)
    rows.forEach(r => {
        const opt  = wbsById[r.wbs_level3_id]
        const disc = opt?.discipline || r.workScope || ''
        if (!disc) return
        const key = disc.toLowerCase()
        map[key] = (map[key] || 0) + computeTotal(r)
    })
    return map
}

const DISC_CHIP_STYLE = {
    civil:      { bg: '#cce5e1', color: '#1b4c43', label: 'Civil' },
    mechanical: { bg: '#daeee9', color: '#24695c', label: 'Mechanical' },
    electrical: { bg: '#fdf6ee', color: '#8a5a2a', label: 'Electrical' },
    instrument: { bg: '#fef2f2', color: '#9f1239', label: 'Instrument' },
}

function nameInitials(name) {
    if (!name) return '?'
    const parts = name.trim().split(/\s+/)
    if (parts.length === 1) return parts[0][0].toUpperCase()
    return (parts[0][0] + parts[parts.length - 1][0]).toUpperCase()
}

export default function App({
    projectId,
    userId,
    userName,
    userDiscipline,
    wsUrl,
    rows: initRows,
    wbsOptions,
    contingency: initContingency,
    canPublish,
    publishStatus: initPublishStatus,
}) {
    const [saveStatus, setSaveStatus]     = useState('saved')
    const [showAddRow, setShowAddRow]     = useState(false)
    const [showPublishModal, setShowPublishModal] = useState(false)
    const [publishing, setPublishing]     = useState(false)
    const [publishStatus, setPublishStatus] = useState(initPublishStatus)
    const [remoteToast, setRemoteToast]   = useState(null)
    const [isFullscreen, setIsFullscreen] = useState(false)
    const pendingRef    = useRef(0)
    const toastTimerRef = useRef(null)

    const {
        rows, connected, synced,
        setField, setFields, addRow, removeRow, getRow, scheduleSave,
        contingency, setContingency,
        onlineUsers, lastRemoteEditor,
    } = useCollab(projectId, wsUrl, initRows, initContingency, {
        userId,
        name:       userName,
        discipline: userDiscipline,
    })

    // ─── Fullscreen ───────────────────────────────────────────────────────────

    useEffect(() => {
        document.body.style.overflow = isFullscreen ? 'hidden' : ''
        if (!isFullscreen) return
        const onKey = (e) => { if (e.key === 'Escape') setIsFullscreen(false) }
        document.addEventListener('keydown', onKey)
        return () => {
            document.removeEventListener('keydown', onKey)
            document.body.style.overflow = ''
        }
    }, [isFullscreen])

    // ─── Remote change toast ──────────────────────────────────────────────────

    useEffect(() => {
        if (!lastRemoteEditor) return
        setRemoteToast(lastRemoteEditor.by)
        clearTimeout(toastTimerRef.current)
        toastTimerRef.current = setTimeout(() => setRemoteToast(null), 2500)
    }, [lastRemoteEditor])

    // ─── Autosave callback ────────────────────────────────────────────────────

    const handleCellChange = useCallback((uid, field, value) => {
        const currentRow = getRow(uid)
        const updatedRow = { ...(currentRow || {}), [field]: value }
        setFields(uid, { [field]: value, rowTotal: computeTotal(updatedRow) })
        setSaveStatus('saving')
        pendingRef.current += 1

        scheduleSave(uid, async (row) => {
            try {
                const res = await api.autosave(projectId, { ...row, workScope: userDiscipline })
                if (res.status !== 200) throw new Error(res.message)
            } catch (e) {
                setSaveStatus('error')
                console.error('Autosave failed:', e)
            } finally {
                pendingRef.current -= 1
                if (pendingRef.current === 0) setSaveStatus('saved')
            }
        })
    }, [setFields, getRow, scheduleSave, projectId, userDiscipline])

    // ─── Batch cell change (work item selection — 6 fields in one Yjs tx) ───

    const handleBatchCellChange = useCallback((uid, fields) => {
        const currentRow = getRow(uid)
        const updatedRow = { ...(currentRow || {}), ...fields }
        setFields(uid, { ...fields, rowTotal: computeTotal(updatedRow) })
        setSaveStatus('saving')
        pendingRef.current += 1
        scheduleSave(uid, async (row) => {
            try {
                const res = await api.autosave(projectId, { ...row, workScope: userDiscipline })
                if (res.status !== 200) throw new Error(res.message)
            } catch (e) {
                setSaveStatus('error')
                console.error('Autosave failed:', e)
            } finally {
                pendingRef.current -= 1
                if (pendingRef.current === 0) setSaveStatus('saved')
            }
        })
    }, [setFields, getRow, scheduleSave, projectId, userDiscipline])

    // ─── Delete row ───────────────────────────────────────────────────────────

    const handleDeleteRow = useCallback(async (uid) => {
        removeRow(uid)
        try {
            await api.deleteRow(projectId, uid)
        } catch (e) {
            console.error('Delete failed:', e)
        }
    }, [removeRow, projectId])

    // ─── Add row ──────────────────────────────────────────────────────────────

    const handleAddRow = useCallback((rowData) => {
        const uid = generateId()
        const newRow = {
            ...rowData,
            uid,
            workScope: userDiscipline,
            rowTotal:  computeTotal(rowData),
        }
        addRow(uid, newRow)
        setShowAddRow(false)
        setSaveStatus('saving')
        pendingRef.current += 1

        api.autosave(projectId, newRow)
            .then(res => {
                if (res.status !== 200) throw new Error(res.message)
            })
            .catch(e => { setSaveStatus('error'); console.error(e) })
            .finally(() => {
                pendingRef.current -= 1
                if (pendingRef.current === 0) setSaveStatus('saved')
            })
    }, [addRow, projectId, userDiscipline])

    // ─── Inline add row (from "+" button on work-element group row) ──────────

    const handleAddRowInline = useCallback((wbs3Id, workElId, rowMeta) => {
        const uid = generateId()
        const newRow = {
            uid,
            wbs_level3_id:       wbs3Id,
            work_element_id:     workElId,
            location:            rowMeta.location    || '',
            discipline:          rowMeta.discipline  || '',
            workElement:         rowMeta.workElement || '',
            workItemId:          '',
            workItemDescription: '',
            unit:                '',
            laborRate:           0,
            toolRate:            0,
            materialRate:        0,
            volume:              1,
            labourFactorial:     1,
            equipmentFactorial:  1,
            materialFactorial:   1,
            workScope:           userDiscipline,
            rowTotal:            0,
        }
        addRow(uid, newRow)
        setSaveStatus('saving')
        pendingRef.current += 1
        api.autosave(projectId, newRow)
            .then(res => { if (res.status !== 200) throw new Error(res.message) })
            .catch(e => { setSaveStatus('error'); console.error(e) })
            .finally(() => {
                pendingRef.current -= 1
                if (pendingRef.current === 0) setSaveStatus('saved')
            })
    }, [addRow, projectId, userDiscipline])

    // ─── Contingency ─────────────────────────────────────────────────────────

    const handleContingencyChange = useCallback((e) => {
        const val = parseFloat(e.target.value) || 0
        setContingency(val)                                   // syncs to all clients via Yjs
        api.saveContingency(projectId, val).catch(console.error)  // persists to DB
    }, [projectId, setContingency])

    // ─── Publish ─────────────────────────────────────────────────────────────

    const handlePublishConfirm = useCallback(async () => {
        setPublishing(true)
        try {
            const res = await api.publish(projectId, contingency)
            if (res.status === 200) {
                setPublishStatus('PUBLISH')
                setShowPublishModal(false)
            } else {
                throw new Error(res.message || 'Publish failed')
            }
        } catch (e) {
            alert(e.message)
        } finally {
            setPublishing(false)
        }
    }, [projectId, contingency])

    // ─── Totals (all disciplines, not just current user's) ───────────────────

    const { sub, cont, grand } = computeGrandTotal(rows, contingency)
    const byDiscipline          = computeByDiscipline(rows, wbsOptions)

    // ─── Connection indicator ─────────────────────────────────────────────────

    const dotClass = connected
        ? 'connection-dot connection-connected'
        : synced ? 'connection-dot connection-disconnected'
        : 'connection-dot connection-connecting'

    const saveLabel = saveStatus === 'saving' ? 'Saving…'
        : saveStatus === 'error'  ? 'Error saving'
        : 'All changes saved'

    const disciplineBadgeClass = ({
        civil:      'est-discipline-civil',
        mechanical: 'est-discipline-mechanical',
        electrical: 'est-discipline-electrical',
        instrument: 'est-discipline-instrument',
    })[userDiscipline?.toLowerCase()] ?? 'est-discipline-default'

    return (
        <div className={`estimate-react-root${isFullscreen ? ' est-fullscreen' : ''}`}>
            {/* ── Toolbar ───────────────────────────────────────────────── */}
            <div className="est-toolbar">
                <div className="est-toolbar-left">
                    <span className={`est-discipline-badge ${disciplineBadgeClass}`}>
                        {userDiscipline?.charAt(0).toUpperCase() + userDiscipline?.slice(1)}
                    </span>
                    <span className={dotClass} title={connected ? 'Real-time connected' : 'Disconnected'} />
                    <span className={`est-save-status est-save-${saveStatus}`}>
                        {saveLabel}
                    </span>
                    {remoteToast && (
                        <span className="est-remote-toast">
                            <i className="fa fa-user-edit" />
                            {remoteToast} updated
                        </span>
                    )}
                </div>

                {/* Online users */}
                {onlineUsers.length > 0 && (
                    <div className="presence-list">
                        {onlineUsers.slice(0, 5).map(u => (
                            <div
                                key={u.clientId}
                                className={`presence-avatar${u.isLocal ? ' presence-avatar-local' : ''}`}
                                style={{ background: u.color }}
                                title={`${u.name}${u.discipline ? ` · ${u.discipline}` : ''}${u.isLocal ? ' (You)' : ''}`}
                            >
                                {nameInitials(u.name)}
                            </div>
                        ))}
                        {onlineUsers.length > 5 && (
                            <div
                                className="presence-avatar presence-avatar-overflow"
                                title={onlineUsers.slice(5).map(u => u.name).join(', ')}
                            >
                                +{onlineUsers.length - 5}
                            </div>
                        )}
                        <span className="presence-count">
                            {onlineUsers.length} online
                        </span>
                    </div>
                )}

                <div className="est-toolbar-right">
                    {canPublish && publishStatus !== 'PUBLISH' && (
                        <button
                            className="est-btn est-btn-publish"
                            onClick={() => setShowPublishModal(true)}
                            disabled={publishing || saveStatus === 'saving'}
                        >
                            <i className="fa fa-paper-plane" />
                            Publish
                        </button>
                    )}
                    {publishStatus === 'PUBLISH' && (
                        <span className="est-published-badge">
                            <i className="fa fa-check-circle" />
                            Published
                        </span>
                    )}
                    {publishStatus !== 'PUBLISH' && (
                        <button
                            className="est-btn est-btn-add"
                            onClick={() => setShowAddRow(true)}
                        >
                            <i className="fa fa-plus" />
                            Add Row
                        </button>
                    )}
                    <button
                        className="est-btn est-btn-fullscreen"
                        onClick={() => setIsFullscreen(f => !f)}
                        title={isFullscreen ? 'Exit fullscreen (Esc)' : 'Fullscreen'}
                    >
                        <i className={`fa ${isFullscreen ? 'fa-compress' : 'fa-expand'}`} />
                    </button>
                </div>
            </div>

            {/* ── Discipline stat cards ─────────────────────────────── */}
            {Object.keys(byDiscipline).length > 0 && (
                <div className="est-disc-stats">
                    {Object.entries(byDiscipline)
                        .sort(([a], [b]) => a.localeCompare(b))
                        .map(([disc, total]) => {
                            const s   = DISC_CHIP_STYLE[disc] || { bg: '#f5f7fb', color: '#59667a', label: disc.charAt(0).toUpperCase() + disc.slice(1) }
                            const pct = sub > 0 ? (total / sub) * 100 : 0
                            return (
                                <div key={disc} className="est-disc-stat-card" style={{ borderTopColor: s.color }}>
                                    <div className="est-disc-stat-name" style={{ color: s.color }}>{s.label}</div>
                                    <div className="est-disc-stat-value">{fmt(total)}</div>
                                    <div className="est-disc-stat-bar-wrap">
                                        <div className="est-disc-stat-bar-fill" style={{ width: `${pct}%`, background: s.color }} />
                                    </div>
                                    <div className="est-disc-stat-pct" style={{ color: s.color }}>{pct.toFixed(1)}%</div>
                                </div>
                            )
                        })
                    }
                </div>
            )}

            {/* ── Published lock banner ────────────────────────────────── */}
            {publishStatus === 'PUBLISH' && (
                <div className="est-published-banner">
                    <i className="fa fa-lock" />
                    <div>
                        <strong>Estimate submitted for review</strong>
                        <span>This estimate is locked. You can edit it again once the reviewer responds.</span>
                    </div>
                </div>
            )}

            {/* ── Grid ─────────────────────────────────────────────────── */}
            <EstimateGrid
                rows={rows}
                wbsOptions={wbsOptions}
                userDiscipline={userDiscipline}
                isReadOnly={publishStatus === 'PUBLISH'}
                isFullscreen={isFullscreen}
                onCellChange={handleCellChange}
                onBatchCellChange={handleBatchCellChange}
                onDeleteRow={handleDeleteRow}
                onAddRowInline={handleAddRowInline}
            />

            {/* ── Totals footer ────────────────────────────────────────── */}
            <div className="est-totals mb-5">
                <div className="est-contingency">
                    <label className="est-contingency-label">Contingency</label>
                    <div className="est-contingency-input-wrap">
                        <input
                            type="number"
                            className="est-contingency-input"
                            value={contingency}
                            onChange={handleContingencyChange}
                            min="0"
                            max="100"
                            step="0.5"
                        />
                        <span className="est-contingency-pct">%</span>
                    </div>
                </div>
                <div className="est-totals-numbers">
                    <div className="est-total-item">
                        <span className="est-total-label">Subtotal</span>
                        <span className="est-total-value">{fmt(sub)}</span>
                    </div>
                    <div className="est-total-sep" />
                    <div className="est-total-item">
                        <span className="est-total-label">Contingency</span>
                        <span className="est-total-value">{fmt(cont)}</span>
                    </div>
                    <div className="est-total-sep" />
                    <div className="est-total-item">
                        <span className="est-total-label">Grand Total</span>
                        <span className="est-total-value est-total-grand">{fmt(grand)}</span>
                    </div>
                </div>
            </div>

            {/* ── Add row modal ─────────────────────────────────────────── */}
            {showAddRow && (
                <AddRowModal
                    wbsOptions={wbsOptions}
                    onAdd={handleAddRow}
                    onClose={() => setShowAddRow(false)}
                />
            )}

            {/* ── Publish confirmation modal ────────────────────────────── */}
            {showPublishModal && (
                <PublishModal
                    userDiscipline={userDiscipline}
                    contingency={contingency}
                    publishing={publishing}
                    onConfirm={handlePublishConfirm}
                    onClose={() => !publishing && setShowPublishModal(false)}
                />
            )}
        </div>
    )
}
