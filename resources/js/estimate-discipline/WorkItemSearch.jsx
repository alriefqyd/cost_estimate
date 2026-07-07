import React, { useState, useEffect, useRef, useCallback } from 'react'
import { searchWorkItems } from './api'

const PAGE_SIZE = 10

// Module-level cache — survives re-mounts within the same session
const pageCache = new Map()

export default function WorkItemSearch({ onSelect, onClose }) {
    const [query, setQuery]     = useState('')
    const [results, setResults] = useState([])
    const [hasMore, setHasMore] = useState(false)
    const [loading, setLoading] = useState(false)
    const inputRef  = useRef(null)
    const listRef   = useRef(null)
    const timerRef  = useRef(null)
    const abortRef  = useRef(null)
    const offsetRef = useRef(0)

    useEffect(() => { inputRef.current?.focus() }, [])

    const loadPage = useCallback((q, offset) => {
        abortRef.current?.abort()

        const cacheKey = `${q}::${offset}`
        if (pageCache.has(cacheKey)) {
            const page = pageCache.get(cacheKey)
            setResults(prev => offset === 0 ? page.items : [...prev, ...page.items])
            setHasMore(page.hasMore)
            setLoading(false)
            return
        }

        setLoading(true)
        const controller = new AbortController()
        abortRef.current = controller
        searchWorkItems(q, offset, controller.signal)
            .then(data => {
                const page = { items: Array.isArray(data?.items) ? data.items : [], hasMore: !!data?.hasMore }
                pageCache.set(cacheKey, page)
                setResults(prev => offset === 0 ? page.items : [...prev, ...page.items])
                setHasMore(page.hasMore)
            })
            .catch(e => { if (e.name !== 'AbortError') { if (offset === 0) setResults([]); setHasMore(false) } })
            .finally(() => setLoading(false))
    }, [])

    // Reset pagination and load the first page whenever the query changes (debounced)
    useEffect(() => {
        clearTimeout(timerRef.current)
        const q = query.trim()
        offsetRef.current = 0
        if (listRef.current) listRef.current.scrollTop = 0
        timerRef.current = setTimeout(() => loadPage(q, 0), 200)
        return () => clearTimeout(timerRef.current)
    }, [query, loadPage])

    const loadMore = useCallback(() => {
        if (loading || !hasMore) return
        offsetRef.current += PAGE_SIZE
        loadPage(query.trim(), offsetRef.current)
    }, [loading, hasMore, query, loadPage])

    const handleScroll = e => {
        const el = e.target
        if (el.scrollHeight - el.scrollTop - el.clientHeight < 80) loadMore()
    }

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

    // Group consecutive results by category for display
    let lastCategory = null

    return (
        <div className="wi-search-overlay" onClick={onClose}>
            <div className="wi-search-panel" onClick={e => e.stopPropagation()}>
                <div className="wi-search-header">
                    <input
                        ref={inputRef}
                        className="wi-search-input"
                        placeholder="Search work items or category…"
                        value={query}
                        onChange={e => setQuery(e.target.value)}
                    />
                    <button className="wi-search-close" onClick={onClose}>✕</button>
                </div>
                <div className="wi-search-results" ref={listRef} onScroll={handleScroll}>
                    {!loading && results.length === 0 && (
                        <div className="wi-search-hint">
                            {query.trim() ? `No results for "${query}"` : 'No work items found'}
                        </div>
                    )}
                    {results.map(item => {
                        const showHeader = item.category !== lastCategory
                        lastCategory = item.category
                        return (
                            <React.Fragment key={item.id}>
                                {showHeader && (
                                    <div className="wi-search-category-header">{item.category}</div>
                                )}
                                <div className="wi-search-item" onClick={() => pick(item)}>
                                    <span className="wi-search-item-name">
                                        {item.text.replace(/ - \(REVIEWED\)| - \(DRAFT\)/g, '')}
                                    </span>
                                    <span className="wi-search-item-unit">{item.unit}</span>
                                </div>
                            </React.Fragment>
                        )
                    })}
                    {loading && <div className="wi-search-hint">Loading…</div>}
                </div>
                <div className="wi-search-footer">
                    <a
                        className="wi-search-create-btn"
                        href="/work-item/create"
                        target="_blank"
                        rel="noopener noreferrer"
                    >
                        <i className="fa fa-plus" /> Create new work item
                    </a>
                </div>
            </div>
        </div>
    )
}
