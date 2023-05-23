<tr class="js-row-work-item-man-power js-row-column">
    <td class="min-w-160">
        <select class="select2 js-select-man-power js-select-item"
                data-url="/getManPower"
                name="man_power[]"
                data-placeholder="Select Man Power"
                style="max-width: 100% !important;">
            @if(($isEdit))
                <option value="{{$exManPower->id}}">[{{$exManPower->code}}] - {{$exManPower->title}}</option>
            @endif
        </select>
    </td>
    <td class="js-work-item-man-power-unit min-w-50">
        <input type="text" name="unit[]" class="form-control
            js-item-unit
            js-unit-work-item-man-power height-40"
            value="{{ $isEdit ? $exManPower->pivot->labor_unit : ''}}"
            {{!$isEdit ? 'disabled' : ''}}>
    </td>
    <td class="js-work-item-man-power-coef min-w-50">
        <input type="text" name="coef[]" step="0.01"
               {{!$isEdit ? 'disabled' : ''}}
               value="{{$isEdit ? $exManPower->pivot->labor_coefisient : ''}}"
               class="form-control js-coef-work-item-man-power
               js-item-coef
               height-40">
    </td>
    <td class="js-work-item-man-power-rate min-w-100 js-item-rate" data-rate="">
        {{$isEdit ? number_format($exManPower->overall_rate_hourly,2,'.',',') : ''}}
    </td>
    <td class="js-work-item-man-power-amount min-w-100 js-item-amount">
        {{$isEdit ? number_format($exManPower->pivot->amount,2,'.',',') : ''}}
    </td>
    <td>
        <i class="fa fa-trash-o js-delete-item js-delete-work-item-man-power text-danger text-20"></i>
    </td>
</tr>
