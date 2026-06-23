import React, { useState, useEffect, useRef } from 'react'
import { searchWorkItems } from './api'

// Module-level cache — survives re-mounts within the same session
const queryCache = new Map()

export default function WorkItemSearch({ onSelect, onClose }) {
    const [query, setQuery]     = useState('')
    const [results, setResults] = useState([])
    const [loading, setLoading] = useState(false)
    const inputRef  = useRef(null)
    const timerRef  = useRef(null)
    const abortRef  = useRef(null)

    useEffect(() => { inputRef.current?.focus() }, [])

    useEffect(() => {
        clearTimeout(timerRef.current)
        abortRef.current?.abort()

        const q = query.trim()
        if (q.length < 2) { setResults([]); setLoading(false); return }

        // Return cached result immediately — no network, no delay
        if (queryCache.has(q)) {
            setResults(queryCache.get(q))
            setLoading(false)
            return
        }

        setLoading(true)
        timerRef.current = setTimeout(async () => {
            const controller = new AbortController()
            abortRef.current = controller
            try {
                const data = await searchWorkItems(q, controller.signal)
                const flat = Array.isArray(data) ? data : []
                queryCache.set(q, flat)
                setResults(flat)
            } catch (e) {
                if (e.name !== 'AbortError') setResults([])
            } finally {
                setLoading(false)
            }
        }, 200)

        return () => {
            clearTimeout(timerRef.current)
            abortRef.current?.abort()
        }
    }, [query])

    const pick = (item) => {
        onSelect({
            workItemId:          item.id,
            workItemDescription: item.text.replace(/ - \(REVIEWED\)| - \(DRAFT\)/g, ''),
            unit:                item.unit ?? '',
            laborRate:           item.manPowersTotalRateInt ?? 0,
            toolRate:            item.equipmentToolsRateInt ?? 0,
            materialRate:        item.materialsRateInt      ?? 0,
        })
    }

    return (
        <div className="wi-search-overlay" onClick={onClose}>
            <div className="wi-search-panel" onClick={e => e.stopPropagation()}>
                <div className="wi-search-header">
                    <input
                        ref={inputRef}
                        className="wi-search-input"
                        placeholder="Search work items… (min. 2 characters)"
                        value={query}
                        onChange={e => setQuery(e.target.value)}
                    />
                    <button className="wi-search-close" onClick={onClose}>✕</button>
                </div>
                <div className="wi-search-results">
                    {loading && <div className="wi-search-hint">Searching…</div>}
                    {!loading && query.trim().length < 2 && (
                        <div className="wi-search-hint">Type at least 2 characters…</div>
                    )}
                    {!loading && query.trim().length >= 2 && results.length === 0 && (
                        <div className="wi-search-hint">No results for "{query}"</div>
                    )}
                    {results.map(item => (
                        <div key={item.id} className="wi-search-item" onClick={() => pick(item)}>
                            <span className="wi-search-item-name">
                                {item.text.replace(/ - \(REVIEWED\)| - \(DRAFT\)/g, '')}
                            </span>
                            <span className="wi-search-item-unit">{item.unit}</span>
                        </div>
                    ))}
                </div>
            </div>
        </div>
    )
}
