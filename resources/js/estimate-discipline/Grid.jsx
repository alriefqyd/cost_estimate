import React, { useCallback, useMemo, useRef, useState } from 'react'
import { AgGridReact } from 'ag-grid-react'
import 'ag-grid-community/styles/ag-grid.css'
import 'ag-grid-community/styles/ag-theme-alpine.css'
import WorkItemSearch from './WorkItemSearch'

// ─── Formatters ──────────────────────────────────────────────────────────────

function fmt(val) {
    if (val == null || val === '' || isNaN(Number(val))) return ''
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

// ─── Discipline chip colors ───────────────────────────────────────────────────

const DISC_COLORS = {
    civil:      { bg: '#cce5e1', color: '#1b4c43' },
    mechanical: { bg: '#daeee9', color: '#24695c' },
    electrical: { bg: '#fdf6ee', color: '#8a5a2a' },
    instrument: { bg: '#fef2f2', color: '#9f1239' },
}

// ─── Hierarchy cell renderers (one per group level) ──────────────────────────

function LocCellRenderer({ data, context }) {
    if (!data) return null
    if (data._type === 'h-loc') return (
        <div
            className="grid-group-row grid-group-row-loc grid-group-collapsible"
            onClick={() => context.toggleCollapsed?.(data._rowId)}
        >
            <i className={`fa fa-chevron-${data._isCollapsed ? 'right' : 'down'} grid-group-chevron`} />
            <i className="fa fa-map-marker-alt grid-group-icon" />
            <span className="grid-group-label">{data._label}</span>
        </div>
    )
    if (data._type === 'footer-add') return (
        <button
            className="grid-footer-add-btn"
            title={`Add row to ${data.workElement}`}
            onClick={e => {
                e.stopPropagation()
                context.openAddRow(data._wbs_level3_id, data._work_element_id, data)
            }}
        >
            <i className="fa fa-plus" />
            Add row
        </button>
    )
    if (data._type === 'data') return (
        <span className="cell-context-text" title={data.location}>{data.location}</span>
    )
    return null
}

function DisCellRenderer({ data, context }) {
    if (!data) return null
    if (data._type === 'h-dis') return (
        <div
            className="grid-group-row grid-group-row-dis grid-group-collapsible"
            onClick={() => context.toggleCollapsed?.(data._rowId)}
        >
            <i className={`fa fa-chevron-${data._isCollapsed ? 'right' : 'down'} grid-group-chevron`} />
            <i className="fa fa-layer-group grid-group-icon" />
            <span className="grid-group-label">{data._label}</span>
        </div>
    )
    if (data._type === 'data') {
        const chip = DISC_COLORS[data.workScope?.toLowerCase()]
        if (chip) return (
            <span className="disc-badge" style={{ background: chip.bg, color: chip.color }}>
                {data.discipline}
            </span>
        )
        return <span className="cell-context-text" title={data.discipline}>{data.discipline}</span>
    }
    return null
}

function WeCellRenderer({ data, context }) {
    if (!data) return null
    if (data._type === 'h-we') return (
        <div
            className="grid-group-row grid-group-row-we grid-group-collapsible"
            onClick={() => context.toggleCollapsed?.(data._rowId)}
        >
            <i className={`fa fa-chevron-${data._isCollapsed ? 'right' : 'down'} grid-group-chevron`} />
            <i className="fa fa-wrench grid-group-icon" />
            <span className="grid-group-label">{data._label}</span>
            {!context.isReadOnly && !data._isCollapsed && (
                <button
                    className="grid-add-btn"
                    title="Add work item to this group"
                    onClick={e => {
                        e.stopPropagation()
                        context.openAddRow(data._wbs_level3_id, data._work_element_id, data)
                    }}
                >+</button>
            )}
        </div>
    )
    if (data._type === 'data') return (
        <span className="cell-context-text" title={data.workElement}>{data.workElement}</span>
    )
    return null
}

// ─── Work item cell renderer (click to open search) ──────────────────────────

function WorkItemCellRenderer({ value, data, context }) {
    if (!data || data._type !== 'data') return null
    const disc = data.workScope || data.discipline || ''
    const canEdit = disc
        ? disc.toLowerCase() === context.userDiscipline?.toLowerCase()
        : false
    return (
        <div
            className={`wi-cell ${canEdit ? 'wi-cell-editable' : 'wi-cell-readonly'}`}
            onClick={() => canEdit && context.openWorkItemSearch(data.uid)}
            title={canEdit ? 'Click to select work item' : `Read-only (${data.workScope})`}
        >
            <span>{value || <em className="text-muted">— click to select —</em>}</span>
            {canEdit && <span className="wi-cell-edit-hint">✏</span>}
        </div>
    )
}

// ─── Volume cell renderer (shows unit badge) ──────────────────────────────────

function VolCellRenderer({ value, data }) {
    if (!data || data._type !== 'data') return null
    return (
        <div style={{ display: 'flex', alignItems: 'center', gap: 4, width: '100%', justifyContent: 'flex-end' }}>
            <span>{value ?? ''}</span>
            {data.unit && <span className="vol-unit-badge">{data.unit}</span>}
        </div>
    )
}

// ─── Number cell editor ───────────────────────────────────────────────────────

const NumberCellEditor = React.forwardRef(({ value, stopEditing }, ref) => {
    const inputRef = useRef(null)
    React.useImperativeHandle(ref, () => ({
        getValue:         () => parseFloat(inputRef.current?.value) || 0,
        afterGuiAttached: () => { inputRef.current?.focus(); inputRef.current?.select() },
    }))
    return (
        <input
            ref={inputRef}
            type="number"
            step="any"
            defaultValue={value}
            className="ag-cell-edit-input"
            onKeyDown={e => {
                if (e.key === 'Enter' || e.key === 'Tab') stopEditing()
                if (e.key === 'Escape') stopEditing(true)
            }}
        />
    )
})
NumberCellEditor.displayName = 'NumberCellEditor'

// ─── Column count breakdown ───────────────────────────────────────────────────
//
//  Non-pinned columns (1–11):
//    1  col-loc   Loc / Equip
//    2  col-dis   Discipline
//    3  col-we    Work Element
//    4  col-wi    Work Item
//    5  col-vol   Vol
//    6  col-mp    Man Power
//    7  col-eq    Equipment
//    8  col-mat   Material
//    9  col-lf    Labor Fac
//   10  col-ef    Equip. Fac
//   11  col-mf    Mat. Fac
//
//  Pinned-right (separate layout area, not affected by colSpan):
//   12  col-total  Total
//   13  col-del    Delete
//
//  colSpan values:
//    h-loc  → col-loc spans 11  (covers cols 1-11)
//    h-dis  → col-dis spans 10  (covers cols 2-11)
//    h-we   → col-we  spans  9  (covers cols 3-11)

// ─── Main Grid component ──────────────────────────────────────────────────────

export default function EstimateGrid({ rows, wbsOptions, userDiscipline, isReadOnly, isFullscreen, onCellChange, onBatchCellChange, onDeleteRow, onAddRowInline }) {
    const gridRef = useRef(null)
    const [wiSearchUid, setWiSearchUid] = useState(null)
    const [collapsed, setCollapsed] = useState(new Set())

    const toggleCollapsed = useCallback((rowId) => {
        setCollapsed(prev => {
            const next = new Set(prev)
            if (next.has(rowId)) next.delete(rowId)
            else next.add(rowId)
            return next
        })
    }, [])

    // Build a lookup from wbs_level3_id → { location, workElement } using fresh DB data.
    // This bypasses any stale values cached in the Yjs document.
    const wbsById = useMemo(() => {
        const map = {}
        ;(wbsOptions || []).forEach(opt => { map[opt.wbs_level3_id] = opt })
        return map
    }, [wbsOptions])

    // Include rows that have a wbs group assigned (even if work item not yet picked)
    const displayRows = useMemo(() => {
        const dataRows = rows.filter(r => r.wbs_level3_id || r.workItemId)

        const resolve = row => {
            const opt = wbsById[row.wbs_level3_id]
            return {
                loc:  (opt?.location    || row.location    || '').trim(),
                disc: (opt?.discipline  || row.discipline  || '').trim(),
                we:   (opt?.workElement || row.workElement || '').trim(),
            }
        }

        dataRows.sort((a, b) => {
            const ra = resolve(a), rb = resolve(b)
            const lc = ra.loc.localeCompare(rb.loc)
            if (lc) return lc
            const dc = ra.disc.localeCompare(rb.disc)
            if (dc) return dc
            return ra.we.localeCompare(rb.we)
        })

        // Pre-compute totals per discipline group for the pinned total column
        const disciplineTotals = {}
        dataRows.forEach(row => {
            const { loc, disc } = resolve(row)
            const key = `${loc}|${disc}`
            disciplineTotals[key] = (disciplineTotals[key] || 0) + computeTotal(row)
        })

        const result = []
        const seen   = new Set()

        dataRows.forEach(row => {
            const { loc, disc, we } = resolve(row)

            const locKey = `h-loc:${loc}`
            const disKey = `h-dis:${loc}|${disc}`
            const weKey  = `h-we:${loc}|${disc}|${we}`

            const locCollapsed = collapsed.has(locKey)
            const disCollapsed = collapsed.has(disKey)
            const weCollapsed  = collapsed.has(weKey)

            if (!seen.has(locKey)) {
                seen.add(locKey)
                result.push({ _rowId: locKey, _type: 'h-loc', _label: loc, _depth: 0, _isCollapsed: locCollapsed })
            }
            if (!seen.has(disKey)) {
                seen.add(disKey)
                if (!locCollapsed) {
                    result.push({
                        _rowId:           disKey,
                        _type:            'h-dis',
                        _label:           disc,
                        _depth:           1,
                        _isCollapsed:     disCollapsed,
                        _disciplineTotal: disciplineTotals[`${loc}|${disc}`] || 0,
                    })
                }
            }
            if (!seen.has(weKey)) {
                seen.add(weKey)
                if (!locCollapsed && !disCollapsed) {
                    result.push({
                        _rowId:           weKey,
                        _type:            'h-we',
                        _label:           we,
                        _depth:           2,
                        _isCollapsed:     weCollapsed,
                        _wbs_level3_id:   row.wbs_level3_id,
                        _work_element_id: row.work_element_id,
                        location:         loc,
                        discipline:       disc,
                        workElement:      we,
                    })
                }
            }
            if (!locCollapsed && !disCollapsed && !weCollapsed) {
                result.push({ ...row, _rowId: row.uid, _type: 'data', _depth: 3, location: loc, discipline: disc, workElement: we })
            }
        })

        if (isReadOnly) return result

        // Insert a footer-add row after the last data row of each work-element group
        const finalResult = []
        for (let i = 0; i < result.length; i++) {
            finalResult.push(result[i])
            const curr = result[i]
            const next = result[i + 1]
            if (curr._type === 'data') {
                const nextIsDataInSameGroup = next?._type === 'data' && next.wbs_level3_id === curr.wbs_level3_id
                if (!nextIsDataInSameGroup) {
                    finalResult.push({
                        _rowId:           `footer-add:${curr.wbs_level3_id}`,
                        _type:            'footer-add',
                        _wbs_level3_id:   curr.wbs_level3_id,
                        _work_element_id: curr.work_element_id,
                        location:         curr.location,
                        discipline:       curr.discipline,
                        workElement:      curr.workElement,
                    })
                }
            }
        }
        return finalResult
    }, [rows, wbsById, isReadOnly, collapsed])

    const canEdit = useCallback(data => {
        if (isReadOnly || data?._type !== 'data') return false
        // Primary check: explicit workScope set by the new React feature
        if (data.workScope) return data.workScope === userDiscipline
        // Fallback: infer ownership from the WBS discipline (old rows before workScope was stored)
        if (data.discipline) return data.discipline.toLowerCase() === userDiscipline?.toLowerCase()
        // No ownership info at all — treat as unowned/not editable
        return false
    }, [isReadOnly, userDiscipline])

    const columnDefs = useMemo(() => [
        // ─── Hierarchy columns ───────────────────────────────────────────────
        {
            colId:        'col-loc',
            headerName:   'Loc / Equip',
            field:        'location',
            minWidth:     120,
            colSpan:      params => (params.data?._type === 'h-loc' || params.data?._type === 'footer-add') ? 11 : 1,
            cellRenderer: LocCellRenderer,
            editable:     false,
            sortable:     false,
            resizable:    true,
        },
        {
            colId:        'col-dis',
            headerName:   'Discipline',
            field:        'discipline',
            minWidth:     110,
            colSpan:      params => params.data?._type === 'h-dis' ? 10 : 1,
            cellRenderer: DisCellRenderer,
            editable:     false,
            sortable:     false,
            resizable:    true,
        },
        {
            colId:        'col-we',
            headerName:   'Work Element',
            field:        'workElement',
            minWidth:     150,
            colSpan:      params => params.data?._type === 'h-we' ? 9 : 1,
            cellRenderer: WeCellRenderer,
            editable:     false,
            sortable:     false,
            resizable:    true,
        },
        // ─── Data columns ────────────────────────────────────────────────────
        {
            colId:        'col-wi',
            headerName:   'Work Item',
            field:        'workItemDescription',
            flex:         2,
            minWidth:     220,
            editable:     false,
            cellRenderer: WorkItemCellRenderer,
            suppressKeyboardEvent: () => true,
        },
        {
            colId:        'col-vol',
            headerName:   'Vol',
            field:        'volume',
            width:        90,
            editable:     params => canEdit(params.data),
            cellEditor:   NumberCellEditor,
            cellRenderer: VolCellRenderer,
            cellClass:    params => [!canEdit(params.data) ? 'cell-readonly' : 'cell-editable', 'cell-num'],
        },
        {
            colId:     'col-mp',
            headerName: 'Man Power',
            field:      'laborRate',
            width:      120,
            editable:   false,
            cellClass:  ['cell-readonly', 'cell-num'],
            valueFormatter: params => params.data?._type === 'data'
                ? fmt((Number(params.data.laborRate) || 0) * (Number(params.data.labourFactorial) || 1))
                : '',
        },
        {
            colId:      'col-eq',
            headerName: 'Equipment',
            field:      'toolRate',
            width:      120,
            editable:   false,
            cellClass:  ['cell-readonly', 'cell-num'],
            valueFormatter: params => params.data?._type === 'data'
                ? fmt((Number(params.data.toolRate) || 0) * (Number(params.data.equipmentFactorial) || 1))
                : '',
        },
        {
            colId:      'col-mat',
            headerName: 'Material',
            field:      'materialRate',
            width:      120,
            editable:   false,
            cellClass:  ['cell-readonly', 'cell-num'],
            valueFormatter: params => params.data?._type === 'data'
                ? fmt((Number(params.data.materialRate) || 0) * (Number(params.data.materialFactorial) || 1))
                : '',
        },
        {
            colId:      'col-lf',
            headerName: 'Labor Fac',
            field:      'labourFactorial',
            width:      85,
            editable:   params => canEdit(params.data),
            cellEditor: NumberCellEditor,
            cellClass:  params => [!canEdit(params.data) ? 'cell-readonly' : 'cell-editable', 'cell-num'],
            valueFormatter: params => params.data?._type === 'data' ? (params.value ?? '') : '',
        },
        {
            colId:      'col-ef',
            headerName: 'Equip. Fac',
            field:      'equipmentFactorial',
            width:      85,
            editable:   params => canEdit(params.data),
            cellEditor: NumberCellEditor,
            cellClass:  params => [!canEdit(params.data) ? 'cell-readonly' : 'cell-editable', 'cell-num'],
            valueFormatter: params => params.data?._type === 'data' ? (params.value ?? '') : '',
        },
        {
            colId:      'col-mf',
            headerName: 'Mat. Fac',
            field:      'materialFactorial',
            width:      85,
            editable:   params => canEdit(params.data),
            cellEditor: NumberCellEditor,
            cellClass:  params => [!canEdit(params.data) ? 'cell-readonly' : 'cell-editable', 'cell-num'],
            valueFormatter: params => params.data?._type === 'data' ? (params.value ?? '') : '',
        },
        {
            colId:      'col-total',
            headerName: 'Total',
            field:      'totalCost',
            width:      140,
            editable:   false,
            pinned:     'right',
            cellClass:  params => {
                if (params.data?._type === 'data') return ['cell-total', 'cell-num']
                if (params.data?._type === 'h-dis') return ['cell-dis-total', 'cell-num']
                return ''
            },
            valueFormatter: params => {
                if (params.data?._type === 'data') return fmt(computeTotal(params.data))
                if (params.data?._type === 'h-dis') return fmt(params.data._disciplineTotal || 0)
                return ''
            },
        },
        {
            colId:      'col-del',
            headerName: '',
            field:      '_actions',
            width:      48,
            pinned:     'right',
            editable:   false,
            sortable:   false,
            resizable:  false,
            cellRenderer: params => {
                if (params.data?._type !== 'data') return null
                if (!canEdit(params.data)) return null
                return (
                    <button
                        className="btn-delete-row"
                        title="Delete row"
                        onClick={() => onDeleteRow(params.data.uid)}
                    >✕</button>
                )
            },
        },
    ], [canEdit, onDeleteRow])

    const defaultColDef = useMemo(() => ({
        sortable:        false,
        resizable:       true,
        suppressMovable: true,
    }), [])


    const handleCellValueChanged = useCallback(params => {
        const { data, colDef, newValue } = params
        if (data?._type !== 'data') return
        onCellChange(data.uid, colDef.field, newValue)
    }, [onCellChange])

    // Flash rows when a remote update changes numeric fields
    const prevRowsRef = useRef(rows)
    React.useEffect(() => {
        const api = gridRef.current?.api
        if (!api) return
        const prev = new Map(prevRowsRef.current.map(r => [r.uid, r]))
        rows.forEach(row => {
            const old = prev.get(row.uid)
            if (!old) return
            const changed = ['volume', 'labourFactorial', 'equipmentFactorial', 'materialFactorial']
                .some(f => old[f] !== row[f])
            if (changed) {
                const node = api.getRowNode(row.uid)
                if (node) api.flashCells({ rowNodes: [node] })
            }
        })
        prevRowsRef.current = rows
    }, [rows])

    const context = useMemo(() => ({
        userDiscipline,
        isReadOnly,
        toggleCollapsed,
        openWorkItemSearch: uid => setWiSearchUid(uid),
        openAddRow: (wbs3Id, workElId, rowMeta) => {
            if (onAddRowInline) onAddRowInline(wbs3Id, workElId, rowMeta)
        },
    }), [userDiscipline, isReadOnly, toggleCollapsed, onAddRowInline])

    const handleWorkItemSelected = useCallback(item => {
        if (!wiSearchUid) return
        const fields = {
            workItemId:          item.workItemId,
            workItemDescription: item.workItemDescription,
            unit:                item.unit,
            laborRate:           item.laborRate,
            toolRate:            item.toolRate,
            materialRate:        item.materialRate,
        }
        if (onBatchCellChange) {
            onBatchCellChange(wiSearchUid, fields)
        } else {
            Object.entries(fields).forEach(([f, v]) => onCellChange(wiSearchUid, f, v))
        }
        setWiSearchUid(null)
    }, [wiSearchUid, onCellChange, onBatchCellChange])

    return (
        <>
            <div className="ag-theme-alpine estimate-grid-wrap">
                <AgGridReact
                    ref={gridRef}
                    rowData={displayRows}
                    columnDefs={columnDefs}
                    defaultColDef={defaultColDef}
                    getRowId={params => params.data._rowId || params.data.uid}
                    rowHeight={40}
                    headerHeight={40}
                    enableCellChangeFlash={true}
                    singleClickEdit={true}
                    stopEditingWhenCellsLoseFocus={true}
                    onCellValueChanged={handleCellValueChanged}
                    context={context}
                    suppressScrollOnNewData={true}
                    rowClassRules={{
                        'row-group-loc':  params => params.data?._type === 'h-loc',
                        'row-group-dis':  params => params.data?._type === 'h-dis',
                        'row-group-we':   params => params.data?._type === 'h-we',
                        'row-footer-add': params => params.data?._type === 'footer-add',
                        'row-own': params =>
                            params.data?._type === 'data' &&
                            (!params.data.workScope || params.data.workScope === userDiscipline),
                        'row-readonly': params =>
                            params.data?._type === 'data' &&
                            params.data.workScope &&
                            params.data.workScope !== userDiscipline,
                        'row-scope-civil': params =>
                            params.data?._type === 'data' &&
                            params.data.workScope?.toLowerCase() === 'civil' &&
                            params.data.workScope !== userDiscipline,
                        'row-scope-mechanical': params =>
                            params.data?._type === 'data' &&
                            params.data.workScope?.toLowerCase() === 'mechanical' &&
                            params.data.workScope !== userDiscipline,
                        'row-scope-electrical': params =>
                            params.data?._type === 'data' &&
                            params.data.workScope?.toLowerCase() === 'electrical' &&
                            params.data.workScope !== userDiscipline,
                        'row-scope-instrument': params =>
                            params.data?._type === 'data' &&
                            params.data.workScope?.toLowerCase() === 'instrument' &&
                            params.data.workScope !== userDiscipline,
                    }}
                    animateRows={false}
                    domLayout="normal"
                />
            </div>

            {wiSearchUid && (
                <WorkItemSearch
                    onSelect={handleWorkItemSelected}
                    onClose={() => setWiSearchUid(null)}
                />
            )}
        </>
    )
}
