<tr class="js-row-work-item-tools-equipment js-row-column">
    <td class="min-w-400">
        <select class="select2 js-select-material js-select-item js-confirm-form"
                data-input-minimum-lenght="3"
                data-url="/getMaterial"
                name="material[]"
                data-placeholder="Select Materials"
                style="max-width: 100% !important;">
            @if(($isEdit))
                <option value="{{$exMaterials->id}}">[{{$exMaterials->code}}] - {{$exMaterials->tool_equipment_description}}</option>
            @endif
        </select>
    </td>
    <td class="js-work-item-unit min-w-75">
        <input type="text" name="unit[]" class="form-control
            js-confirm-form
            js-item-unit height-40"
               value="{{ $isEdit ? $exMaterials->pivot->unit : ''}}"
            {{!$isEdit ? 'disabled' : ''}}>
    </td>
    <td class="js-work-item-coef min-w-50">
        <input type="text" name="coef[]" step="0.01"
               {{!$isEdit ? 'disabled' : ''}}
               value="{{$isEdit ? number_format($exMaterials->pivot->quantity,2,'.') : ''}}"
               class="form-control js-coef-work-item-material
               js-item-coef
               js-confirm-form
               height-40">
    </td>
    <td class="js-work-item-material-rate min-w-100 js-item-rate"
        data-rate="{{$isEdit ? $exMaterials->rate : ''}}">
        @if($isEdit) @currencyFormat($exMaterials->rate) @endif
    </td>
    <td class="js-work-item-material-amount min-w-150 js-item-amount">
        @if($isEdit) @currencyFormat($exMaterials->pivot->amount) @endif
    </td>
    <td>
        <i class="fa fa-trash-o js-delete-item js-delete-work-item-material text-danger js-confirm-form text-20"></i>
    </td>
</tr>
