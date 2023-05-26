@inject('setting',App\Models\Setting::class)
<div class="row mb-1">
    <div class="col-md-5">
        <label class="form-label form-label-black m-0" for="validationCustom01">Category</label>
        <select class="select2 js-select-work-item-type"
                data-allowClear="true"
                name="work_item_type_id" >
            <option disabled="disabled" value="" {{!isset($work_item?->work_item_type_id) ? 'selected' : ''}}>Select Work Item Category</option>
            @foreach($work_item_type as $item)
                <option data-code="{{$item->getMaxCode()}}" {{isset($work_item?->work_item_type_id) && $work_item?->work_item_type_id == $item->id ? 'selected' : ''}} value="{{$item->id}}">{{$item->title}}</option>
            @endforeach
        </select>
    </div>
    <input type="hidden" name="parent_id" class="js-parent-work-item"
           value="{{isset($work_item?->parent_id) ? $work_item?->parent_id : old('parent_id')}}">
    <div class="col-md-7">
        <label class="form-label form-label-black m-0" for="validationCustom01">Existing Work Item</label>
        <select class="select2 form-control js-validate js-project_project_desc">
            @if($isEdit)
                <option value="{{$work_item->parent->id}}">{{$work_item?->parent?->description}}</option>
            @endif
        </select>
    </div>

</div>
<div class="row mb-1">
    <div class="col-md-12">
        <label class="form-label form-label-black m-0" for="validationCustom01">Description</label>
        <textarea class="form-control js-work-description" name="description">{{isset($work_item?->description) ? $work_item?->description : old('description')}}</textarea>
    </div>
</div>
<div class="row mb-1">
    <div class="col-md-4">
        <label class="form-label form-label-black m-0" for="validationCustom01">Code</label>
        <div class="input-group">
{{--            @if(!$isEdit)--}}
{{--                <input type="text" class="input-group-text col-4 js-prefix-code" name="prefix_code" value="{{$work_item_type[0]->code}}.">--}}
{{--            @endif--}}
            <input class="form-control js-validate js-work-item-code js-project_project_no height-40" name="code" type="text"
                   disabled
                   value="{{isset($work_item?->code) ? $work_item->code : old('code')}}">
            <input class="form-control js-validate js-work-item-code js-project_project_no height-40" name="code" type="hidden"
                   value="{{isset($work_item?->code) ? $work_item->code : old('code')}}">
        </div>
    </div>
    <div class="col-md-4">
        <label class="form-label form-label-black m-0" for="exampleFormControlInput1">Volume</label>
        <input class="form-control js-material-quantity height-40"
               value="{{isset($work_item?->volume) ? $work_item?->volume : old('volume')}}"
               name="volume" type="number">
    </div>
    <div class="col-md-4">
        <label class="form-label form-label-black m-0" for="exampleFormControlInput1">Unit</label>
        <input class="form-control js-material-unit height-40"
               value="{{isset($work_item?->unit) ? $work_item->unit : old('unit')}}"
               name="unit" type="text">
    </div>
</div>
<div class="row">
    <div class="col-md-12 mt-5 text-end">
        <button class="btn js-btn-save-work-item btn-outline-success">Save</button>
    </div>
</div>
