@inject('workItemController','App\Http\Controllers\WorkItemController')
<div class="col-sm-12 col-lg-12 col-xl-12">
    <div class="col">
        <span class="float-end">
            <a href="/project/{{$project->id}}/estimate-discipline/create">
                <button class="btn btn-outline-primary" type="button">
                    {{sizeof($estimateAllDisciplines) > 0 ? 'Edit Data' : 'Add New Data'}}
                </button>
            </a>
        </span>
    </div>
    <div class="clearfix"></div>
    <div class="col">
        <ul class="nav nav-tabs mb-4" id="icon-tab" role="tablist">
            <li class="nav-item"><a class="nav-link {{request()->segment(4) == 'All' || !request()->discipline ? 'active' : ''}}" id="icon-home-tab" href="/project/{{$project->id}}/discipline/all" aria-selected="true"><i class="icofont icofont-ui-home"></i>All</a></li>
            <li class="nav-item"><a class="nav-link {{request()->segment(4) == 'civil' ? 'active' : ''}}" id="icon-home-tab" href="/project/{{$project->id}}/discipline/civil" role="tab" aria-controls="icon-home" aria-selected="false"><i class="icofont icofont-ui-home"></i>Civil</a></li>
            <li class="nav-item"><a class="nav-link {{request()->segment(4) == 'mechanical' ? 'active' : ''}}" id="profile-icon-tab" href="/project/{{$project->id}}/discipline/mechanical" role="tab" aria-controls="profile-icon" aria-selected="false"><i class="icofont icofont-man-in-glasses"></i>Mechanical</a></li>
            <li class="nav-item"><a class="nav-link {{request()->segment(4) == 'electrical' ? 'active' : ''}}" id="contact-icon-tab" href="/project/{{$project->id}}/discipline/electrical" role="tab" aria-controls="contact-icon" aria-selected="false"><i class="icofont icofont-contacts"></i>Electrical</a></li>
            <li class="nav-item"><a class="nav-link {{request()->segment(4) == 'instrument' ? 'active' : ''}}" id="contact-icon-tab" href="/project/{{$project->id}}/discipline/instrument" role="tab" aria-controls="contact-icon" aria-selected="false"><i class="icofont icofont-contacts"></i>Instrument</a></li>
        </ul>
    </div>
    <div class="table-responsive table-striped">
        <table class="table table-bordered">
            <thead class="bg-primary">
                <tr>
                    <th style="vertical-align : middle;" rowspan="2" class="text-center th-lg">Work Element</th>
                    <th scope="col" style="vertical-align : middle;" rowspan="2" class="text-center">Work Item</th>
                    <th scope="col" style="vertical-align : middle;" rowspan="2" class="text-center">Vol</th>
                    <th scope="col" style="vertical-align : middle;" rowspan="2" class="text-center">Unit</th>
                    <th scope="col" style="vertical-align : middle;" colspan="2" class="text-center">Labour Cost</th>
                    <th scope="col" style="vertical-align : middle;" colspan="2" class="text-center">Tool & Equipment</th>
                    <th scope="col" style="vertical-align : middle;" colspan="2" class="text-center">Material Cost</th>
                    <th scope="col" style="vertical-align : middle;" rowspan="2" class="text-center">Total Work Cost</th>
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
            @php ($totalCost = array())
            @foreach($estimateAllDisciplines as $key => $value)
                @php ($total = $workItemController->getTotalCost($value,'man_power',false) +
                        $workItemController->getTotalCost($value,'tool_equipments',false) +
                        $workItemController->getTotalCost($value,'materials',false))
                @php($totalCost[] = $total)
                <tr>
                    <td colspan="1">{{$key}}</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>{{$workItemController->getTotalCost($value,'man_power',true)}}</td>
                    <td></td>
                    <td>{{$workItemController->getTotalCost($value,'tool_equipments',true)}}</td>
                    <td></td>
                    <td>{{$workItemController->getTotalCost($value,'materials',true)}}</td>
                    <td>
                        {{$workItemController->toCurrency($total)}}
                    </td>
                </tr>
                @foreach($value as $item)
                    <tr>
                        <td>{{$item?->workItems?->code}}</td>
                        <td>{{$item?->workItems?->description}}</td>
                        <td>{{$item?->volume}}</td>
                        <td>{{$item?->workItems?->unit}}</td>
                        <td>{{$workItemController->toCurrency($item?->workItems?->manPowers()->sum('amount'))}}</td>
                        <td>{{$workItemController->toCurrency($item?->volume * $item?->workItems?->manPowers()->sum('amount'))}}</td>
                        <td>{{$workItemController->toCurrency($workItemController->getTotalAmountToolsEquipment($item?->workItems?->equipmentTools))}}</td>
                        <td>{{$workItemController->toCurrency($workItemController->getTotalAmountToolsEquipment($item?->workItems?->equipmentTools) * $item?->volume) }}</td>
                        <td>{{$workItemController->toCurrency($workItemController->getTotalAmountMaterials($item?->workItems?->materials)) }}</td>
                        <td>{{$workItemController->toCurrency($workItemController->getTotalAmountMaterials($item?->workItems?->materials) * $item?->volume)}}</td>
                        <td></td>
                    </tr>
                @endforeach
            @endforeach
            <tr class="font-weight-bold" style="background-color: #c4bd97">
                <td colspan="10">
                    Total
                </td>
                <td>
                    {{$workItemController->toCurrency(array_sum($totalCost))}}
                </td>
            </tr>
            </tbody>
        </table>
    </div>
</div>

