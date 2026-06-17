const csrf = () => document.querySelector('meta[name="csrf-token"]')?.content ?? ''

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
            totalRateManPowers:  row.laborRate  * (parseFloat(row.labourFactorial)    || 1),
            totalRateEquipments: row.toolRate   * (parseFloat(row.equipmentFactorial) || 1),
            totalRateMaterials:  row.materialRate * (parseFloat(row.materialFactorial)  || 1),
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

export async function searchWorkItems(q, signal) {
    const res = await fetch(`/work-items/search?q=${encodeURIComponent(q)}`, { signal })
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
