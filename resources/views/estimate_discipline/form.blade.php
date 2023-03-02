{{--
!!!!!!!!
add work element and discipline first
input work item based work element and discipline
like canon site behaviour

discipline is max two in condition cost-estimate just one disciplene (general and own discipline)
discipline possible just only one data

work element created based on discipline
work element is not mandatory

work item created based on work element (if exist) or discipline

top of page is create discipline and work element

--}}

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
                                        <th class="text-center min-w-550" style="">
                                            Labour
                                        </th>
                                        <th class="text-center min-w-500" style="">
                                            Tool And Equipment
                                        </th>
                                        <th class="text-center min-w-500" style="">
                                            Material
                                        </th>
                                        <th class="text-center" style="width: 15%">Action</th>
                                    </tr>
                                    </thead>
                                    <tbody class="js-body-work-item-table">
                                    @foreach($workItem as $item)
                                        <tr class="js-work-item-input-column">
                                            <td class="text-center">{{$item->workElements?->name}}</td>
                                            <td>{{$item->workItems->description  }}</td>
                                            <td>{{$item->volume }} {{$item->workItems->unit}}</td>
                                            <td>
                                                <table class="table table-striped">
                                                    <thead>
                                                    <th>Title</th>
                                                    <th>Unit</th>
                                                    <th>Coef</th>
                                                    <th>Rate (Rp)</th>
                                                    <th>Amount (Rp)</th>
                                                    </thead>
                                                    <tbody>
                                                    @foreach($item?->workItems?->manPowers as $manPower)
                                                        <tr>
                                                            <td>{{$manPower->title}}</td>
                                                            <td>{{$manPower?->pivot?->labor_unit}}</td>
                                                            <td>{{$workItemController->toDecimalRound($manPower?->pivot?->labor_coefisient)}}</td>
                                                            <td>{{$workItemController->toCurrency($manPower?->overall_rate_hourly)}}</td>
                                                            <td>{{$workItemController->toCurrency($manPower?->pivot?->amountPivot)}}</td>
                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                            </td>
                                            <td>
                                                <table class="table table-striped">
                                                    <thead>
                                                        <th>Description</th>
                                                        <th>Unit</th>
                                                        <th>Quantity</th>
                                                        <th>Unit Price (Rp)</th>
                                                        <th>Amount (Rp)</th>
                                                    </thead>
                                                    <tbody>
                                                    @foreach($item?->workItems?->equipmentTools as $tools)
                                                        <tr>
                                                            <td>{{ $tools->description }}</td>
                                                            <td>{{ $tools?->pivot?->unit }}</td>
                                                            <td>{{ $tools?->pivot?->quantity }}</td>
                                                            <td>{{ $workItemController->toCurrency($tools?->pivot?->unit_price) }}</td>
                                                            <td>{{ $workItemController->toCurrency($tools?->pivot?->amount) }}</td>
                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                </table>
                                            </td>
                                            <td>
                                                <table class="table table-striped">
                                                    <thead>
                                                    <th>Description</th>
                                                    <th>Unit</th>
                                                    <th>Quantity</th>
                                                    <th>Unit Price (Rp)</th>
                                                    <th>Amount (Rp)</th>
                                                    </thead>
                                                    <tbody>
                                                    @foreach($item?->workItems?->materials as $tools)
                                                        <tr>
                                                            <td>{{ $tools?->tool_equipment_description }}</td>
                                                            <td>{{ $tools?->pivot?->unit }}</td>
                                                            <td>{{ $tools?->pivot?->quantity }}</td>
                                                            <td>{{ $workItemController->toCurrency($tools?->pivot?->unit_price) }}</td>
                                                            <td>{{ $workItemController->toCurrency($tools?->pivot?->amount) }}</td>
                                                        </tr>
                                                    @endforeach
                                                    </tbody>
                                                </table>
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
                            <button class="btn btn-primary js-save-estimate-discipline">Save As Draft</button>
                            <button class="btn btn-primary js-save-estimate-discipline">Publish</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif




