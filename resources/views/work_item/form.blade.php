@inject('setting',App\Models\Setting::class)
<div class="row mb-1">
    <div class="col-md-5">
        <label class="form-label form-label-black m-0" for="validationCustom01">Code</label>
        <input class="form-control js-validate js-project_project_no height-40" name="code"  type="text"
               value="{{isset($work_item?->code) ? $work_item->code : old('code')}}">
    </div>
    <div class="col-md-5">
        <label class="form-label form-label-black m-0" for="validationCustom01">Category</label>
        <select class="select2"
                data-allowClear="true"
                name="work_item_type_id" >
            @foreach($work_item_type as $item)
                <option {{isset($work_item?->work_item_type_id) && $work_item?->work_item_type_id == $item->id ? 'selected' : ''}} value="{{$item->id}}">{{$item->title}}</option>
            @endforeach
        </select>
    </div>
</div>
<div class="row mb-1">
    <div class="col-md-10">
        <label class="form-label form-label-black m-0" for="validationCustom01">Work Description</label>
        <input class="form-control js-validate js-project_project_no height-40" name="description"  type="text"
               value="{{isset($work_item?->description) ? $work_item->description : old('description')}}">
    </div>
</div>
<div class="row mb-1">
    <div class="col-md-5">
        <label class="form-label form-label-black m-0" for="exampleFormControlInput1">Volume</label>
        <input class="form-control js-material-quantity height-40"
               value="{{isset($work_item?->volume) ? $work_item?->volume : old('volume')}}"
               name="volume" type="number">
    </div>
    <div class="col-md-5">
        <label class="form-label form-label-black m-0" for="exampleFormControlInput1">Unit</label>
        <input class="form-control js-material-unit height-40"
               value="{{isset($work_item?->unit) ? $work_item->unit : old('unit')}}"
               name="unit" type="text">
    </div>
</div>
<div class="row">
    <div class="col-md-12 mt-5 text-end">
        <button class="btn js-btn-save-man-power btn-outline-success">Save</button>
    </div>
</div>
