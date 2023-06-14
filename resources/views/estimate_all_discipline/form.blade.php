<div class="card">
    <div class="card-body">
        <div class="row js-row-work-breakdown-work-item">
            <div class="col-md-4">
                <h6>Level 1 Location/Equipment</h6>
                <div class="mb-3 col-md-10 mb-2">
                    <div style="height: 3px; background-color: #24695c "></div>
                </div>
                <div class="form-group">
                    <select class="select2 form-control js-select-level1" data-id="{{$project?->id}}" data-placeholder="Select WBS Level 1">
                        <option></option>
                        @foreach($wbsLevel3 as $key => $wbs)
                            <option value="{{$wbs->first()->identifier}}">{{$key}}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="col-md-4 js-level2-checkbox ">
                <h6>Level 2 Discipline</h6>
                <div class="mb-3 col-md-10 mb-2">
                    <div style="height: 3px; background-color: #24695c "></div>
                </div>
                <div class="form-group">
                    <select class="select2 form-control js-select-level2"
                            data-placeholder="Select WBS Level 2"
                            data-id="{{$project?->id}}">
                        <option></option>
                    </select>
                </div>
            </div>
            <div class="col-md-4 js-level3-checkbox">
                <h6>Level 3 Work Element</h6>
                <div class="mb-3 col-md-10 mb-2">
                    <div style="height: 3px; background-color: #24695c "></div>
                </div>
                <div class="form-group">
                    <select class="select2 form-control js-select-level3" data-id="{{$project->id}}" data-placeholder="Select WBS Level 3">
                        <option></option>
                    </select>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card js-card-section-work-item d-none" data-id="{{$project?->id}}">
    <div class="card-body">
        <div class="row">
            <div class="col-md-10">
                <label>Work Item</label>
                <select class="select2 js-select-work-items"
                        data-url="/getWorkItems"
                        style="max-width: 100% !important;">
                </select>
            </div>
            <div class="col-md-2 js-column-vol">
                <label>Volume</label>
                <div class="input-group">
                    <input class="form-control js-input-vol" style="height:40px" type="text" placeholder="Vol"
                           aria-label="Vol">
                    <span class="input-group-text js-vol-result-ajax">Kg</span>
                </div>
            </div>
            <div class="col-md-4 mt-3">
                <label>Labor Factorial</label>
                <input class="form-control js-input-labor_factorial" type="number">
            </div>
            <div class="col-md-4 mt-3">
                <label>Equipment Factorial</label>
                <input class="form-control js-input-equipment_factorial" type="number">
            </div>
            <div class="col-md-4 mt-3">
                <label>Material Factorial</label>
                <input class="form-control js-input-material_factorial" type="number" >
            </div>
            <div class="col-md-4 mt-3">
                <label>Labours Unit Price (Rp)</label>
                <input type="text" class="form-control js-labour-unit-price-preview" disabled="disabled">
            </div>
            <div class="col-md-4 mt-3">
                <label>Equipment Tools Unit Price (Rp)</label>
                <input type="text" class="form-control js-equipment-unit-price-preview" disabled="disabled">
            </div>
            <div class="col-md-4 mt-3">
                <label>Materials Unit Price (Rp)</label>
                <input type="text" class="form-control js-material-unit-price-preview" disabled="disabled">
            </div>
            <div class="col-md-12 mt-3">
                <div class="float-end text-12 cursor-pointer js-add-work-item">
                    <i class="fa fa-plus-circle"></i> Add new work item
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 m-t-40">
                <div class="table-responsive mb-2" style="table-layout: auto">
                    <table class="table table-striped js-work-item-table d-none">
                        <thead>
                        <tr>
                            <th class="text-center min-w-300">Work Item</th>
                            <th class="text-center min-w-100">Vol</th>
                            <th class="text-center min-w-200" style="">
                                Labour
                            </th>
                            <th class="text-center min-w-200" style="">
                                Tool And Equipment
                            </th>
                            <th class="text-center min-w-200" style="">
                                Material
                            </th>
                            <th class="text-center min-w-150" style="">
                                Labor Factorial
                            </th>
                            <th class="text-center min-w-160" style="">
                                Equipment Factorial
                            </th>
                            <th class="text-center min-w-150" style="">
                                Material Factorial
                            </th>
                            <th class="text-center" style="width: 15%">Action</th>
                        </tr>
                        </thead>
                        <tbody class="js-body-work-item-table">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="mb-5 float-end">
    <button class="btn btn-primary js-save-estimate-discipline d-none" >Save As Draft</button>
    <button class="btn btn-primary js-save-estimate-discipline d-none" >Publish</button>
</div>
@include('estimate_all_discipline.modal_detail')




