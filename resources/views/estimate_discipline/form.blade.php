@inject('workItemController','App\Http\Controllers\WorkItemController')
<div class="card js-select-discipline-card" data-id="{{$project->id}}">
    <div class="card-body">
        <div class="mb-2">
            <label class="col-form-label">Discipline</label>
            <select name="work_scope" class="select2 js-select-discipline col-sm-12">
                <option disabled {{!request()->discipline ? 'selected' : ''}} value> Select Discipline</option>
                <option {{request()->discipline == 'general' ? 'selected' : ''}} value="general">General</option>
                <option {{request()->discipline == 'electrical' ? 'selected' : ''}} value="electrical">Electrical
                </option>
                <option {{request()->discipline == 'instrument' ? 'selected' : ''}} value="instrument">Instrument
                </option>
                <option {{request()->discipline == 'mechanical' ? 'selected' : ''}} value="mechanical">Mechanical
                </option>
                <option {{request()->discipline == 'civil' ? 'selected' : ''}} value="civil">Civil</option>
            </select>
        </div>
    </div>
</div>
@if(request()->discipline)
    <div class="card js-works-detail-form">
        <div class="card-body">
            <ul class="nav nav-tabs" id="myTab" role="tablist">
                <li class="nav-item"><a class="nav-link active" id="home-tab" data-bs-toggle="tab" href="#home"
                                        role="tab" aria-controls="home" aria-selected="true">Work Element</a></li>
                @if(!$isEmptyWorkElement)
                    <li class="nav-item"><a class="nav-link" id="profile-tabs" data-bs-toggle="tab" href="#profile"
                                            role="tab" aria-controls="profile" aria-selected="false">Work Item</a></li>
                @endif
            </ul>
            <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade show active" id="home" role="tabpanel" aria-labelledby="home-tab">
                    <form method="post" action="workItem">
                        <div class="col-md-12 mt-5">
                            <div class="table-responsive mb-5">
                                <table class="table js-work-element-table table-striped">
                                    <thead>
                                    <tr>
                                        <th scope="col" class="col-11">Title</th>
                                        <th scope="col" class="col-2 text-center">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @if($isEmptyWorkElement)
                                        <tr class="js-work-element-input-column">
                                            <td>
                                                <input class="form-control typeahead form-control tt-input js-input-work-element-idx-0
                                                       js-work-element-input" type="text"
                                                       name="work_element[]"
                                                       placeholder="Work Element" autocomplete="off"
                                                       spellcheck="false" dir="auto"
                                                       style="position: relative; vertical-align: top;">
                                                <input type="hidden" name="element_id[]"/>
                                            </td>
                                            <td class="text-center"><i
                                                    class="fa fa-trash-o js-delete-work-element text-danger text-20"
                                                    data-idx="0"></i></td>
                                        </tr>
                                    @else
                                        @foreach($workElement as $item)
                                            <tr class="js-work-element-input-column">
                                                <td>
                                                    <input class="form-control typeahead form-control tt-input js-input-work-element-idx-0
                                                       js-work-element-input" type="text"
                                                           name="work_element[]"
                                                           value="{{$item->name}}"
                                                           placeholder="Work Element" autocomplete="off"
                                                           spellcheck="false" dir="auto"
                                                           style="position: relative; vertical-align: top;">
                                                    <input type="hidden" name="element_id[]" value="{{$item->id}}"/>
                                                </td>
                                                <td class="text-center"><i
                                                        class="fa fa-trash-o js-delete-work-element text-danger text-20"
                                                        data-idx="0"></i></td>
                                            </tr>
                                        @endforeach
                                    @endif
                                    </tbody>
                                </table>
                                <div class="float-end text-12 cursor-pointer js-add-work-element"><i
                                        class="fa fa-plus-circle"></i> Add new work element
                                </div>
                            </div>
                            <input type="hidden" name="discipline" value="{{request()->discipline}}"/>
                            <button type="submit" class="btn btn-primary float-end">Save Work Element</button>
                        </div>
                    </form>
                </div>
                <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                    <div class="row">
                        <div class="col-md-12 mt-5">
                            <div class="table-responsive js-table-input-work-item mb-2">
                                <table class="table table-fixed" style="width: 100%">
                                    <thead>
                                    <tr>
                                        <th style="width: 70% !important;">Title</th>
                                        <th style="width: 10%">Vol</th>
                                        <th class="text-center" style="width: 20%">Work Element</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    <tr>
                                        <td>
                                            <select class="select2 js-select-work-items"
                                                    data-url="/getWorkItems"
                                                    style="max-width: 100% !important;">
                                            </select>

                                        </td>
                                        <td class="js-column-vol">
                                            <div class="input-group">
                                                <input class="form-control js-input-vol" type="text" placeholder="Vol"
                                                       aria-label="Vol">
                                                <span class="input-group-text js-vol-result-ajax">Kg</span>
                                            </div>
                                        </td>
                                        <td>
                                            <select class="select2 js-select-work-element col-sm-12"
                                                    data-discipline="{{request()->discipline}}"
                                                    data-id="{{$project->id}}">
                                            </select>
                                        </td>
                                    </tr>
                                    </tbody>
                                </table>
                                <div class="float-end text-12 cursor-pointer js-add-work-item"><i
                                        class="fa fa-plus-circle"></i> Add new work item
                                </div>
                            </div>
                        </div>
                        <div class="col-md-12 m-t-25">
                            <div class="table-responsive mb-2" style="table-layout: auto">
                                <table class="table table-striped js-work-item-table">
                                    <thead>
                                    <tr>
                                        <th class="text-center min-w-200" style="">Work Element</th>
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
                                        <th class="text-center" style="width: 15%">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody class="js-body-work-item-table">
                                    @foreach($workItem as $item)

                                        @php($arrTotManPower = $item?->workItems?->manPowers?->map(function ($el)
                                                    use ($workItemController){
                                                    $rate = $el->overall_rate_hourly;
                                                    $coef = $el->pivot->labor_coefisient;
                                                    $tot = $rate * $workItemController->toDecimalRound($coef);
                                                    return $tot;
                                                })->all())
                                        @php($arrTotTool = $item?->workItems?->equipmentTools->map(function ($el){
                                                  $rate = $el->local_rate;
                                                  $qty = $el->pivot->quantity;
                                                  $tot = $rate * (float) $qty;
                                                  return $tot;
                                              })->all())
                                        @php($arrTotMaterials = $item?->workItems?->materials->map(function ($el){
                                                 $rate = $el->rate;
                                                 $qty = $el->pivot->quantity;
                                                 $tot = $rate * (float) $qty;
                                                 return $tot;
                                             })->all())
                                        @php($totalRateManPower = $workItemController->toCurrency(array_sum($arrTotManPower)))
                                        @php($totalRateEquipments = $workItemController->toCurrency(array_sum($arrTotTool)))
                                        @php($totalRateMaterials = $workItemController->toCurrency(array_sum($arrTotMaterials)))

                                        <input type="hidden" class="js-existing-work-items" name="element_id[]"
                                               data-volume="{{$item->volume}}"
                                               data-work-element="{{$item->work_element_id}}"
                                               data-work-item="{{$item->work_item_id}}"
                                               value="{{$item->id}}">
                                        <tr class="js-work-item-input-column" data-id-item="{{$item->id}}">
                                            <td class="text-center">{{$item->workElements?->name}}</td>
                                            <td>{{$item->workItems->description  }}</td>
                                            <td>{{$item->volume }} {{$item->workItems->unit}}</td>
                                            <td>
                                                {{$totalRateManPower}}
                                                @if($arrTotManPower)
                                                    <i class="fa fa-exclamation-circle cursor-pointer"
                                                       data-bs-toggle="modal" data-original-title="test" data-bs-target="#manPowersModal_{{$item->work_item_id}}"></i>
                                                @endif
                                            </td>
                                            <td>
                                                {{$totalRateEquipments}}
                                                @if($arrTotTool)
                                                    <i class="fa fa-exclamation-circle cursor-pointer"
                                                       data-bs-toggle="modal" data-original-title="test" data-bs-target="#toolsEquipmentsModal_{{$item->work_item_id}}"></i>
                                                @endif
                                            </td>
                                            <td>
                                                {{$totalRateMaterials}}
                                                @if($arrTotMaterials)
                                                    <i class="fa fa-exclamation-circle cursor-pointer"
                                                       data-bs-toggle="modal" data-original-title="test" data-bs-target="#materialsModal_{{$item->work_item_id}}"></i>
                                                @endif
                                            </td>
                                            <td class="text-center"><i
                                                    class="fa fa-trash-o js-delete-work-item text-danger text-20"
                                                    data-idx="{{ $loop->index }}"></i></td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="mt-5 float-end">
                            <button class="btn btn-primary js-save-estimate-discipline" {{sizeof($workItem) > 0 ? "data-update=true" : ''}} >Save As Draft</button>
                            <button class="btn btn-primary js-save-estimate-discipline" {{sizeof($workItem) > 0 ? "data-update=true" : ''}}>Publish</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @include('estimate_discipline.modal_detail')
@endif




