@inject('workItemController','App\Http\Controllers\WorkItemController')
<table class="tg">
    <thead>
        <tr>
            <th rowspan="2" style="background-color: #FFC000" class="text-center-table">Loc/Equip</th>
            <th rowspan="2" style="background-color: #FFC000">Discipline</th>
            <th rowspan="2" style="background-color: #FFC000">Work Element</th>
            <th rowspan="2" style="background-color: #FFC000">Description</th>
            <th rowspan="2" style="background-color: #FFC000">Work Item</th>
            <th rowspan="2" style="background-color: #FFC000">Labor Cost</th>
            <th rowspan="2" style="background-color: #FFC000">Tool and Equipment Cost</th>
            <th rowspan="2" style="background-color: #FFC000">Material Cost</th>
            <th rowspan="2" style="background-color: #FFC000">Total Work Cost</th>
        </tr>
        <tr></tr>
    </thead>
    <tbody>
    @php($totalCost = 0)
    @php($idxAlphabet = 'A')
    @php($idNum = 1)

    @php($previousLocation = null)
    @php($previousDiscipline = null)
    @php($previousElement = null)
    @php($previousWbsLevel3 = null)

    @foreach($estimateAllDisciplines as $key => $value)
        @php ($totalByWorkElement = $workItemController->sumTotalByLocation($value)['totalWorkCostByElement'])
        @php ($totalCost += $totalByWorkElement)
        @php($alpha = $idxAlphabet)
        @php($idElement2 = 1)

        @foreach($value as $item)
            @if($key !== $previousLocation)
                @php($idNum = 1)
                <tr>
                    <td style="background-color: #C4BD97">{{$idxAlphabet++}}</td>
                    <td style="background-color: #C4BD97"></td>
                    <td style="background-color: #C4BD97"></td>
                    <td colspan="5" style="background-color: #C4BD97;font-weight: bold">{{$key}}</td>
                    <td colspan="" style="background-color: #C4BD97">{{$workItemController->toCurrency($totalByWorkElement)}}</td>
                </tr>
            @endif
            @php($codeDiscipline = null);
            @if($item->wbsLevels3->wbsDiscipline->id !== $previousDiscipline
                || $key !== $previousLocation)
                @php($idElement2 = 1)
                <tr>
                    <td></td>
                    <td colspan="">{{$alpha}}.{{$idNum++}}</td>
                    <td></td>
                    <td colspan="">{{$item->wbsLevels3->wbsDiscipline->title}}</td>
                    <td></td>
                </tr>
            @endif
            @if($item->wbsLevels3->workElements->id !== $previousElement || $key !== $previousLocation)
                {{$idElement = $idNum - 1}}
                <tr>
                    <td></td>
                    <td></td>
                    <td>{{$alpha}} . {{$idElement}}. {{$idElement2++}}</td>
                    <td colspan="">{{$item?->wbsLevels3->workElements?->title}}</td>
                </tr>
            @endif
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td>{{$item?->workItems?->description}}</td>
                <td>{{$workItemController->getResultCount($item->labor_cost_total_rate, $item->volume)}}</td>
                <td>{{$workItemController->getResultCount($item->tool_unit_rate_total, $item->volume) }}</td>
                <td>{{$workItemController->getResultCount($item->material_unit_rate_total, $item->volume)}}</td>
            </tr>
            @php($previousLocation = $key)
            @php($previousWbsLevel3 = $item->wbsLevels3->identifier)
            @php($previousDiscipline = $item->wbsLevels3->wbsDiscipline->id)
            @php($previousElement = $item->wbsLevels3->workElements->id)
        @endforeach
    @endforeach
        <tr>
            <td style="background-color: #FFC000"></td>
            <td style="background-color: #FFC000"></td>
            <td style="background-color: #FFC000"></td>
            <td style="background-color: #FFC000">TOTAL PROJECT COST</td>
            <td style="background-color: #FFC000"></td>
            <td style="background-color: #FFC000"></td>
            <td style="background-color: #FFC000"></td>
            <td style="background-color: #FFC000"></td>
            <td style="background-color: #FFC000">{{$workItemController->toCurrency($totalCost)}}</td>
        </tr>
    </tbody>
</table>
