@inject('workItemController','App\Http\Controllers\WorkItemController')
<table class="tg">
    <tr>
        <td></td>
    </tr>
    <tr>
        <td>PT VALE INDONESIA, TBK</td>
    </tr>
    <tr>
        <td>
            DEPARTEMENT ENGINEERING AND CONSTRUCTION - ENGINEERING SERVICE
        </td>
    </tr>
    <tr>
        <td></td>
    </tr>
    <tr>
        <td>SUMMARY COST ESTIMATE</td>
    </tr>
    <tr></tr>

    <tr>
        <td>
            <div class="col-md-12">
                <div class="col-md-2">
                    PROJECT NO
                </div>
                <div class="col-md-2">
                    : {{$project->project_no}}
                </div>
            </div>
        </td>
    </tr>
    <tr>
        <td>PROJECT TITLE : {{$project->project_title}}</td>
    </tr>
    <tr>
        <td>PROJECT MANAGER : {{$project->project_manager}}</td>
    </tr>
    <tr>
        <td>PROJECT ENGINEER : {{$project->project_engineer}}</td>
    </tr>
    <tr>
        <td>DESIGN ENGINEER : {{$project->getMechanicalEngineer()}},{{$project->getCivilEngineer()}}
            ,{{$project->getElectricalEngineer()}},{{$project->getInstrumentEngineer()}}</td>
    </tr>
    <tr>
        <td></td>
    </tr>

    <thead>
        <tr>
            <th rowspan="2" style="background-color: #FFC000">LOC/<br>EQUIP</th>
            <th rowspan="2" style="background-color: #FFC000">DISCI<br>PLINE</th>
            <th rowspan="2" style="background-color: #FFC000">WORK<br>ELEMENT</th>
            <th rowspan="2" style="background-color: #FFC000">DESCRIPTION</th>
            <th rowspan="2" style="background-color: #FFC000">WORK ITEM</th>
            <th rowspan="2" style="background-color: #FFC000">LABOR COST (IDR)</th>
            <th rowspan="2" style="background-color: #FFC000">TOOL AND <br>EQUIPMENT COST (IDR)</th>
            <th rowspan="2" style="background-color: #FFC000">MATERIAL COST (IDR)</th>
            <th rowspan="2" style="background-color: #FFC000">TOTAL WORK COST (IDR)</th>
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
                <td>{{$workItemController->toCurrency($item->labor_cost_total_rate)}}</td>
                <td>{{$workItemController->toCurrency($item->tool_unit_rate_total) }}</td>
                <td>{{$workItemController->toCurrency($item->material_unit_rate_total)}}</td>
            </tr>
            @php($previousLocation = $key)
            @php($previousWbsLevel3 = $item->wbsLevels3->identifier)
            @php($previousDiscipline = $item->wbsLevels3->wbsDiscipline->id)
            @php($previousElement = $item->wbsLevels3->workElements->id)
        @endforeach
    @endforeach
    @php($contigency = $totalCost * (15/100))
        <tr>
            <td style="background-color: #C4BD97">{{chr(64 + sizeof($estimateAllDisciplines) + 1)}}</td>
            <td style="background-color: #C4BD97"></td>-
            <td style="background-color: #C4BD97"></td>
            <td style="background-color: #C4BD97">CONTINGENCY</td>
            <td style="background-color: #C4BD97"></td>
            <td style="background-color: #C4BD97"></td>
            <td style="background-color: #C4BD97"></td>
            <td style="background-color: #C4BD97"></td>
            <td style="background-color: #C4BD97">{{$workItemController->toCurrency($contigency)}}</td>
        </tr>
        <tr>
            <td style="background-color: #FFC000">{{chr(64 + sizeof($estimateAllDisciplines) + 2)}}</td>
            <td style="background-color: #FFC000"></td>
            <td style="background-color: #FFC000"></td>
            <td style="background-color: #FFC000">TOTAL PROJECT COST</td>
            <td style="background-color: #FFC000"></td>
            <td style="background-color: #FFC000"></td>
            <td style="background-color: #FFC000"></td>
            <td style="background-color: #FFC000"></td>
            <td style="background-color: #FFC000">{{$workItemController->toCurrency($totalCost + $contigency)}}</td>
        </tr>
    </tbody>
</table>
