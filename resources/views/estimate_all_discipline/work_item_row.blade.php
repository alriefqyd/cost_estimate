<tr class="js-row-item-estimate table-row-work-item"
    data-uid="@if(isset($item)){{ trim($item->unique_identifier ?? '') }}@else@{{ uniqueIdentifier }}@endif"
    data-persisted="{{ isset($item) ? 'true' : 'false' }}"
    data-work-scope="{{ isset($item) ? ($item->workScope ?? '') : '' }}">
    <td></td>
    <td class="js-column-identifier">
        <input type="hidden" readonly class="js-item-version"
               value="
               @if(isset($item))
                    {{$item->version}}
                @else
                    @{{ itemVersion }}
                @endif">

        <input type="hidden" readonly class="js-unique-identifier"
           value="@if(isset($item)){{ trim($item->unique_identifier ?? '') }}@else@{{ uniqueIdentifier }}@endif">
    </td>
    <td class="min-w-150">
        @if(isset($item))
            <input type="hidden" name="wbs_level3" class="js-wbs_level3_id" value="{{$item->wbs_level3_id ?? ''}}">
            <input type="hidden" name="work_element" class="js-work_element_id" value="{{$item->work_element_id ?? ''}}">
        @else
            <input type="hidden" name="wbs_level3" data-mustache="true" class="js-wbs_level3_id" value="@{{ wbsLevel3 }}">
            <input type="hidden" name="work_element" data-mustache="true" class="js-work_element_id" value="@{{ workElement }}">
        @endif
    </td>
    <td class="min-w-300">
        <div>
            <span class="js-select2-select-work-item-temp {{isset($item) ? 'd-none' : ''}}">
                <select class="select2 js-select-work-items"
                        data-cost-man-power=
                            @if(isset($item->workItemUnitRateLaborCost))
                                 "{{$item->workItemUnitRateLaborCost}}"
                            @else
                                "@{{ manPowerCostRate }}"
                            @endif
                        data-cost-tools=
                            @if(isset($item->workItemUnitRateToolCost))
                                "{{$item->workItemUnitRateToolCost}}"
                            @else
                                "@{{ equipmentCostRate }}"
                            @endif
                        data-cost-material=
                            @if(isset($item->workItemUnitRateMaterialCost))
                                "{{$item->workItemUnitRateMaterialCost}}"
                            @else
                                "@{{ materialCostRate }}"
                            @endif
                        data-url="/getWorkItems">
                    @if(isset($item))
                        <option selected value="{{$item->workItemId}}">{{$item->workItemDescription}}</option>
                    @else
                        <option selected value="@{{ workItemId }}">@{{ workItemDescription }}</option>
                    @endif
                </select>
            </span>
            <div class="{{isset($item) ? '' : 'd-none'}} js-work-item-text cursor-pointer"
                data-id="@if(isset($item)){{ $item->workItemId }}@else@{{ workItemId }}@endif"
                data-total=
                    @if(isset($item->workItemTotalCost))
                          "{{$item->workItemTotalCost}}"
                    @else
                        "@{{ total }}"
                    @endif
                data-cost-man-power=
                    @if(isset($item->workItemUnitRateLaborCost))
                        "{{$item->workItemUnitRateLaborCost}}"
                    @else
                        "@{{ manPowerCost }}"
                    @endif
                data-cost-tools=
                    @if(isset($item->workItemUnitRateToolCost))
                        "{{$item->workItemUnitRateToolCost}}"
                    @else
                        "@{{ equipmentCost }}"
                    @endif
                data-cost-material=
                    @if(isset($item->workItemUnitRateMaterialCost))
                        "{{$item->workItemUnitRateMaterialCost}}"
                    @else
                        "@{{ materialCost }}"
                    @endif
                ">
                <span class="float-start f-w-500 f-15">
                    @if(isset($item->workItemDescription))
                        {{$item->workItemDescription}}
                    @else
                        @{{ workItemDescription }}
                    @endif
                </span>
            </div>
            <div class="d-inline-block float-end">
                <i class="fa fa-minus-circle cursor-pointer btn-remove-work-item js-delete-work-item"></i>
                <i class="fa fa-plus-circle cursor-pointer btn-add-work-item js-add-work-item-element"
                   @if(isset($wbsId) && isset($workElement))
                        data-id="{{$wbsId}}" data-work-element="{{isset($workElement) ? $workElement : ''}}">
                   @else
                        data-id="@{{ wbsLevel3 }}" data-work-element="@{{ workElement }}">
                   @endif
                </i>
            </div>
        </div>
    </td>
    <td class="min-w-120">
        <div class="input-group">
            <input class="form-control js-input-vol" style="height:40px" type="text" placeholder="Vol"
                   @if(isset($item->estimateVolume))
                       value="{{$item->estimateVolume}}"
                   @else
                       value="@{{workItemVolume}}"
                   @endif
                   {{!isset($item) ? 'disabled="disabled"' : '' }}
                   aria-label="Vol">
            <span class="input-group-text font js-vol-result-ajax f-w-500 f-15" style="font-size: 10px">
                @if(isset($item))
                    {{$item->workItemUnit}}
                @else
                    @{{ unit }}
                @endif
            </span>
        </div>
    </td>
    <td class="min-w-130">
        <span class="float-start js-work-item-man-power-cost f-w-500 f-15">
            @if(isset($item->workItemUnitRateTotalLaborCost))
                {{$item->workItemUnitRateTotalLaborCost}}
            @else
                @{{ manPowerCostStr }}
            @endif
        </span>
        <span class="float-end f-w-550 f-15">
            <i class="fa fa-info-circle cursor-pointer cost-info-icon
                {{isset($item->workItemUnitRateTotalLaborCost) ? floatval($item->workItemUnitRateTotalLaborCost) > 0 ? 'd-block' : 'd-none' : ''}}
                js-open-modal-detail js-work-item-man-power-cost-modal"
               data-type="man_power"
               data-id="
                   @if(isset($item->workItemId))
                        {{$item->workItemId}}
                   @else
                        @{{ workItemId }}
                   @endif
               "></i>
        </span>
    </td>
    <td class="min-w-130">
        <span class="float-start js-work-item-equipment-cost f-w-500 f-15">
            @if(isset($item->workItemUnitRateTotalToolCost))
                {{$item->workItemUnitRateTotalToolCost}}
            @else
                @{{ equipmentCostStr }}
            @endif
        </span>
        <span class="float-end f-w-550 f-15">
            <i class="fa fa-info-circle cursor-pointer cost-info-icon
                @{{ isShowEquipment }}
                {{isset($item) ? floatval($item->workItemUnitRateTotalToolCost) > 0 ? 'd-block' : 'd-none' : ''}}
                js-open-modal-detail js-work-item-equipment-cost-modal"
               data-type="equipment"
               data-id="
               @if(isset($item->workItemId))
                    {{$item->workItemId}}
               @else
                    @{{ workItemId }}
               @endif
           "></i>
        </span>
    </td>
    <td class="min-w-130">
        <span class="float-start js-work-item-material-cost f-w-500 f-15">
             @if(isset($item->workItemUnitRateTotalMaterialCost))
                {{$item->workItemUnitRateTotalMaterialCost}}
            @else
                @{{ materialCostStr }}
            @endif
        </span>
        <span class="float-end f-w-550 f-15">
            <i class="fa fa-info-circle cursor-pointer cost-info-icon
                @{{ isShowMaterial }}
                {{isset($item) ? floatval($item->workItemUnitRateTotalMaterialCost) > 0 ? 'd-block' : 'd-none' : ''}}
                js-open-modal-detail js-work-item-material-cost-modal"
               data-type="material"
               data-id=
                   @if(isset($item->workItemId))
                        "{{$item->workItemId}}"
                   @else
                        "@{{ workItemId }}"
                   @endif
               ></i>
        </span>
    </td>
    <td class="min-w-80">
        <input class="form-control js-input-labor_factorial factorial-input"
               value=
                        @if(isset($item->workItemLaborFactorial))
                            "{{$item->workItemLaborFactorial}}"
                        @else
                            "@{{ laborFactorial }}"
                        @endif
               placeholder="Lbr Fac" type="number">
    </td>
    <td class="min-w-80">
        <input class="form-control js-input-equipment_factorial factorial-input"
               value=
                        @if(isset($item->workItemEquipmentFactorial))
                            "{{$item->workItemEquipmentFactorial}}"
                        @else
                            "@{{ equipmentFactorial }}"
                        @endif
               placeholder="Eqp Fac" type="number">
    </td>
    <td class="min-w-100">
        <input class="form-control js-input-material_factorial factorial-input"
               value=
                        @if(isset($item->workItemMaterialFactorial))
                            "{{$item->workItemMaterialFactorial}}"
                        @else
                            "@{{ materialFactorial }}"
                        @endif
               placeholder="Mat Fac" type="number">
    </td>
    <td class="js-total-work-item-rate">
        <span class="f-w-500 f-15">
            @if(isset($item->workItemTotalCostStr))
                {{$item->workItemTotalCostStr}}
            @else
                @{{ total }}
            @endif
        </span>
    </td>
</tr>

