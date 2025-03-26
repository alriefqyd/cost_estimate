@inject('workItemController','App\Http\Controllers\WorkItemController')
<style>
    .page-break {
        page-break-inside: avoid;
    }

    .tbd {
        margin: 0;
    }
    .tbd th {
        padding: 5px;
    }

    .tbd tr td {
        padding: 4px;
    }

    .title-block {
        font-family: Arial, Helvetica, sans-serif;
        text-align: left;
        margin-bottom: 20px;
        display: flex;
        align-items: center;
    }

    .title-block h1 {
        font-size: 16px;
        font-weight: bold;
        margin-right: 20px; /* Adds space between the text and the image */
    }

    .title-block .subtitle {
        font-size: 14px;
        font-weight: bold;
        margin-bottom: 0;
    }

    .title-block .details {
        font-size: 12px;
        margin-top: 10px;
        width: 60%;
    }

    .details tr {
        padding: 5px;
    }

    .details td {
        padding: 0;
    }

    .tbd {
        width: 100%; /* Set table width to fill container */
        border-collapse: collapse; /* Avoid gaps */
    }

    .tbd th, .tbd td {
        width: auto; /* Allow columns to automatically adjust */
        padding: 5px;
        border: 1px solid black;
    }
</style>
<div class="title-block">
    <img style="width: 120px; height: auto; position: absolute; top:-20px; right: 80px" src="{{'data:image/png;base64,'.base64_encode(file_get_contents(public_path('assets/images/vale-logo.png')))}}">
    <h1 style="margin: 0;">PT VALE INDONESIA, TBK </h1>
   <div class="row" style="width: 100%; display: flex; margin-top: 10px">
        <div class="title">
            <div class="subtitle" style="font-weight: bold;">DEPARTMENT ENGINEERING AND CONSTRUCTION - ENGINEERING SERVICE </div>
            <div class="subtitle" style="font-weight: bold;">SUMMARY COST ESTIMATE</div>
        </div>
    </div>

    <table class="details">
        <tr>
            <td>PROJECT NO:</td>
            <td>{{$project->project_no}}</td>
        </tr>
        <tr>
            <td>PROJECT TITLE:</td>
            <td>{{$project->project_title}}</td>
        </tr>
        <tr>
            <td>PROJECT MANAGER:</td>
            <td>{{$project->projectManager?->profiles?->full_name}}</td>
        </tr>
        <tr>
            <td>PROJECT ENGINEER:</td>
            <td>{{$project->projectEngineer?->profiles?->full_name}}</td>
        </tr>
        <tr>
            <td>DESIGN ENGINEER:</td>
            <td>{{$project->getAllEngineerExcel()}}</td>
        </tr>
    </table>
</div>
<table class="tbd" style="font-family: Arial, Helvetica, sans-serif; font-size: 12px">
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
                    <td colspan="" style="background-color: #C4BD97;text-align: right">{{number_format($costProject[$key]->totalWorkCost,2,',','.')}}</td>
                    <td colspan="" style="background-color: #C4BD97;text-align: right">{{number_format($costProject[$key]->totalWorkCost / $usdIdr,2,',','.') }}</td>
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
                    <td style="background-color: #DDD9C4;text-align: right">{{number_format($costProject[$key]->disciplineLaborCost[$item->disciplineTitle],2,',','.')}}</td>
                    <td style="background-color: #DDD9C4"></td>
                    <td style="background-color: #DDD9C4;text-align: right">{{number_format($costProject[$key]->disciplineToolCost[$item->disciplineTitle],2,',','.')}}</td>
                    <td style="background-color: #DDD9C4"></td>
                    <td style="background-color: #DDD9C4;text-align: right">{{number_format($costProject[$key]->disciplineMaterialCost[$item->disciplineTitle],2,',','.')}}</td>
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
                    <td style="background-color: #eceae0;text-align: right">{{number_format($costProject[$key]->elementLaborCost[$item->workElementTitle],2,',','.')}}</td>
                    <td style="background-color: #eceae0"></td>
                    <td style="background-color: #eceae0;text-align: right">{{number_format($costProject[$key]->elementToolCost[$item->workElementTitle],2,',','.')}}</td>
                    <td style="background-color: #eceae0"></td>
                    <td style="background-color: #eceae0;text-align: right">{{number_format($costProject[$key]->elementMaterialCost[$item->workElementTitle],2,',','.')}}</td>
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
                    <td style="text-align: right">{{$item?->workItemUnitRateLaborCost}}</td>
                    <td style="text-align: right">{{number_format($item?->workItemTotalLaborCost ?: 0 ,2,',','.')}}</td>
                    <td style="text-align: right">{{$item?->workItemUnitRateToolCost}}</td>
                    <td style="text-align: right">{{number_format($item?->workItemTotalToolCost ?: 0,2,',','.')}}</td>
                    <td style="text-align: right">{{$item?->workItemUnitRateMaterialCost}}</td>
                    <td style="text-align: right">{{number_format($item?->workItemTotalMaterialCost ?: 0,2,',','.')}}</td>
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
        <td style="background-color: #C4BD97"><b>CONTINGENCY</b></td>
        <td style="background-color: #C4BD97"></td>
        <td style="background-color: #C4BD97"></td>
        <td style="background-color: #C4BD97"></td>
        <td style="background-color: #C4BD97"></td>
        <td style="background-color: #C4BD97"></td>
        <td style="background-color: #C4BD97"></td>
        <td style="background-color: #C4BD97"></td>
        <td style="background-color: #C4BD97"></td>
        <td style="background-color: #C4BD97; text-align: right">{{number_format($project->getContingencyCost(),2,',','.')}}</td>
        <td style="background-color: #C4BD97; text-align: right">{{number_format(($project->getContingencyCost() / $usdIdr),2,',','.')}}</td>
    </tr>
    <tr>
        <td style="background-color: #FFC000">{{chr(64 + sizeof($estimateAllDisciplines) + 2)}}</td>
        <td style="background-color: #FFC000"></td>
        <td style="background-color: #FFC000"></td>
        <td style="background-color: #FFC000"><b>TOTAL PROJECT COST</b></td>
        <td style="background-color: #FFC000"></td>
        <td style="background-color: #FFC000"></td>
        <td style="background-color: #FFC000"></td>
        <td style="background-color: #FFC000"></td>
        <td style="background-color: #FFC000"></td>
        <td style="background-color: #FFC000"></td>
        <td style="background-color: #FFC000"></td>
        <td style="background-color: #FFC000"></td>
        <td style="background-color: #FFC000; text-align: right">{{number_format($project->getTotalCostWithContingency(),2,',','.')}}</td>
        <td style="background-color: #FFC000; text-align: right">{{number_format(($project->getTotalCostWithContingency() / $usdIdr) ,2,',','.')}}</td>
    </tr>
    </tbody>
</table>
<div style="margin-bottom: 100px">
    <p style="font-family: Arial, Helvetica, sans-serif">Approval Status</p>
    <table style="margin-top: 20px; font-family: Arial, Helvetica, sans-serif">
        <tr>
            @if(isset($project->designEngineerCivil))
                <td style="padding: 0 20px 0 20px ">Civil Reviewer</td>
            @endif
            @if(isset($project->designEngineerMechanical))
                <td style="padding: 0 20px 0 20px ">Mechanical Reviewer</td>
            @endif
            @if(isset($project->designEngineerArchitect))
                <td style="padding: 0 20px 0 20px ">Architecture Reviewer</td>
            @endif
            @if(isset($project->designEngineerElectrical))
                <td style="padding: 0 20px 0 20px ">Electrical Reviewer</td>
            @endif
            @if(isset($project->designEngineerInstrument))
                <td style="padding: 0 20px 0 20px ">Instrument Reviewer</td>
            @endif
            @if(isset($project->designEngineerIt))
                <td style="padding: 0 20px 0 20px ">IT Reviewer</td>
            @endif

        </tr>
        <tr style="text-align: center">
            @if(isset($project->designEngineerCivil))
                <td style="padding: 0 20px 0 20px ">{{$project->civil_approval_status}}</td>
            @endif
            @if(isset($project->designEngineerMechanical))
                <td style="padding: 0 20px 0 20px ">{{$project->mechanical_approval_status}}</td>
            @endif
            @if(isset($project->designEngineerArchitect))
                <td style="padding: 0 20px 0 20px ">{{$project->architecture_approval_status}}</td>
            @endif
            @if(isset($project->designEngineerElectrical))
                <td style="padding: 0 20px 0 20px ">{{$project->electrical_approval_status}}</td>
            @endif
            @if(isset($project->designEngineerInstrument))
                <td style="padding: 0 20px 0 20px ">{{$project->instrument_approval_status}}</td>
            @endif
            @if(isset($project->designEngineerIt))
                <td style="padding: 0 20px 0 20px ">{{$project->it_approval_status}}</td>
            @endif
        </tr>
        <tr style="text-align: center;">
        <tr style="text-align: center;">
            @if(isset($project->designEngineerCivil))
                <td style="border-top: solid 2px black">{{$project->reviewerCivil?->profiles?->full_name}}</td>
            @endif
            @if(isset($project->designEngineerMechanical))
                <td style="border-top: solid 2px black">{{$project->reviewerMechanical?->profiles?->full_name}}</td>
            @endif
            @if(isset($project->designEngineerArchitect))
                <td style="border-top: solid 2px black">{{$project->reviewerArchitect?->profiles?->full_name}}</td>
            @endif
            @if(isset($project->designEngineerElectrical))
                <td style="border-top: solid 2px black">{{$project->reviewerElectrical?->profiles?->full_name}}</td>
            @endif
            @if(isset($project->designEngineerInstrument))
                <td style="border-top: solid 2px black">{{$project->reviewerInstrument?->profiles?->full_name}}</td>
            @endif
            @if(isset($project->designEngineerIt))
                <td style="border-top: solid 2px black">{{$project->reviewerIt?->profiles?->full_name}}</td>
            @endif
        </tr>
    </table>
</div>

<small style="font-family: Arial, Helvetica, sans-serif; margin-top: 40px">* Generated by web cost estimate. <a href="http://10.34.168.90:8080/project/{{$project->id}}">http://10.34.168.90:8080/project/{{$project->id}}</a></small>
