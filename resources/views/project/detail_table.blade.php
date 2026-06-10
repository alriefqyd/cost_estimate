@inject('workItemController','App\Http\Controllers\WorkItemController')
@php
    $grouped = [];
    foreach ($estimateAllDisciplines as $locationKey => $items) {
        foreach ($items as $item) {
            $grouped[$locationKey][$item->disciplineTitle ?? 'Uncategorized'][$item->workElementTitle ?? 'Uncategorized'][] = $item;
        }
    }

    $disciplineTotals = [];
    foreach ($grouped as $locationKey => $disciplineGroup) {
        foreach ($disciplineGroup as $disciplineName => $workElementGroup) {
            $labor = 0; $tool = 0; $material = 0; $total = 0;
            foreach ($workElementGroup as $workItems) {
                foreach ($workItems as $item) {
                    $labor    += $item->workItemTotalLaborCost    ?? 0;
                    $tool     += $item->workItemTotalToolCost     ?? 0;
                    $material += $item->workItemTotalMaterialCost ?? 0;
                    $total    += $item->workItemTotalCost         ?? 0;
                }
            }
            $disciplineTotals[$locationKey][$disciplineName] = compact('labor','tool','material','total');
        }
    }

@endphp

{{-- Column-group toggles --}}
<div class="d-flex gap-2 mb-2 flex-wrap" id="tour-col-toggles">
    <button type="button" class="btn btn-xs btn-outline-secondary js-toggle-col-group active"
            data-group="unit-rate" title="Show / hide unit rate columns">
        <i class="fa fa-eye-slash me-1"></i>Unit Rates
    </button>
    <button type="button" class="btn btn-xs btn-outline-secondary js-toggle-col-group active"
            data-group="fac" title="Show / hide factorial columns">
        <i class="fa fa-eye-slash me-1"></i>Factorials
    </button>
</div>

<div class="table-page-flow js-detail-table-wrap" id="tour-estimate-table" style="position:relative;">
    <div class="table-custom table-container" style="position:relative;">
        <table class="table table-custom js-full-estimate-table">
            <thead class="bg-primary">
                <tr>
                    {{-- col 1 --}}
                    <th class="bg-primary text-left" rowspan="2" style="min-width:110px;">Loc / Equip</th>
                    {{-- col 2 --}}
                    <th class="bg-primary text-left" rowspan="2" style="min-width:110px;">Discipline</th>
                    {{-- col 3 --}}
                    <th class="bg-primary text-left" rowspan="2" style="min-width:100px;">Work Element</th>
                    {{-- col 4 --}}
                    <th class="bg-primary text-left" rowspan="2" style="min-width:200px;">Work Item</th>
                    {{-- col 5 --}}
                    <th class="bg-primary text-left" rowspan="2" style="min-width:45px;">Vol</th>
                    {{-- col 6 --}}
                    <th class="bg-primary text-left" rowspan="2" style="min-width:45px;">Unit</th>
                    {{-- cols 7-8: Labour --}}
                    <th class="bg-primary text-center col-group-unit-rate" colspan="1">Labour</th>
                    <th class="bg-primary text-center" colspan="1" style="min-width:110px;">Labour&nbsp;Total (IDR)</th>
                    {{-- cols 9-10: Tool --}}
                    <th class="bg-primary text-center col-group-unit-rate" colspan="1">Tool &amp; Equip</th>
                    <th class="bg-primary text-center" colspan="1" style="min-width:110px;">Tool&nbsp;Total (IDR)</th>
                    {{-- cols 11-12: Material --}}
                    <th class="bg-primary text-center col-group-unit-rate" colspan="1">Material</th>
                    <th class="bg-primary text-center" colspan="1" style="min-width:110px;">Mat.&nbsp;Total (IDR)</th>
                    {{-- col 13 --}}
                    <th class="bg-primary text-left" rowspan="2" style="min-width:120px;">Total Work Cost (IDR)</th>
                    {{-- cols 14-16: Fac --}}
                    <th class="bg-primary text-center col-group-fac" colspan="3">Fac</th>
                </tr>
                <tr class="bg-primary">
                    <th class="bg-primary col-group-unit-rate" style="min-width:100px;">Unit Rate (IDR)</th>
                    <th class="bg-primary" style="min-width:110px;">Total (IDR)</th>
                    <th class="bg-primary col-group-unit-rate" style="min-width:100px;">Unit Rate (IDR)</th>
                    <th class="bg-primary" style="min-width:110px;">Total (IDR)</th>
                    <th class="bg-primary col-group-unit-rate" style="min-width:100px;">Unit Rate (IDR)</th>
                    <th class="bg-primary" style="min-width:110px;">Total (IDR)</th>
                    <th class="bg-primary text-center col-group-fac" style="min-width:50px;">L</th>
                    <th class="bg-primary text-center col-group-fac" style="min-width:50px;">T</th>
                    <th class="bg-primary text-center col-group-fac" style="min-width:50px;">M</th>
                </tr>
            </thead>
            <tbody class="js-table-body-detail">
                @foreach($grouped as $locationKey => $disciplineGroup)
                    <tr class="js-column-location table-row-location" style="background-color:#C5C5C7D0;">
                        <td>
                            <span class="float-start row-hierarchy-label">
                                <i class="fa fa-map-marker-alt me-1" style="font-size:10px;opacity:0.6;"></i>
                                {{ucwords(strtolower($locationKey))}}
                            </span>
                            <div class="d-inline-block float-end collapse-toggle">
                                <i class="fa fa-chevron-up js-minimize cursor-pointer"></i>
                                <i class="fa fa-chevron-down js-maximize cursor-pointer d-none"></i>
                            </div>
                        </td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        {{-- unit-rate cols --}}
                        <td class="col-group-unit-rate"></td>
                        <td>@if(isset($costProject[$locationKey])){{$costProject[$locationKey]->totalLaborCost}}@endif</td>
                        <td class="col-group-unit-rate"></td>
                        <td>@if(isset($costProject[$locationKey])){{$costProject[$locationKey]->totalEquipmentCost}}@endif</td>
                        <td class="col-group-unit-rate"></td>
                        <td>@if(isset($costProject[$locationKey])){{$costProject[$locationKey]->totalMaterialCost}}@endif</td>
                        <td class="f-w-700">@if(isset($costProject[$locationKey])){{number_format($costProject[$locationKey]->totalWorkCost,2,',','.')}}@endif</td>
                        <td class="col-group-fac"></td>
                        <td class="col-group-fac"></td>
                        <td class="col-group-fac"></td>
                    </tr>

                    @foreach($disciplineGroup as $disciplineName => $workElementGroup)
                        @php $dt = $disciplineTotals[$locationKey][$disciplineName] ?? null; @endphp
                        <tr class="js-column-discipline table-row-discipline" style="background-color:#DEDEDED0;">
                            <td></td>
                            <td>
                                <div class="d-flex align-items-start justify-content-between">
                                    <span class="row-hierarchy-label">
                                        <i class="fa fa-layer-group me-1" style="font-size:10px;opacity:0.6;"></i>
                                        {{ucwords(strtolower($disciplineName))}}
                                    </span>
                                    <div class="collapse-toggle ms-2 flex-shrink-0">
                                        <i class="fa fa-chevron-up js-minimize cursor-pointer"></i>
                                        <i class="fa fa-chevron-down js-maximize cursor-pointer d-none"></i>
                                    </div>
                                </div>
                            </td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td class="col-group-unit-rate"></td>
                            <td class="text-end f-w-500">@if($dt){{number_format($dt['labor'],2,',','.')}}@endif</td>
                            <td class="col-group-unit-rate"></td>
                            <td class="text-end f-w-500">@if($dt){{number_format($dt['tool'],2,',','.')}}@endif</td>
                            <td class="col-group-unit-rate"></td>
                            <td class="text-end f-w-500">@if($dt){{number_format($dt['material'],2,',','.')}}@endif</td>
                            <td class="text-end f-w-700">@if($dt){{number_format($dt['total'],2,',','.')}}@endif</td>
                            <td class="col-group-fac"></td>
                            <td class="col-group-fac"></td>
                            <td class="col-group-fac"></td>
                        </tr>

                        @foreach($workElementGroup as $workElementName => $workItems)
                            <tr class="js-column-work-element table-row-work-element" style="background-color:#EFEFEFD0;">
                                <td></td>
                                <td></td>
                                <td>
                                    <div class="d-flex align-items-start justify-content-between">
                                        <span class="row-hierarchy-label">
                                            <i class="fa fa-wrench me-1" style="font-size:10px;opacity:0.6;"></i>
                                            {{$workElementName}}
                                        </span>
                                        <div class="collapse-toggle ms-2 flex-shrink-0">
                                            <i class="fa fa-chevron-up js-minimize cursor-pointer"></i>
                                            <i class="fa fa-chevron-down js-maximize cursor-pointer d-none"></i>
                                        </div>
                                    </div>
                                </td>
                                <td></td><td></td><td></td>
                                <td class="col-group-unit-rate"></td><td></td>
                                <td class="col-group-unit-rate"></td><td></td>
                                <td class="col-group-unit-rate"></td><td></td>
                                <td></td>
                                <td class="col-group-fac"></td>
                                <td class="col-group-fac"></td>
                                <td class="col-group-fac"></td>
                            </tr>

                            @foreach($workItems as $item)
                                <tr class="table-row-work-item" data-estimate-id="{{$item->id}}">
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td class="f-w-500">{{$item->workItemDescription}}</td>
                                    <td>{{$item->estimateVolume}}</td>
                                    <td>{{$item->workItemUnit}}</td>
                                    <td class="col-group-unit-rate">{{$item->workItemUnitRateLaborCost}}</td>
                                    <td class="text-end">{{number_format($item->workItemTotalLaborCost,2,',','.')}}</td>
                                    <td class="col-group-unit-rate">{{$item->workItemUnitRateToolCost}}</td>
                                    <td class="text-end">{{number_format($item->workItemTotalToolCost,2,',','.')}}</td>
                                    <td class="col-group-unit-rate">{{$item->workItemUnitRateMaterialCost}}</td>
                                    <td class="text-end">{{number_format($item->workItemTotalMaterialCost,2,',','.')}}</td>
                                    <td class="f-w-500">{{$item->workItemTotalCostStr}}</td>
                                    <td class="text-center col-group-fac">{{$item->workItemLaborFactorial}}</td>
                                    <td class="text-center col-group-fac">{{$item->workItemEquipmentFactorial}}</td>
                                    <td class="text-center col-group-fac">{{$item->workItemMaterialFactorial}}</td>
                                </tr>
                            @endforeach
                        @endforeach
                    @endforeach
                @endforeach

                <tr class="f-w-700 table-row-contingency">
                    <td colspan="12">
                        <span class="row-hierarchy-label">
                            <i class="fa fa-percentage me-1" style="font-size:10px;opacity:0.7;"></i>
                            Contingency {{$project->projectSettings?->contingency}}%
                        </span>
                    </td>
                    <td colspan="4">{{number_format($project->getContingencyCost(),2,',','.')}}</td>
                </tr>
                <tr class="table-row-grand-total">
                    <td colspan="12">
                        <i class="fa fa-calculator me-1" style="font-size:11px;opacity:0.85;"></i>
                        Grand Total
                    </td>
                    <td colspan="4">{{number_format($project->getTotalCostWithContingency(),2,',','.')}}</td>
                </tr>
            </tbody>
        </table>

        @if($project->isAssignedToProject())
        <div class="annotation-layer js-annotation-layer"
             data-project-id="{{$project->id}}"
             data-readonly="{{ $project->isAssignedReviewer() ? '0' : '1' }}"></div>
        @endif
    </div>
</div>
