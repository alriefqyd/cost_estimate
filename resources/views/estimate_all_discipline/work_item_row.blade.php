<tr>
    <td></td>
    <td></td>
    <td>
        @if(isset($item))
            <input type="hidden" name="wbs_level3" class="js-wbs_level3_id" value="{{$item->wbs_level3_id ?? ''}}">
            <input type="hidden" name="work_element" class="js-work_element_id" value="{{$item->work_element_id ?? ''}}">
        @else
            <input type="hidden" name="wbs_level3" class="js-wbs_level3_id" value="@{{ wbsLevel3 }}">
            <input type="hidden" name="work_element" class="js-work_element_id" value="@{{ workElement }}">
        @endif
    </td>
    <td class="min-w-300">
        <div>
            <span class="{{isset($item) ? 'd-none' : ''}}">
                <select class="select2 js-select-work-items"
                        data-cost-man-power="{{$item->workItemUnitRateLaborCost ?? ''}}"
                        data-cost-tools="{{$item->workItemUnitRateToolCost ?? ''}}"
                        data-cost-material="{{$item->workItemUnitRateMaterialCost ?? ''}}"
                        data-url="/getWorkItems">
                    @if(isset($item))
                        <option selected value="{{$item->workItemId}}">{{$item->workItemDescription}}</option>
                    @endif
                </select>
            </span>
            <div class="{{isset($item) ? '' : 'd-none'}} js-work-item-text cursor-pointer"
                data-total="{{$item->workItemTotalCost ?? ''}}"
                data-cost-man-power="{{$item->workItemUnitRateLaborCost ?? ''}}"
                data-cost-tools="{{$item->workItemUnitRateToolCost ?? ''}}"
                data-cost-material="{{$item->workItemUnitRateMaterialCost ?? ''}}">
                <span class="float-start">
                    {{$item->workItemDescription ?? ''}}
                </span>
                <div class="d-inline-block float-end">
                    <i class="fa fa-minus-circle cursor-pointer font-danger js-delete-work-item"></i>
                </div>
            </div>
        </div>
    </td>
    <td class="min-w-150">
        <div class="input-group">
            <input class="form-control js-input-vol" style="height:40px" type="text" placeholder="Vol"
                   value="{{$item->estimateVolume ?? ''}}"
                   {{!isset($item) ? 'disabled="disabled"' : '' }}
                   aria-label="Vol">
            <span class="input-group-text fontjs-vol-result-ajax" style="font-size: 10px">{{isset($item) ? $item->workItemUnit : 'Kg'}}</span>
        </div>
    </td>
    <td class="min-w-140">
        <span class="float-start js-work-item-man-power-cost">
            {{isset($item->workItemUnitRateTotalLaborCost) ? $item->workItemUnitRateTotalLaborCost : ''}}
        </span>
        <span class="float-end">
            <span class="float-end">
            <i class="fa fa-exclamation-circle cursor-pointer
            {{isset($item->workItemUnitRateTotalLaborCost) && $item->workItemUnitRateTotalLaborCost > 0 ? 'd-block' : 'd-none'}}
            js-open-modal-detail js-work-item-man-power-cost-modal"
               data-id="{{$item->workItemId ?? ''}}"></i>
        </span>
    </td>
    <td class="min-w-140">
        <span class="float-start js-work-item-equipment-cost">
            {{isset($item->workItemUnitRateTotalToolCost) ? $item->workItemUnitRateTotalToolCost: ''}}
        </span>
        <span class="float-end">
            <span class="float-end">
            <i class="fa fa-exclamation-circle cursor-pointer
            {{isset($item) && $item->workItemTotalToolCost > 0 ? 'd-block' : 'd-none'}}
            js-open-modal-detail js-work-item-equipment-cost-modal"
               data-id="{{$item->workItemId ?? ''}}"></i>
        </span>
        </span>
    </td>
    <td class="min-w-140">
        <span class="float-start js-work-item-material-cost">
            {{isset($item->workItemUnitRateTotalMaterialCost) ? $item->workItemUnitRateTotalMaterialCost : ''}}
        </span>
        <span class="float-end">
            <i class="fa fa-exclamation-circle cursor-pointer
            {{isset($item) && $item->workItemTotalMaterialCost > 0 ? 'd-block' : 'd-none'}}
            js-open-modal-detail js-work-item-material-cost-modal"
               data-id="{{$item->workItemId ?? ''}}"></i>
        </span>
    </td>
    <td>
        <input class="form-control js-input-labor_factorial" value="{{$item->workItemLaborFactorial ?? ''}}" placeholder="Labor Factorial" type="number">
    </td>
    <td>
        <input class="form-control js-input-equipment_factorial" value="{{$item->workItemEquipmentFactorial ?? ''}}" placeholder="Equipment Factorial" type="number">
    </td>
    <td>
        <input class="form-control js-input-material_factorial" value="{{$item->workItemMaterialFactorial ?? ''}}" placeholder="Material Factorial" type="number" >
    </td>
    <td class="min-w-150 js-total-work-item-rate"><span>{{$item->workItemTotalCostStr ?? ''}}</span></td>
</tr>

<div class="modal" id="modal-loading" data-backdrop="static">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-body text-center">
                <div class="loading-spinner mb-2"></div>
                <div>Loading....</div>
            </div>
        </div>
    </div>
</div>

