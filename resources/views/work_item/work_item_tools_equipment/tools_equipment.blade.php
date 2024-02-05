<tr class="js-row-work-item-tools-equipment js-row-column">
    <td class="min-w-160">
        <select class="select2 js-select-tools-equipment js-select-item js-confirm-form"
                data-minimum-input-length="3"
                data-url="/getToolsEquipment"
                name="tools_equipment[]"
                data-placeholder="Select Tools Equipment"
                style="max-width: 100% !important;">
            @if(($isEdit))
                <option value="{{$exEquipmentTools->id}}">[{{$exEquipmentTools->code}}] - {{$exEquipmentTools->description}}</option>
            @endif
        </select>
    </td>
    <td class="js-work-item-unit min-w-50">
        <input type="text" name="unit[]" class="form-control js-confirm-form
            js-item-unit height-40"
               value="{{ $isEdit ? $exEquipmentTools->pivot->unit : ''}}"
            {{!$isEdit ? 'disabled' : ''}}>
    </td>
    <td class="js-work-item-coef min-w-50">
        <input type="text" name="coef[]" step="0.01"
               {{!$isEdit ? 'disabled' : ''}}
               value="{{$isEdit ? number_format($exEquipmentTools->pivot->quantity,2,'.') : ''}}"
               class="form-control js-coef-work-item-tools-equipment
               js-confirm-form
               js-item-coef
               height-40">
    </td>
    <td class="js-work-item-tools-equipment-rate min-w-100 js-item-rate"
        data-rate="{{$isEdit ? $exEquipmentTools->local_rate : ''}}">
        @if($isEdit) @currencyFormat($exEquipmentTools->local_rate) @endif
    </td>
    <td class="js-work-item-tools-equipment-amount min-w-100 js-item-amount">
        @if($isEdit) @currencyFormat($exEquipmentTools->pivot->amount) @endif
    </td>
    <td>
        <i class="fa fa-trash-o js-delete-item js-delete-work-item-tools-equipment text-danger js-confirm-form text-20"></i>
    </td>
</tr>
