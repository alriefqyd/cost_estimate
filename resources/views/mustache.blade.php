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
    @include('estimate_all_discipline.location_mustache')
</script>

<script id="js-template-table-discipline_work-element" type="x-tmpl-mustache">
    @include('estimate_all_discipline.discipline_work_element')
</script>

<script id="js-template-table-work_item_man_power" type="x-tmpl-mustache">
    @include('work_item.work_item_man_power.man_power',['isEdit' => false])
</script>

<script id="js-template-table-work_item_tools_equipment" type="x-tmpl-mustache">
    @include('work_item.work_item_tools_equipment.tools_equipment',['isEdit' => false])
</script>

<script id="js-template-table-work_item_material" type="x-tmpl-mustache">
    @include('work_item.work_item_material.material',['isEdit' => false])
</script>

<script id="js-template-table-work_item_column" type="x-tmpl-mustache">
    @include('estimate_all_discipline.work_item_row')
</script>

<script id="js-template-modal-detail-estimate" type="x-templ-mustache">
    <div class="modal fade js-modal-detail-estimate" id="workItemDetailModal"
    data-backdrop="static" data-keyboard="false"
    tabindex="-1" role="dialog" aria-labelledby="materialsModal_Label" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Detail</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" style="max-height: 400px; overflow-y: auto;">
                    <div class="col-md-12 mb-5">
                        <label>Man Power</label>
                        <table class="table table-striped">
                            <thead>
                            <th>Description</th>
                            <th>Unit</th>
                            <th>Coef</th>
                            <th>Rate (Rp)</th>
                            <th>Amount (Rp)</th>
                            </thead>
                            <tbody>
                            @{{#manPower}}
                            <tr>
                                <td>
                                    @{{ title }}
                                </td>
                                <td>
                                    @{{ labor_unit }}
                                </td>
                                <td>
                                    @{{ labor_coefisient }}
                                </td>
                                <td>
                                    @{{ overall_rate_hourly }}
                                </td>
                                <td>
                                    @{{ amount }}
                                </td>
                            </tr>
                            @{{/manPower}}
                            </tbody>
                        </table>
                    </div>
                    <hr>
                    <div class="col-md-12 mb-5">
                        <label>Tool and Equipment</label>
                        <table class="table table-striped">
                            <thead>
                            <th>Description</th>
                            <th>Unit</th>
                            <th>Quantity</th>
                            <th>Local Rate (Rp)</th>
                            <th>Amount (Rp)</th>
                            </thead>
                            <tbody>
                            @{{ #equipment }}
                            <tr>
                                <td>
                                    @{{ description }}
                                </td>
                                <td>
                                     @{{ unit }}
                                </td>
                                <td> @{{ quantity }}</td>
                                <td> @{{ local_rate }}</td>
                                <td> @{{ amount }}</td>
                            </tr>
                            @{{ /equipment }}
                            </tbody>
                        </table>
                    </div>
                    <hr>
                    <div class="col-md-12 mb-3">
                        <label>Material</label>
                        <table class="table table-striped">
                            <thead>
                            <th>Description</th>
                            <th>Unit</th>
                            <th>Quantity</th>
                            <th>Unit Price (Rp)</th>
                            <th>Amount (Rp)</th>
                            </thead>
                            <tbody>
                            @{{ #material }}
                            <tr>
                                <td>
                                    @{{ description }}
                                </td>
                                <td>@{{ unit }}</td>
                                <td>@{{ quantity }}</td>
                                <td>@{{ rate }}</td>
                                <td>@{{ amount }}</td>
                            </tr>
                            @{{/material}}
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" type="button" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</script>



