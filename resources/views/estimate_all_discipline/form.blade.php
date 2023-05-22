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
{{--                    @foreach($wbsLevel3 as $key => $wbs)--}}
{{--                        <div class="checkbox checkbox-primary">--}}
{{--                            <input id="checkbox-{{$key}}" data-id="{{$project?->id}}" class="js-checkbox-wbs-level1" value="{{$key}}" type="checkbox">--}}
{{--                            <label class="label-checkbox-work-item" for="checkbox-{{$key}}">{{$key}}</label>--}}
{{--                        </div>--}}
{{--                    @endforeach--}}
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
{{--                    <span class="js-loader">--}}
{{--                        <div class="loader-box">--}}
{{--                            <div class="loader-3"></div>--}}
{{--                        </div>--}}
{{--                    </span>--}}
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
{{--                    <span class="js-loader">--}}
{{--                        <div class="loader-box">--}}
{{--                        <div class="loader-3"></div>--}}
{{--                      </div>--}}
{{--                    </span>--}}
                </div>
            </div>
        </div>
{{--        <div class="float-end mt-4">--}}
{{--            <button class="js-btn-next-work-item btn btn-outline-success" disabled="disabled">Next</button>--}}
{{--        </div>--}}
    </div>
</div>

<div class="card js-card-section-work-item d-none" data-id="{{$project?->id}}">
    <div class="card-body">
        <div class="row">
{{--            <form class="">--}}
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
{{--                <div class="table-responsive js-table-input-work-item mb-5">--}}
{{--                    <table class="table table-striped table-fixed" style="width: 100%" >--}}
{{--                        <thead>--}}
{{--                            <tr>--}}
{{--                                <td style="width: 60%">Work Item</td>--}}
{{--                                <td style="width: 15%">Volume</td>--}}
{{--                                <td style="width: 25%">Unit Price</td>--}}
{{--                                <td style="width: 10%">Labour Factorial</td>--}}
{{--                                <td style="width: 11%">Equipment Factorial</td>--}}
{{--                                <td style="width: 10%">Material Factorial</td>--}}
{{--                            </tr>--}}
{{--                        </thead>--}}
{{--                        <tbody>--}}
{{--                            <tr>--}}
{{--                                <td>--}}
{{--                                    <select class="select2 js-select-work-items"--}}
{{--                                            data-url="/getWorkItems"--}}
{{--                                            style="max-width: 100% !important;">--}}
{{--                                    </select>--}}
{{--                                </td>--}}
{{--                                <td class="js-column-vol">--}}
{{--                                    <div class="input-group">--}}
{{--                                        <input class="form-control js-input-vol" type="text" placeholder="Vol"--}}
{{--                                               aria-label="Vol">--}}
{{--                                        <span class="input-group-text js-vol-result-ajax">Kg</span>--}}
{{--                                    </div>--}}
{{--                                </td>--}}
{{--                                <td>--}}

{{--                                </td>--}}

{{--                            </tr>--}}
{{--                            <tr>--}}
{{--                                <td>--}}

{{--                                </td>--}}
{{--                                <td>--}}

{{--                                </td>--}}
{{--                                <td>--}}

{{--                                </td>--}}
{{--                            </tr>--}}
{{--                        </tbody>--}}
{{--                    </table>--}}
{{--                    <div class="float-end text-12 cursor-pointer js-add-work-item">--}}
{{--                        <i class="fa fa-plus-circle"></i> Add new work item--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </form>--}}
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
{{--                            <td colspan="9" class="text-center js-row-empty-label">--}}
{{--                                Empty Data--}}
{{--                            </td>--}}
{{--                        @foreach($workItem as $item)--}}
{{--                            @php($arrTotManPower = $item?->workItems?->manPowers?->map(function ($el)--}}
{{--                                        use ($workItemController){--}}
{{--                                        $rate = $el->overall_rate_hourly;--}}
{{--                                        $coef = $el->pivot->labor_coefisient;--}}
{{--                                        $tot = (float) $rate * (float) $workItemController->toDecimalRound($coef);--}}
{{--                                        return $tot;--}}
{{--                                    })->all())--}}
{{--                            @php($arrTotTool = $item?->workItems?->equipmentTools->map(function ($el){--}}
{{--                                      $rate = $el->local_rate;--}}
{{--                                      $qty = $el->pivot->quantity;--}}
{{--                                      $tot = $rate * (float) $qty;--}}
{{--                                      return $tot;--}}
{{--                                  })->all())--}}
{{--                            @php($arrTotMaterials = $item?->workItems?->materials->map(function ($el){--}}
{{--                                     $rate = $el->rate;--}}
{{--                                     $qty = $el->pivot->quantity;--}}
{{--                                     $tot = $rate * (float) $qty;--}}
{{--                                     return $tot;--}}
{{--                                 })->all())--}}
{{--                            @php($totalRateManPower = $workItemController->toCurrency(array_sum($arrTotManPower)))--}}
{{--                            @php($totalRateEquipments = $workItemController->toCurrency(array_sum($arrTotTool)))--}}
{{--                            @php($totalRateMaterials = $workItemController->toCurrency(array_sum($arrTotMaterials)))--}}

{{--                            <input type="hidden" class="js-existing-work-items" name="element_id[]"--}}
{{--                                   data-volume="{{$item->volume}}"--}}
{{--                                   data-work-element="{{$item->work_element_id}}"--}}
{{--                                   data-work-item="{{$item->work_item_id}}"--}}
{{--                                   value="{{$item->id}}">--}}
{{--                            <tr class="js-work-item-input-column" data-id-item="{{$item->id}}">--}}
{{--                                <td class="text-center">{{$item->workElements?->name}}</td>--}}
{{--                                <td>{{$item->workItems->description  }}</td>--}}
{{--                                <td>{{$item->volume }} {{$item->workItems->unit}}</td>--}}
{{--                                <td>--}}
{{--                                    {{$totalRateManPower}}--}}
{{--                                    @if($arrTotManPower)--}}
{{--                                        <i class="fa fa-exclamation-circle cursor-pointer"--}}
{{--                                           data-bs-toggle="modal" data-original-title="test" data-bs-target="#manPowersModal_{{$item->work_item_id}}"></i>--}}
{{--                                    @endif--}}
{{--                                </td>--}}
{{--                                <td>--}}
{{--                                    {{$totalRateEquipments}}--}}
{{--                                    @if($arrTotTool)--}}
{{--                                        <i class="fa fa-exclamation-circle cursor-pointer"--}}
{{--                                           data-bs-toggle="modal" data-original-title="test" data-bs-target="#toolsEquipmentsModal_{{$item->work_item_id}}"></i>--}}
{{--                                    @endif--}}
{{--                                </td>--}}
{{--                                <td>--}}
{{--                                    {{$totalRateMaterials}}--}}
{{--                                    @if($arrTotMaterials)--}}
{{--                                        <i class="fa fa-exclamation-circle cursor-pointer"--}}
{{--                                           data-bs-toggle="modal" data-original-title="test" data-bs-target="#materialsModal_{{$item->work_item_id}}"></i>--}}
{{--                                    @endif--}}
{{--                                </td>--}}
{{--                                <td class="text-center"><i--}}
{{--                                        class="fa fa-trash-o js-delete-work-item text-danger text-20"--}}
{{--                                        data-idx="{{ $loop->index }}"></i></td>--}}
{{--                            </tr>--}}
{{--                        @endforeach--}}
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
{{--@if(request()->discipline)--}}
{{--    <div class="card js-works-detail-form">--}}
{{--        <div class="card-body">--}}
{{--            <ul class="nav nav-tabs" id="myTab" role="tablist">--}}
{{--                <li class="nav-item"><a class="nav-link active" id="home-tab" data-bs-toggle="tab" href="#home"--}}
{{--                                        role="tab" aria-controls="home" aria-selected="true">Work Element</a></li>--}}
{{--                @if(!$isEmptyWorkElement)--}}
{{--                    <li class="nav-item"><a class="nav-link" id="profile-tabs" data-bs-toggle="tab" href="#profile"--}}
{{--                                            role="tab" aria-controls="profile" aria-selected="false">Work Item</a></li>--}}
{{--                @endif--}}
{{--            </ul>--}}
{{--            <div class="tab-content" id="myTabContent">--}}
{{--                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">--}}
{{--                    <form method="post" action="workItem">--}}
{{--                        <div class="col-md-12 mt-5">--}}
{{--                            <div class="table-responsive mb-5">--}}
{{--                                <table class="table js-work-element-table table-striped">--}}
{{--                                    <thead>--}}
{{--                                    <tr>--}}
{{--                                        <th scope="col" class="col-11">Title</th>--}}
{{--                                        <th scope="col" class="col-2 text-center">Action</th>--}}
{{--                                    </tr>--}}
{{--                                    </thead>--}}
{{--                                    <tbody>--}}
{{--                                    @if($isEmptyWorkElement)--}}
{{--                                        <tr class="js-work-element-input-column">--}}
{{--                                            <td>--}}
{{--                                                <input class="form-control typeahead form-control tt-input js-input-work-element-idx-0--}}
{{--                                                       js-work-element-input" type="text"--}}
{{--                                                       name="work_element[]"--}}
{{--                                                       placeholder="Work Element" autocomplete="off"--}}
{{--                                                       spellcheck="false" dir="auto"--}}
{{--                                                       style="position: relative; vertical-align: top;">--}}
{{--                                                <input type="hidden" name="element_id[]"/>--}}
{{--                                            </td>--}}
{{--                                            <td class="text-center"><i--}}
{{--                                                    class="fa fa-trash-o js-delete-work-element text-danger text-20"--}}
{{--                                                    data-idx="0"></i></td>--}}
{{--                                        </tr>--}}
{{--                                    </tbody>--}}
{{--                                </table>--}}
{{--                                <div class="float-end text-12 cursor-pointer js-add-work-element"><i--}}
{{--                                        class="fa fa-plus-circle"></i> Add new work element--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                            <input type="hidden" name="discipline" value="{{request()->discipline}}"/>--}}
{{--                            <button type="submit" class="btn btn-primary float-end">Save Work Element</button>--}}
{{--                        </div>--}}
{{--                    </form>--}}
{{--                </div>--}}
{{--                <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">--}}
{{--                    <div class="row">--}}
{{--                        <div class="col-md-12 mt-5">--}}
{{--                            <div class="table-responsive js-table-input-work-item mb-2">--}}
{{--                                <table class="table table-fixed" style="width: 100%">--}}
{{--                                    <thead>--}}
{{--                                    <tr>--}}
{{--                                        <th style="width: 70% !important;">Title</th>--}}
{{--                                        <th style="width: 10%">Vol</th>--}}
{{--                                        <th class="text-center" style="width: 20%">Work Element</th>--}}
{{--                                    </tr>--}}
{{--                                    </thead>--}}
{{--                                    <tbody>--}}
{{--                                    <tr>--}}
{{--                                        <td>--}}
{{--                                            <select class="select2 js-select-work-items"--}}
{{--                                                    data-url="/getWorkItems"--}}
{{--                                                    style="max-width: 100% !important;">--}}
{{--                                            </select>--}}

{{--                                        </td>--}}
{{--                                        <td class="js-column-vol">--}}
{{--                                            <div class="input-group">--}}
{{--                                                <input class="form-control js-input-vol" type="text" placeholder="Vol"--}}
{{--                                                       aria-label="Vol">--}}
{{--                                                <span class="input-group-text js-vol-result-ajax">Kg</span>--}}
{{--                                            </div>--}}
{{--                                        </td>--}}
{{--                                        <td>--}}
{{--                                            <select class="select2 js-select-work-element-item col-sm-12"--}}
{{--                                                    data-discipline="{{request()->discipline}}"--}}
{{--                                                    data-id="{{$project->id}}">--}}
{{--                                                @foreach($workElement as $element)--}}
{{--                                                    <option value="{{$element->id}}">{{$element->name}}</option>--}}
{{--                                                @endforeach--}}
{{--                                            </select>--}}
{{--                                        </td>--}}
{{--                                    </tr>--}}
{{--                                    </tbody>--}}
{{--                                </table>--}}
{{--                                <div class="float-end text-12 cursor-pointer js-add-work-item"><i--}}
{{--                                        class="fa fa-plus-circle"></i> Add new work item--}}
{{--                                </div>--}}
{{--                            </div>--}}
{{--                        </div>--}}



                        <div class="mb-5 float-end">
                            <button class="btn btn-primary js-save-estimate-discipline d-none" >Save As Draft</button>
                            <button class="btn btn-primary js-save-estimate-discipline d-none" >Publish</button>
                        </div>
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
    @include('estimate_all_discipline.modal_detail')
{{--@endif--}}




