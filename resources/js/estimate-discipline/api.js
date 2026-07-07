const csrf = () => document.querySelector('meta[name="csrf-token"]')?.content ?? ''

// Factorials default to 1 when unset, but an explicit 0 must zero out the cost — parseFloat(0) || 1 would wrongly reset it to 1.
function factorialOr1(val) {
    return (val === null || val === undefined || val === '') ? 1 : parseFloat(val)
}

function formBody(obj) {
    const p = new URLSearchParams()
    Object.entries(obj).forEach(([k, v]) => p.set(k, v ?? ''))
    return p
}

export async function autosave(projectId, row) {
    const res = await fetch(`/project/${projectId}/estimate-discipline/autosave`, {
        method:  'POST',
        headers: { 'X-CSRF-TOKEN': csrf(), 'Accept': 'application/json' },
        body: formBody({
            unique_identifier:   row.uid,
            workItem:            row.workItemId,
            workItemText:        row.workItemDescription,
            vol:                 row.volume,
            labourFactorial:     row.labourFactorial,
            equipmentFactorial:  row.equipmentFactorial,
            materialFactorial:   row.materialFactorial,
            labourUnitRate:      row.laborRate,
            equipmentUnitRate:   row.toolRate,
            materialUnitRate:    row.materialRate,
            totalRateManPowers:  row.laborRate  * factorialOr1(row.labourFactorial),
            totalRateEquipments: row.toolRate   * factorialOr1(row.equipmentFactorial),
            totalRateMaterials:  row.materialRate * factorialOr1(row.materialFactorial),
            wbs_level3:          row.wbs_level3_id,
            work_element:        row.work_element_id,
        }),
    })
    return res.json()
}

export async function deleteRow(projectId, uid) {
    await fetch(`/project/${projectId}/estimate-discipline/row/${uid}`, {
        method:  'DELETE',
        headers: { 'X-CSRF-TOKEN': csrf(), 'Accept': 'application/json' },
        body:    formBody({}),
    })
}

export async function saveContingency(projectId, value) {
    const res = await fetch(`/project/${projectId}/estimate-discipline/contingency`, {
        method:  'POST',
        headers: { 'X-CSRF-TOKEN': csrf(), 'Accept': 'application/json' },
        body:    formBody({ contingency: value }),
    })
    return res.json()
}

export async function searchWorkItems(q, offset, signal) {
    const res = await fetch(`/work-items/search?q=${encodeURIComponent(q)}&offset=${offset}`, { signal })
    return res.json()
}

export async function getWorkItemBreakdown(workItemId) {
    const res = await fetch(`/work-items/${workItemId}/breakdown`)
    return res.json()
}

export async function publish(projectId, contingency) {
    const res = await fetch(`/project/${projectId}/estimate-discipline/publish`, {
        method:  'POST',
        headers: { 'X-CSRF-TOKEN': csrf(), 'Accept': 'application/json' },
        body:    formBody({ contingency }),
    })
    return res.json()
}
