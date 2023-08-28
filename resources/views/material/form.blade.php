@inject('setting',App\Models\Setting::class)
<div class="row mb-1">
    <div class="col-md-5">
        <label class="form-label form-label-black m-0" for="validationCustom01">Category</label>
        <select class="select2 js-select-category-material js-confirm-form"
                data-allowClear="true"
                name="category_id" >
            <option selected disabled="disabled">Select Material Category</option>
            @foreach($material_category as $item)
                <option data-code="{{$item->code}}" data-num-count="{{$item->materials->count()}}" {{isset($material?->category_id) && $material?->category_id == $item->id ? 'selected' : ''}} value="{{$item->id}}">{{$item->description}}</option>
            @endforeach
        </select>
    </div>
    <div class="col-md-5">
        <label class="form-label form-label-black m-0" for="validationCustom01">Code</label>
        <input class="form-control js-validate js-material-code height-40 js-confirm-form" name="code" readonly  type="text"
               value="{{isset($material?->code) ? $material->code : old('code')}}">
    </div>
</div>
<div class="row mb-1">
    <div class="col-md-5">
        <label class="form-label form-label-black m-0" for="validationCustom01">Description</label>
        <input class="form-control js-validate js-project_project_no js-confirm-form height-40" name="tool_equipment_description"  type="text"
               value="{{isset($material?->tool_equipment_description) ? $material->tool_equipment_description : old('tool_equipment_description')}}">
    </div>
    <div class="col-md-5">
        <label class="form-label form-label-black m-0" for="exampleFormControlInput1">Quantity</label>
        <input class="form-control js-material-quantity js-confirm-form height-40"
               value="{{isset($material?->quantity) ? $material->quantity : old('quantity')}}"
               name="quantity" type="number">
    </div>
</div>
<div class="row mb-1">
    <div class="col-md-5">
        <label class="form-label form-label-black m-0" for="exampleFormControlInput1">Unit</label>
        <input class="form-control js-material-unit js-confirm-form height-40"
               value="{{isset($material?->unit) ? $material->unit : old('unit')}}"
               name="unit" type="text">
    </div>
    <div class="col-md-5">
        <label class="form-label form-label-black m-0" for="exampleFormControlInput1">Rate</label>
        <input class="form-control js-material-rate js-confirm-form js-currency-idr height-40"
               value="{{isset($material?->rate) ? number_format($material->rate,2,',','.') : old('rate')}}"
               name="rate" type="text">
    </div>
</div>
<div class="row mb-1">
    <div class="col-md-5">
        <label class="form-label form-label-black m-0" for="exampleFormControlInput1">Stock Code</label>
        <input class="form-control js-material-rate js-confirm-form height-40"
               value="{{isset($material?->stock_code) ? $material?->stock_code : old('stock_code')}}"
               name="stock_code" type="text">
    </div>
    <div class="col-md-5">
        <label class="form-label form-label-black m-0" for="validationCustom01">Ref Material Number</label>
        <input class="form-control height-40 js-confirm-form" name="ref_material_number"  type="text"
               value="{{isset($material?->ref_material_number) ? $material->ref_material_number : old('ref_material_number')}}">
    </div>
</div>
<div class="row mb-1">
    <div class="col-md-12">
        <div class="mb-3">
            <label class="form-label form-label-black m-0" for="validationCustom01">Remark</label>
            <textarea class="form-control js-material-remark js-confirm-form" name="remark" style="height: 100px">{{isset($material?->remark) ? $material->remark : old('remark')}}</textarea>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12 mt-5 text-end">
        <button class="btn js-btn-save-man-power js-save-confirm-form btn-outline-success">Save</button>
    </div>
</div>
