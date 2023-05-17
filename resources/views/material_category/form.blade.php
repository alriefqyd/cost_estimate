@inject('setting',App\Models\Setting::class)

<div class="row">
    <div class="col-md-4">
        <div class="mb-3">
            <label class="form-label form-label-black m-0" for="exampleFormControlInput1">Code</label>
            <input class="form-control js-tool-equipment-code"
                   value="{{isset($material_category?->code) ? $material_category->code : old('code')}}"
                   name="code" type="text">
        </div>
    </div>
    <div class="col-md-8">
        <div class="mb-3">
            <label class="form-label form-label-black m-0" for="exampleFormControlInput1">Description</label>
            <input class="form-control js-tool-equipment-description"
                   value="{{isset($material_category?->description) ? $material_category->description : old('description')}}"
                   name="description" type="text">
        </div>
    </div>
</div>
<div class="row">
    <div class="col-md-12 mt-2 text-end">
        <button class="btn js-btn-save-tool-equipment btn-outline-success">Save</button>
    </div>
</div>
