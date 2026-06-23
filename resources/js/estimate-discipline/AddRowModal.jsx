import React, { useState } from 'react'
import WorkItemSearch from './WorkItemSearch'

export default function AddRowModal({ wbsOptions, onAdd, onClose }) {
    const [wbsId, setWbsId]             = useState('')
    const [showSearch, setShowSearch]   = useState(false)
    const [workItem, setWorkItem]       = useState(null)

    const selected = wbsOptions.find(o => String(o.wbs_level3_id) === String(wbsId))

    const handleSelect = (item) => {
        setWorkItem(item)
        setShowSearch(false)
    }

    const handleAdd = () => {
        if (!selected || !workItem) return
        onAdd({
            wbs_level3_id:   selected.wbs_level3_id,
            work_element_id: selected.work_element_id,
            location:        selected.location,
            discipline:      selected.discipline,
            workElement:     selected.workElement,
            ...workItem,
            volume:          1,
            labourFactorial:    1,
            equipmentFactorial: 1,
            materialFactorial:  1,
        })
    }

    return (
        <div className="modal fade show d-block" tabIndex="-1" role="dialog" style={{ background: 'rgba(0,0,0,.4)' }}>
            <div className="modal-dialog" role="document">
                <div className="modal-content">
                    <div className="modal-header">
                        <h5 className="modal-title">Add Work Item</h5>
                        <button className="btn-close" onClick={onClose} />
                    </div>
                    <div className="modal-body">
                        <div className="mb-3">
                            <label className="form-label fw-bold">Work Element</label>
                            <select
                                className="form-select"
                                value={wbsId}
                                onChange={e => { setWbsId(e.target.value); setWorkItem(null) }}
                            >
                                <option value="">— Select —</option>
                                {wbsOptions.map(o => (
                                    <option key={o.wbs_level3_id} value={o.wbs_level3_id}>
                                        {o.label}
                                    </option>
                                ))}
                            </select>
                        </div>

                        <div className="mb-3">
                            <label className="form-label fw-bold">Work Item</label>
                            <div
                                className="form-control d-flex align-items-center justify-content-between cursor-pointer"
                                style={{ minHeight: 38 }}
                                onClick={() => wbsId && setShowSearch(true)}
                            >
                                <span className={workItem ? '' : 'text-muted'}>
                                    {workItem ? workItem.workItemDescription : 'Click to search…'}
                                </span>
                                {workItem && <span className="badge bg-secondary ms-2">{workItem.unit}</span>}
                            </div>
                        </div>

                        {selected && workItem && (
                            <div className="table-responsive">
                                <table className="table table-sm table-bordered mb-0">
                                    <thead><tr>
                                        <th>Man Power</th><th>Equipment</th><th>Material</th>
                                    </tr></thead>
                                    <tbody><tr>
                                        <td>{workItem.laborRate?.toLocaleString()}</td>
                                        <td>{workItem.toolRate?.toLocaleString()}</td>
                                        <td>{workItem.materialRate?.toLocaleString()}</td>
                                    </tr></tbody>
                                </table>
                            </div>
                        )}
                    </div>
                    <div className="modal-footer">
                        <button className="btn btn-secondary" onClick={onClose}>Cancel</button>
                        <button
                            className="btn btn-primary"
                            onClick={handleAdd}
                            disabled={!selected || !workItem}
                        >Add Row</button>
                    </div>
                </div>
            </div>
            {showSearch && <WorkItemSearch onSelect={handleSelect} onClose={() => setShowSearch(false)} />}
        </div>
    )
}
