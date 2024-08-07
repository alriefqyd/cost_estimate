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
            DEPARTMENT ENGINEERING AND CONSTRUCTION - ENGINEERING SERVICE
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
            <td>PROJECT MANAGER : {{$project->projectManager?->profiles?->full_name}}</td>
    </tr>
    <tr>
        <td>PROJECT ENGINEER : {{$project->projectEngineer?->profiles?->full_name}}</td>
    </tr>
    <tr>
        <td>DESIGN ENGINEER : {{$project->getAllEngineerExcel()}}</td>
    </tr>
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td class="">* USD converted from IDR (1 USD = {{toCurrency($usdIdr)}}).</td>
    </tr>

    <thead>
    <tr>
        <th rowspan="2" style="background-color: #FFC000">LOC/<br>EQUIP</th>
        <th rowspan="2" style="background-color: #FFC000">DISCI<br>PLINE</th>
        <th rowspan="2" style="background-color: #FFC000">WORK<br>ELEMENT</th>
        <th rowspan="2" style="background-color: #FFC000">DESCRIPTION</th>
        <th rowspan="2" style="background-color: #FFC000">VOL</th>
        <th rowspan="2" style="background-color: #FFC000">UNIT</th>
        {{-- <th rowspan="2" style="background-color: #FFC000">WORK ITEM</th>--}}
        <th colspan="2" style="background-color: #FFC000">LABOR COST (IDR)</th>
        <th colspan="2" style="background-color: #FFC000">TOOL AND EQUIPMENT COST (IDR)</th>
        <th colspan="2" style="background-color: #FFC000">MATERIAL COST (IDR)</th>
        <th rowspan="2" style="background-color: #FFC000">TOTAL WORK COST (IDR)</th>
        <th rowspan="2" style="background-color: #FFC000">TOTAL WORK COST (USD)</th>
    </tr>
    <tr>
        <th style="background-color: #FFC000">UNIT RATE</th>
        <th style="background-color: #FFC000">TOTAL</th>
        <th style="background-color: #FFC000">UNIT RATE</th>
        <th style="background-color: #FFC000">TOTAL</th>
        <th style="background-color: #FFC000">UNIT RATE</th>
        <th style="background-color: #FFC000">TOTAL</th>
    </tr>
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
        @php($alpha = $idxAlphabet)
        @php($idElement2 = 1)

        @foreach($value as $item)
            @if($key !== $previousLocation)
                @php($idNum = 1)
                <tr>
                    <td style="background-color: #C4BD97">{{$idxAlphabet++}}</td>
                    <td style="background-color: #C4BD97"></td>
                    <td style="background-color: #C4BD97"></td>
                    <td colspan="9" style="background-color: #C4BD97;font-weight: bold">{{$key}}</td>
                    <td colspan="" style="background-color: #C4BD97">{{$costProject[$key]->totalWorkCost}}</td>
                    <td colspan="" style="background-color: #C4BD97">{{$costProject[$key]->totalWorkCost / $usdIdr }}</td>
                </tr>
            @endif
            @php($codeDiscipline = null);
            @if($item->disciplineTitle !== $previousDiscipline
                || $key !== $previousLocation)
                @php($idElement2 = 1)
                <tr>
                    <td style="background-color: #DDD9C4"></td>
                    <td style="background-color: #DDD9C4" colspan="">{{$alpha}}.{{$idNum++}}</td>
                    <td style="background-color: #DDD9C4"></td>
                    <td style="background-color: #DDD9C4" colspan="">{{$item?->disciplineTitle}}</td>
                    <td style="background-color: #DDD9C4"></td>
                    <td style="background-color: #DDD9C4"></td>
                    <td style="background-color: #DDD9C4"></td>
                    <td style="background-color: #DDD9C4">{{$costProject[$key]->disciplineLaborCost[$item->disciplineTitle]}}</td>
                    <td style="background-color: #DDD9C4"></td>
                    <td style="background-color: #DDD9C4">{{$costProject[$key]->disciplineToolCost[$item->disciplineTitle]}}</td>
                    <td style="background-color: #DDD9C4"></td>
                    <td style="background-color: #DDD9C4">{{$costProject[$key]->disciplineMaterialCost[$item->disciplineTitle]}}</td>
                    <td style="background-color: #DDD9C4"></td>
                    <td style="background-color: #DDD9C4"></td>
                </tr>
            @endif
            @if($item->workElementTitle !== $previousElement || $key !== $previousLocation)
                {{$idElement = $idNum - 1}}
                <tr>
                    <td style="background-color: #eceae0"></td>
                    <td style="background-color: #eceae0"></td>
                    <td style="background-color: #eceae0">{{$alpha}}.{{$idElement}}.{{$idElement2++}}</td>
                    <td style="background-color: #eceae0">{{$item?->workElementTitle}}</td>
                    <td style="background-color: #eceae0"></td>
                    <td style="background-color: #eceae0"></td>
                    <td style="background-color: #eceae0"></td>
                    <td style="background-color: #eceae0">{{$costProject[$key]->elementLaborCost[$item->workElementTitle]}}</td>
                    <td style="background-color: #eceae0"></td>
                    <td style="background-color: #eceae0">{{$costProject[$key]->elementToolCost[$item->workElementTitle]}}</td>
                    <td style="background-color: #eceae0"></td>
                    <td style="background-color: #eceae0">{{$costProject[$key]->elementMaterialCost[$item->workElementTitle]}}</td>
                    <td style="background-color: #eceae0"></td>
                    <td style="background-color: #eceae0"></td>

                </tr>
            @endif
            @if($isDetail)
                <tr>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td colspan="">{{$item?->workItemDescription}}</td>
                    <td>{{$item?->estimateVolume}}</td>
                    <td>{{$item?->workItemUnit}}</td>
                    <td>{{$item?->workItemUnitRateLaborCost}}</td>
                    <td>{{$item?->workItemTotalLaborCost ?: ""}}</td>
                    <td>{{$item?->workItemUnitRateToolCost}}</td>
                    <td>{{$item?->workItemTotalToolCost ?: ""}}</td>
                    <td>{{$item?->workItemUnitRateMaterialCost}}</td>
                    <td>{{$item?->workItemTotalMaterialCost ?: ""}}</td>
                    <td></td>
                    <td></td>
                </tr>
            @endif
            {{--
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td>{{$item?->workItemDescription}}</td>
                <td>{{number_format($item->workItemTotalLaborCost,2,'.',',')}}</td>
                <td>{{number_format($item->workItemTotalToolCost,2,'.',',')}}</td>
                <td>{{number_format($item->workItemTotalMaterialCost,2,'.',',')}}</td>
            </tr>
            --}}
            @php($previousLocation = $key)
            @php($previousWbsLevel3 = $item->workItemIdentifier)
            @php($previousDiscipline = $item->disciplineTitle)
            @php($previousElement = $item->workElementTitle)
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
        <td style="background-color: #C4BD97"></td>
        <td style="background-color: #C4BD97"></td>
        <td style="background-color: #C4BD97"></td>
        <td style="background-color: #C4BD97"></td>
        <td style="background-color: #C4BD97">{{$project->getContingencyCost()}}</td>
        <td style="background-color: #C4BD97">{{$project->getContingencyCost() / $usdIdr}}</td>
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
        <td style="background-color: #FFC000"></td>
        <td style="background-color: #FFC000"></td>
        <td style="background-color: #FFC000"></td>
        <td style="background-color: #FFC000"></td>
        <td style="background-color: #FFC000">{{$project->getTotalCostWithContingency()}}</td>
        <td style="background-color: #FFC000">{{$project->getTotalCostWithContingency() / $usdIdr}}</td>
    </tr>
    <tr></tr>
    <tr></tr>
    <tr>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td><small>* Generated by web cost estimate. <a href="http://10.34.168.208:5000/project/{{$project->id}}">http://10.34.168.208:5000/project/{{$project->id}}</a></small></td>
    </tr>
    </tbody>
</table>
