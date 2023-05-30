@inject('setting',App\Models\Setting::class)
@if($isWorkElement)
    <div class="row mb-1">
        <div class="col-md-5">
            <label class="form-label form-label-black m-0" for="validationCustom01">Discipline</label>
            <select class="select2 js-select-category-material"
                    data-allowClear="true"
                    name="parent_id" >
                <option value="{{$discipline?->id}}">{{$discipline?->title}}</option>
            </select>
        </div>
    </div>
@endif
<div class="row mb-1">
    <div class="col-md-12">
        <label class="form-label form-label-black m-0" for="validationCustom01">Description</label>
        <input class="form-control js-validate js-project_project_no height-40" name="title"  type="text"
               value="{{isset($wbs?->title) ? $wbs?->title : old('title')}}">
    </div>
</div>

<div class="row">
    <div class="col-md-12 mt-5 text-end">
        <button class="btn js-btn-save-wbs-setting btn-outline-success">Save</button>
    </div>
</div>
