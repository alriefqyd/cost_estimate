@inject('workItemController','App\Http\Controllers\WorkItemController')
<table class="table table-bordered" style="border-color: black !important;">
    <thead class="bg-primary">
        <tr>
            <th style="vertical-align : middle;" rowspan="2" class="text-center min-w-250 th-lg">Location/Equipment</th>
            <th style="vertical-align : middle;" rowspan="2" class="text-center min-w-250 th-lg">Discipline</th>
            <th style="vertical-align : middle;" rowspan="2" class="text-center min-w-250 th-lg">Work Element</th>
            <th scope="col" style="vertical-align : middle;" rowspan="2" class="text-center min-w-300">Work Item</th>
            <th scope="col" style="vertical-align : middle;" rowspan="2" class="text-center">Vol</th>
            <th scope="col" style="vertical-align : middle;" rowspan="2" class="text-center">Unit</th>
            <th scope="col" style="vertical-align : middle;" colspan="2" class="text-center">Labour Cost</th>
            <th scope="col" style="vertical-align : middle;" colspan="2" class="text-center">Tool & Equipment</th>
            <th scope="col" style="vertical-align : middle;" colspan="2" class="text-center">Material Cost</th>
            <th scope="col" style="vertical-align : middle;" rowspan="2" class="text-center min-w-160">Total Work Cost</th>
            <th scope="col" style="vertical-align : middle;" rowspan="2" class="text-center">Labor Factorial</th>
            <th scope="col" style="vertical-align : middle;" rowspan="2" class="text-center">Tool Equipment Factorial</th>
            <th scope="col" style="vertical-align : middle;" rowspan="2" class="text-center">Material Factorial</th>
        </tr>
        <tr style="text-align: center">
            <th style="vertical-align : middle;">
                Unit Rate
            </th>
            <th style="vertical-align : middle;">
                Total Labor
            </th>
            <th style="vertical-align : middle;">
                Unit Rate
            </th>
            <th style="vertical-align : middle;">
                Total Material
            </th>
            <th style="vertical-align : middle;">
                Unit Rate
            </th>
            <th style="vertical-align : middle;">
                Total Tool & Equipment
            </th>
        </tr>
    </thead>
    <tbody>

    @php ($totalCost = 0)
    @foreach($estimateAllDisciplines as $key => $value)
        @php ($totalByWorkElement = $workItemController->sumTotalByLocation($value)['totalWorkCostByElement'])
        @php ($totalCost += $totalByWorkElement)
        <tr class="font-weight-bold" style="background-color: #c4bd97">
            <td>{{$key}}</td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td>{{$workItemController->sumTotalByLocation($value)['totalLaborByWorkElement']}}</td>
            <td></td>
            <td>{{$workItemController->sumTotalByLocation($value)['totalEquipmentByWorkElement']}}</td>
            <td></td>
            <td>{{$workItemController->sumTotalByLocation($value)['totalMaterialByWorkElement']}}</td>
            <td>{{$workItemController->toCurrency($totalByWorkElement)}}</td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
        @foreach($value as $item)
            <tr>
                <td></td>
                <td>{{$item->wbsLevels3->wbsDiscipline->title}}</td>
                <td>{{$item?->wbsLevels3->workElements?->title}}</td>
                <td>{{$item?->workItems?->description}}</td>
                <td>{{$item?->volume}}</td>
                <td>{{$item?->workItems?->unit}}</td>
                <td>{{$workItemController->getResultCount($item->labor_unit_rate, $item->labour_factorial)}}</td>
                <td>{{number_format($item->labor_cost_total_rate,2,',','.')}}</td>
                <td>{{$workItemController->getResultCount($item->tool_unit_rate, $item->equipment_factorial)}}</td>
                <td>{{number_format($item->tool_unit_rate_total,2,',','.')}}</td>
                <td>{{$workItemController->getResultCount($item->material_unit_rate,$item->material_factorial) }}</td>
                <td>{{number_format($item->material_unit_rate_total,2,',','.')}}</td>
                <td></td>
                <td>{{$item->labour_factorial}}</td>
                <td>{{$item->equipment_factorial}}</td>
                <td>{{$item->material_factorial}}</td>
            </tr>
        @endforeach
    @endforeach
    <tr class="bg-brown font-weight-bold">
        <td colspan="12">CONTINGENCY</td>
        <td colspan="4">{{number_format($project->getContingencyCost(),2,',','.')}}</td>
    </tr>
    <tr class="bg-brown font-weight-bold">
        <td colspan="12" >TOTAL</td>
        <td colspan="4">{{number_format($project->getTotalCostWithContingency(),2,',','.')}}</td>
    </tr>
    </tbody>
</table>
