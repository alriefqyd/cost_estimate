@inject('setting',App\Models\Setting::class)

<div class="row">
    <div class="col-md-8">
        <div class="mb-3">
            <label class="form-label form-label-black m-0" for="exampleFormControlInput1">Category</label>
            <select class="select2 js-select-category-equipment js-confirm-form"
                    data-allowClear="true"
                    name="category" >
                <option selected disabled="disabled">Select Category</option>
                @foreach($equipment_tools_category as $etc)
                    <option data-code="{{$etc->code}}" data-num-count="{{$etc->equipmentTools->count()}}"
                            {{isset($equipment_tools?->category_id) && $equipment_tools?->category_id == $etc->id ? 'selected' : ''}} value="{{$etc->id}}">{{$etc->description}}</option>
                @endforeach
            </select>
        </div>
    </div>
    <div class="col-md-2">
        <div class="mb-3">
            <label class="form-label form-label-black m-0" for="exampleFormControlInput1">Code</label>
            <input class="form-control js-tool-equipment-code height-40 js-confirm-form"
                   value="{{isset($equipment_tools?->code) ? $equipment_tools->code : old('code')}}"
                   readonly name="code" type="text">
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-8">
        <div class="mb-3">
            <label class="form-label form-label-black m-0" for="exampleFormControlInput1">Description</label>
            <input class="form-control js-tool-equipment-description js-confirm-form"
                   value="{{isset($equipment_tools?->description) ? $equipment_tools->description : old('description')}}"
                   name="description" type="text">
        </div>
    </div>
    <div class="col-md-4">
        <div class="mb-3">
            <label class="form-label form-label-black m-0" for="exampleFormControlInput1">Unit</label>
            <input class="form-control js-tool-equipment-unit js-confirm-form"
                   value="{{isset($equipment_tools?->unit) ? $equipment_tools->unit : old('unit')}}"
                   name="unit" type="text">
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-4">
        <div class="mb-3">
            <label class="form-label form-label-black m-0" for="exampleFormControlInput1">Quantity</label>
            <input class="form-control js-tool-equipment-quantity js-confirm-form"
                   value="{{isset($equipment_tools?->quantity) ? $equipment_tools->quantity : old('quantity')}}"
                   name="quantity" type="number">
        </div>
    </div>
    <div class="col-md-4">
        <div class="mb-3">
            <label class="form-label form-label-black m-0" for="exampleFormControlInput1">Local Rate</label>
            <input class="form-control js-tool-equipment-local-rate js-currency-idr js-confirm-form"
                   value="{{isset($equipment_tools?->local_rate) ? number_format($equipment_tools->local_rate,2,',','.') : old('local_rate')}}"
                   name="local_rate" type="text">
        </div>
    </div>
    <div class="col-md-4">
        <div class="mb-3">
            <label class="form-label form-label-black m-0" for="exampleFormControlInput1">National Rate</label>
            <input class="form-control js-tool-equipment-national-rate js-currency-idr js-confirm-form"
                   value="{{isset($equipment_tools?->national_rate) ?  number_format($equipment_tools->national_rate,2,',','.') : old('national_rate')}}"
                   name="national_rate" type="text">
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12">
        <div class="mb-3">
            <label class="form-label form-label-black m-0" for="validationCustom01">Remark</label>
            <textarea class="form-control js-tool-equipment-remark js-confirm-form" name="remark" style="height: 100px">{{isset($equipment_tools?->remark) ? $equipment_tools->remark : old('remark')}}</textarea>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12 mt-5 text-end">
        <button class="btn js-btn-save-tool-equipment js-save-confirm-form btn-outline-success">Save</button>
    </div>
</div>
