@inject('workItemController','App\Http\Controllers\WorkItemController')
<div class="table-responsive">
    <table class="table table-bordered" style="border-color: black !important;">
        <thead class="bg-primary">
            <tr>
                <th style="vertical-align : middle;" rowspan="2" class="text-center min-w-250 th-lg">Location/Equipment</th>
                <th style="vertical-align : middle;" rowspan="2" class="text-center min-w-250 th-lg">Discipline</th>
                <th style="vertical-align : middle;" rowspan="2" class="text-center min-w-250 th-lg">Work Element</th>
                <th scope="col" style="vertical-align : middle;" rowspan="2" class="text-center min-w-400">Work Item</th>
                <th scope="col" style="vertical-align : middle;" rowspan="2" class="text-center min-w-50">Vol</th>
                <th scope="col" style="vertical-align : middle;" rowspan="2" class="text-center">Unit</th>
                <th scope="col" style="vertical-align : middle;" colspan="2" class="text-center">Labour Cost</th>
                <th scope="col" style="vertical-align : middle;" colspan="2" class="text-center">Tool & Equipment</th>
                <th scope="col" style="vertical-align : middle;" colspan="2" class="text-center">Material Cost</th>
                <th scope="col" style="vertical-align : middle;" rowspan="2" class="text-center min-w-160">Total Work Cost</th>
                <th scope="col" style="vertical-align : middle;" rowspan="2" class="text-center min-w-120">Labor Fact</th>
                <th scope="col" style="vertical-align : middle;" rowspan="2" class="text-center min-w-150">Tool Equip Fact</th>
                <th style="vertical-align : middle;" rowspan="2" class="text-center min-w-120">Material Fact</th>
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
                <th style="vertical-align : middle;" class="min-w-150">
                    Total Tool & Equip
                </th>
                <th style="vertical-align : middle;">
                    Unit Rate
                </th>
                <th style="vertical-align : middle;" class="min-w-150">
                    Total Material
                </th>
            </tr>
        </thead>
        <tbody>

        @foreach($estimateAllDisciplines as $key => $value)
            @php($previousDiscipline = null)
            @php($previousWorkElement = null)
            <tr class="font-weight-bold" style="background-color: #b5ac76">
                <td>{{$key}}</td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td>{{$costProject[$key]->totalLaborCost}}</td>
                <td></td>
                <td>{{$costProject[$key]->totalEquipmentCost}}</td>
                <td></td>
                <td>{{$costProject[$key]->totalMaterialCost}}</td>
                <td>{{number_format($costProject[$key]->totalWorkCost,2,'.',',')}}</td>
                <td></td>
                <td></td>
                <td></td>
            </tr>
            @foreach($value as $item)
                @if($item->disciplineTitle != $previousDiscipline)
                    <tr class="font-weight-bold" style="background-color: #c4bd97">
                        <td></td>
                        <td colspan="15">{{$item->disciplineTitle != $previousDiscipline ? $item->disciplineTitle : ''}}</td>
                    </tr>
                @endif
                @if($item->workElementTitle != $previousWorkElement)
                    <tr class="font-weight-bold" style="background-color: #c9c5aa">
                        <td></td>
                        <td></td>
                        <td colspan="14">{{$item->workElementTitle != $previousWorkElement ? $item->workElementTitle : ''}}</td>
                    </tr>
                @endif
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>{{$item->workItemDescription}}</td>
                    <td>{{$item->estimateVolume}}</td>
                    <td>{{$item->workItemUnit}}</td>
                    <td>{{$item->workItemUnitRateLaborCost}}</td>
                    <td>{{number_format($item->workItemTotalLaborCost,2,'.',',')}}</td>
                    <td>{{$item->workItemUnitRateToolCost}}</td>
                    <td>{{number_format($item->workItemTotalToolCost,2,'.',',')}}</td>
                    <td>{{$item->workItemUnitRateMaterialCost}}</td>
                    <td>{{number_format($item->workItemTotalMaterialCost,2,'.',',')}}</td>
                    <td></td>
                    <td>{{$item->workItemLaborFactorial}}</td>
                    <td>{{$item->workItemEquipmentFactorial}}</td>
                    <td>{{$item->workItemMaterialFactorial}}</td>
                </tr>
                @php($previousDiscipline = $item->disciplineTitle)
                @php($previousWorkElement = $item->workElementTitle)
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
</div>
