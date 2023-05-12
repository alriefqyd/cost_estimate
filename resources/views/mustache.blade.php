@inject('witemCont','App\Http\Controllers\WorkItemController')
<script id="js-template-work-element" type="x-tmpl-mustache">
     <tr class="js-work-element-input-column">
        <td>
            <input class="form-control typeahead form-control tt-input js-input-work-element-idx-@{{ no }}
                           js-work-element-input" type="text"
                           name="work_element[]"
                   placeholder="Work Element" autocomplete="off"
                   spellcheck="false" dir="auto" style="position: relative; vertical-align: top;">
            <input type="hidden" name="element_id[]"/>
        </td>
        <td class="text-center"><i class="fa fa-trash-o js-delete-work-element text-danger text-20" data-idx="@{{ no }}"></i></td>
    </tr>

</script>

<script id="js-template-work-item" type="x-tmpl-mustache">
    <tr class="js-work-item-input-column text-center">
        <td>@{{ workItemText }}</td>
        <td>@{{ vol }} @{{ unit }}</td>
        <td>
             @{{ totalRateManPowers }}
             @{{#totalRateManPowers}}
                <i class="fa fa-exclamation-circle cursor-pointer"
                   data-bs-toggle="modal" data-original-title="test" data-bs-target="#manPowersModal_@{{ workItemId }}"></i>
             @{{/totalRateManPowers}}
        </td>
        <td>
            @{{ totalRateEquipments }}
            @{{#totalRateEquipments}}
                <i class="fa fa-exclamation-circle cursor-pointer"
                   data-bs-toggle="modal" data-original-title="test" data-bs-target="#toolsEquipmentsModal_@{{ workItemId }}"></i>
            @{{/totalRateEquipments}}
        </td>
        <td>
            @{{ totalRateMaterials }}
            @{{#totalRateMaterials}}
                <i class="fa fa-exclamation-circle cursor-pointer"
                   data-bs-toggle="modal" data-original-title="test" data-bs-target="#materialsModal_@{{ workItemId }}"></i>
            @{{/totalRateMaterials}}
        </td>
        <td>
            @{{ labourFactorial }}
        </td>
        <td>
            @{{ equipmentFactorial }}
        </td>
        <td>
            @{{ materialFactorial }}
        </td>
         <td class="text-center"><i class="fa fa-trash-o js-delete-work-item text-danger text-20" data-idx="@{{ idx }}"></i></td>
    </tr>

</script>

<script id="js-template-modal-work-item" type="x-tmpl-mustache">
    <div class="modal fade js-modal-detail-work-item-@{{workItemId}}" id="materialsModal_@{{workItemId}}" role="dialog" aria-labelledby="materialsModal_@{{workItemId}}Label" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Material</h5>
                        <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <table class="table table-striped">
                            <thead>
                            <th>Description</th>
                            <th>Unit</th>
                            <th>Quantity</th>
                            <th>Unit Price (Rp)</th>
                            <th>Amount (Rp)</th>
                            </thead>
                            <tbody>
                            @{{ #materials }}
                            <tr>
                                <td>@{{ tool_equipment_description }}</td>
                                <td>@{{ unit }}</td>
                                <td>@{{ pivot.quantity }}</td>
                                <td>@{{ rate }}</td>
                                <td>@{{ pivot.amount }}</td>
                            </tr>
                            @{{ /materials }}
                            </tbody>
                        </table>
                    </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" type="button" data-bs-dismiss="modal">Close</button>
                    <button class="btn btn-secondary" type="button">Save changes</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade js-modal-detail-work-item-@{{workItemId}}" id="manPowersModal_@{{workItemId}}" role="dialog" aria-labelledby="manPowersModal_@{{workItemId}}Label" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Man Power</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body js-modal-work-item">
                    <table class="table table-striped">
                        <thead>
                        <th>Title</th>
                        <th>Unit</th>
                        <th>Coef</th>
                        <th>Rate (Rp)</th>
                        <th>Amount (Rp)</th>
                        </thead>
                        <tbody>
                        @{{ #manPowers }}
                        <tr>
                            <td>@{{ title }}</td>
                            <td>@{{ pivot.labor_unit }}</td>
                            <td>@{{ pivot.labor_coefisient }}</td>
                            <td>@{{ overall_rate_hourly }}</td>
                            <td>@{{ pivot.amount }}</td>
                        </tr>
                        @{{ /manPowers }}
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" type="button" data-bs-dismiss="modal">Close</button>
                    <button class="btn btn-secondary" type="button">Save changes</button>
                </div>
            </div>
        </div>
    </div>
    </div>
    <div class="modal fade js-modal-detail-work-item-@{{workItemId}}" id="toolsEquipmentsModal_@{{workItemId}}" role="dialog" aria-labelledby="materialsModal_@{{workItemId}}Label" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Equipment Tool</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-striped">
                        <thead>
                        <th>Description</th>
                        <th>Unit</th>
                        <th>Quantity</th>
                        <th>Unit Price (Rp)</th>
                        <th>Amount (Rp)</th>
                        </thead>
                        <tbody>
                        @{{ #equipmentTools }}
                        <tr>
                            <td>@{{ description }}</td>
                            <td>@{{ unit }}</td>
                            <td>@{{ quantity }}</td>
                            <td>@{{ unitPrice }}</td>
                            <td>@{{ amount }}</td>
                        </tr>
                        @{{ /equipmentTools }}
                        </tbody>
                    </table>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" type="button" data-bs-dismiss="modal">Close</button>
                    <button class="btn btn-secondary" type="button">Save changes</button>
                </div>
            </div>
        </div>
    </div>
</script>

<script id="js-template-table-work-item-additional-man-power" type="x-tmpl-mustache">
    <tr>
        <td>
            <select class="form-control js-select-item-additional"
            data-url="/getItemAdditional/manPower">
            </select>
        </td>
        <td>
            <input type="text" name="unit_man_power[]" class="form-control"/>
        </td>
        <td>
            <input type="text" name="coef_man_power[]" class="form-control js-additional-man-power-coef"/>
        </td>
        <td class="js-additional-man-power-rate">

        </td>
        <td class="js-additional-man-power-amount">

        <td>

    </tr>
</script>

<script id="js-template-table-location_equipment" type="x-tmpl-mustache">
    @include('work_item.location_mustache')
</script>

<script id="js-template-table-discipline_work-element" type="x-tmpl-mustache">
    @include('work_item.discipline_work_element')
</script>

