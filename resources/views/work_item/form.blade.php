@inject('setting',App\Models\Setting::class)
<div class="row mb-1">
    <div class="col-md-5">
        <label class="form-label form-label-black m-0" for="validationCustom01">Input Style</label>
        <div class="form-group m-t-15 m-checkbox-inline mb-0 custom-radio-ml">
            <div class="radio radio-primary">
                <input id="radioinline1" class="js-work-item-input-style" {{isset($work_item->id) ? "checked" : ""}} type="radio" name="style" value="standard">
                <label class="mb-0" for="radioinline1">Standard</label>
            </div>
            <div class="radio radio-primary">
                <input id="radioinline2" class="js-work-item-input-style" type="radio" name="style" value="custom">
                <label class="mb-0" for="radioinline2">Custom</label>
            </div>
        </div>
    </div>

</div>

<div class="js-form-work-item {{!$work_item->id ? 'd-none' : ''}}">
    <div class="row mb-1">
        <div class="col-md-5">
            <label class="form-label form-label-black m-0" for="validationCustom01">Category</label>
            <select class="select2 js-select-work-item-type js-confirm-form"
                    data-allowClear="true"
                    name="work_item_type_id" >
                <option disabled="disabled" value="" {{!isset($work_item?->work_item_type_id) ? 'selected' : ''}}>Select Work Item Category</option>
                @foreach($work_item_type as $item)
                    <option data-code="{{$item->getMaxCode()}}" {{isset($work_item?->work_item_type_id) && $work_item?->work_item_type_id == $item->id ? 'selected' : ''}} value="{{$item->id}}">{{$item->title}}</option>
                @endforeach
            </select>
        </div>
        <input type="hidden" name="parent_id" class="js-parent-work-item js-confirm-form"
               value="{{isset($work_item?->parent_id) ? $work_item?->parent_id : old('parent_id')}}">
        <div class="col-md-7">
            <label class="form-label form-label-black m-0" for="validationCustom01">Existing Work Item</label>
            <select class="select2 form-control js-confirm-form js-validate js-project_project_desc">
                <option disabled="disabled" value="" {{!isset($work_item->parent?->id) ? 'selected' : ''}}>Select Existing Work Item</option>
                @if($isEdit)
                    <option value="{{$work_item->parent?->id}}">{{$work_item?->parent?->description}}</option>
                @endif
            </select>
        </div>

    </div>
    <div class="row mb-1">
        <div class="col-md-12">
            <label class="form-label form-label-black m-0" for="validationCustom01">Description</label>
            <textarea class="form-control js-work-description js-confirm-form" name="description">{{isset($work_item?->description) ? $work_item?->description : old('description')}}</textarea>
        </div>
    </div>
    <div class="row mb-1">
        <div class="col-md-4">
            <label class="form-label form-label-black m-0" for="validationCustom01">Code</label>
            <div class="input-group">
                <input class="form-control js-confirm-form js-validate js-work-item-code js-project_project_no height-40" name="code" type="text"
                       disabled
                       value="{{isset($work_item?->code) ? $work_item->code : old('code')}}">
                <input class="form-control js-confirm-form js-validate js-work-item-code js-project_project_no height-40" name="code" type="hidden"
                       value="{{isset($work_item?->code) ? $work_item->code : old('code')}}">
            </div>
        </div>
        <div class="col-md-4">
            <label class="form-label form-label-black m-0" for="exampleFormControlInput1">Volume</label>
            <input class="form-control js-vol-work-item height-40 js-confirm-form"
                   value="{{isset($work_item?->volume) ? $work_item?->volume : old('volume')}}"
                   name="volume" type="number">
        </div>
        <div class="col-md-4">
            <label class="form-label form-label-black m-0" for="exampleFormControlInput1">Unit</label>
            <input class="form-control js-unit-work-item height-40 js-confirm-form"
                   value="{{isset($work_item?->unit) ? $work_item->unit : old('unit')}}"
                   name="unit" type="text">
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 mt-5 text-end">
            <a href="/work-item/">
                <div class="btn js-btn-save-work-item btn-outline-danger">Cancel</div>
            </a>
            <button class="btn js-btn-save-work-item js-save-confirm-form btn-outline-success">Save</button>
        </div>
    </div>
</div>
