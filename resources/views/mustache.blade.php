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
    <tr class="js-work-item-input-column">
        <td class="text-center">@{{ workElementText }}</td>
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
         <td class="text-center"><i class="fa fa-trash-o js-delete-work-item text-danger text-20" data-idx="@{{ idx }}"></i></td>
    </tr>
</script>

<script id="js-template-modal-work-item" type="x-tmpl-mustache">
    <div class="modal fade js-modal-detail-work-item-@{{workItemId}}" id="materialsModal_@{{workItemId}}" tabindex="-1" role="dialog" aria-labelledby="materialsModal_@{{workItemId}}Label" aria-hidden="true">
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
                                <td>@{{ description }}</td>
                                <td>@{{ unit }}</td>
                                <td>@{{ quantity }}</td>
                                <td>@{{ rate }}</td>
                                <td>@{{ amount }}</td>
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
    <div class="modal fade js-modal-detail-work-item-@{{workItemId}}" id="manPowersModal_@{{workItemId}}" tabindex="-1" role="dialog" aria-labelledby="manPowersModal_@{{workItemId}}Label" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Man Power</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
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
                            <td>@{{ manPowerTitle }}</td>
                            <td>@{{ unitPivot }}</td>
                            <td>@{{ laborCoefisient }}</td>
                            <td>@{{ rateHourly }}</td>
                            <td>@{{ amountPivot }}</td>
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
    <div class="modal fade js-modal-detail-work-item-@{{workItemId}}" id="toolsEquipmentsModal_@{{workItemId}}" tabindex="-1" role="dialog" aria-labelledby="materialsModal_@{{workItemId}}Label" aria-hidden="true">
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
