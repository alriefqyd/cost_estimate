@inject('setting',App\Models\Setting::class)
<div class="row mb-1">
    <div class="col-md-5">
        <label class="form-label form-label-black m-0" for="validationCustom01">Code</label>
        <input class="form-control js-validate js-project_project_no height-40" name="code"  type="text"
               value="{{isset($material?->code) ? $material->code : old('code')}}">
    </div>
    <div class="col-md-5">
        <label class="form-label form-label-black m-0" for="validationCustom01">Category</label>
        <select class="select2"
                data-allowClear="true"
                name="category_id" >
            @foreach($material_category as $item)
                <option {{isset($material?->category_id) && $material?->category_id == $item->id ? 'selected' : ''}} value="{{$item->id}}">{{$item->description}}</option>
            @endforeach
        </select>
    </div>
</div>
<div class="row mb-1">
    <div class="col-md-5">
        <label class="form-label form-label-black m-0" for="validationCustom01">Description</label>
        <input class="form-control js-validate js-project_project_no height-40" name="tool_equipment_description"  type="text"
               value="{{isset($material?->tool_equipment_description) ? $material->tool_equipment_description : old('tool_equipment_description')}}">
    </div>
    <div class="col-md-5">
        <label class="form-label form-label-black m-0" for="exampleFormControlInput1">Quantity</label>
        <input class="form-control js-material-quantity height-40"
               value="{{isset($material?->quantity) ? $material->quantity : old('quantity')}}"
               name="quantity" type="number">
    </div>
</div>
<div class="row mb-1">
    <div class="col-md-5">
        <label class="form-label form-label-black m-0" for="exampleFormControlInput1">Unit</label>
        <input class="form-control js-material-unit height-40"
               value="{{isset($material?->unit) ? $material->unit : old('unit')}}"
               name="unit" type="text">
    </div>
    <div class="col-md-5">
        <label class="form-label form-label-black m-0" for="exampleFormControlInput1">Rate</label>
        <input class="form-control js-material-rate js-currency-idr height-40"
               value="{{isset($material?->rate) ? number_format($material->rate,2,',','.') : old('rate')}}"
               name="rate" type="text">
    </div>
</div>
<div class="row mb-1">
    <div class="col-md-5">
        <label class="form-label form-label-black m-0" for="exampleFormControlInput1">Stock Code</label>
        <input class="form-control js-material-rate height-40"
               value="{{isset($material?->stock_code) ? $material?->stock_code : old('stock_code')}}"
               name="stock_code" type="text">
    </div>
    <div class="col-md-5">
        <label class="form-label form-label-black m-0" for="validationCustom01">Ref Material Number</label>
        <input class="form-control height-40" name="ref_material_number"  type="text"
               value="{{isset($material?->ref_material_number) ? $material->ref_material_number : old('ref_material_number')}}">
    </div>
</div>
<div class="row mb-1">
    <div class="col-md-12">
        <div class="mb-3">
            <label class="form-label form-label-black m-0" for="validationCustom01">Remark</label>
            <textarea class="form-control js-material-remark" name="remark" style="height: 100px">{{isset($material?->remark) ? $material->remark : old('remark')}}</textarea>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12 mt-5 text-end">
        <button class="btn js-btn-save-man-power btn-outline-success">Save</button>
    </div>
</div>
