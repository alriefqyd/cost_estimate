@inject('workItemController','App\Http\Controllers\WorkItemController')
<div class="table-responsive">
    <table class="table table-bordered" style="border-color: black !important;">
        <thead class="bg-primary">
            <tr>
                <th style="vertical-align : middle;" rowspan="2" class="text-center th-lg min-w-110">Location / Equipment</th>
                <th style="vertical-align : middle;" rowspan="2" class="text-center min-w-110 th-lg">Discipline</th>
                <th style="vertical-align : middle;" rowspan="2" class="text-center min-w-110 th-lg">Work Element</th>
                <th scope="col" style="vertical-align : middle;" rowspan="2" class="text-center min-w-300">Work Item</th>
                <th scope="col" style="vertical-align : middle;" rowspan="2" class="text-center min-w-40">Vol</th>
                <th scope="col" style="vertical-align : middle;" rowspan="2" class="text-center">Unit</th>
                <th scope="col" style="vertical-align : middle;" colspan="2" class="text-center">Labour Cost (IDR)</th>
                <th scope="col" style="vertical-align : middle;" colspan="2" class="text-center">Tool & Equipment (IDR)</th>
                <th scope="col" style="vertical-align : middle;" colspan="2" class="text-center">Material Cost (IDR)</th>
                <th scope="col" style="vertical-align : middle;" rowspan="2" class="text-center min-w-130">Total Work Cost (IDR)</th>
                <th scope="col" style="vertical-align : middle;" colspan="3" class="text-center">Fac</th>
            </tr>
            <tr style="text-align: center">
                <th style="vertical-align : middle;">
                    Unit Rate
                </th>
                <th style="vertical-align : middle;" class="min-w-110">
                    Total
                </th>
                <th style="vertical-align : middle;">
                    Unit Rate
                </th>
                <th style="vertical-align : middle;" class="min-w-110">
                    Total
                </th>
                <th style="vertical-align : middle;">
                    Unit Rate
                </th>
                <th style="vertical-align : middle;" class="min-w-110">
                    Total
                </th>
                <th scope="col" style="vertical-align : middle;" class="text-center min-w-65">Labor</th>
                <th scope="col" style="vertical-align : middle;" class="text-center min-w-65">Tool</th>
                <th style="vertical-align : middle;" class="text-center min-w-75">Material</th>
            </tr>
        </thead>
        <tbody>

        @foreach($estimateAllDisciplines as $key => $value)
            @php($previousDiscipline = null)
            @php($previousWorkElement = null)
            <tr style="background-color: #C5C5C7D0">
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
                    <tr class="" style="background-color: #DEDEDED0">
                        <td></td>
                        <td colspan="">{{$item->disciplineTitle != $previousDiscipline ? $item->disciplineTitle : ''}}</td>
                        <td colspan="14"></td>
                    </tr>
                @endif
                @if($item->workElementTitle != $previousWorkElement)
                    <tr class="" style="background-color: #EFEFEFD0">
                        <td></td>
                        <td></td>
                        <td>{{$item->workElementTitle != $previousWorkElement ? $item->workElementTitle  : ''}}
                        </td>
                        <td colspan="13"></td>
                    </tr>
                @endif
                <tr >
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>{{$item->workItemDescription}}</td>
                    <td>{{$item->estimateVolume}}</td>
                    <td>{{$item->workItemUnit}}</td>
                    <td>{{$item->workItemUnitRateLaborCost}}</td>
                    <td>{{number_format($item->workItemTotalLaborCost,2,',','.')}}</td>
                    <td>{{$item->workItemUnitRateToolCost}}</td>
                    <td>{{number_format($item->workItemTotalToolCost,2,',','.')}}</td>
                    <td>{{$item->workItemUnitRateMaterialCost}}</td>
                    <td>{{number_format($item->workItemTotalMaterialCost,2,',','.')}}</td>
                    <td></td>
                    <td>{{$item->workItemLaborFactorial}}</td>
                    <td>{{$item->workItemEquipmentFactorial}}</td>
                    <td>{{$item->workItemMaterialFactorial}}</td>
                </tr>
                @php($previousDiscipline = $item->disciplineTitle)
                @php($previousWorkElement = $item->workElementTitle)
            @endforeach
        @endforeach
        <tr class="font-weight-bold" style="background-color: #C5C5C7D0">
            <td colspan="12">CONTINGENCY</td>
            <td colspan="4">{{number_format($project->getContingencyCost(),2,',','.')}}</td>
        </tr>
        <tr class="font-weight-bold" style="background-color: #C5C5C7D0">
            <td colspan="12" >TOTAL</td>
            <td colspan="4">{{number_format($project->getTotalCostWithContingency(),2,',','.')}}</td>
        </tr>
        </tbody>
    </table>
</div>
