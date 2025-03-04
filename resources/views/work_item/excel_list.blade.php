
<table>
    <tr>
        <td colspan="5">PT VALE INDONESIA, TBK</td>
    </tr>
    <tr>
        <td colspan="5">
            DEPARTMENT ENGINEERING AND CONSTRUCTION - ENGINEERING SERVICE
        </td>
    </tr>
    <tr></tr>
    <tr>
        <td colspan="11">
            PTVI STANDARD DETAILED DATABASE COST ESTIMATE
        </td>
    </tr>
    <thead>
    <tr>
        <th rowspan="2" style="background-color: #FFC000">WORK ITEM</th>
        <th rowspan="2" style="background-color: #FFC000">WORK DESCRIPTION</th>
        <th rowspan="2" style="background-color: #FFC000">WORK ITEM TYPE</th>
        <th rowspan="2" style="background-color: #FFC000">VOLUME</th>
        <th rowspan="2" style="background-color: #FFC000">UNIT</th>
        <th colspan="5" style="background-color: #FFC000">LABOR</th>
        <th colspan="6" style="background-color: #FFC000">TOOLS AND EQUIPMENT</th>
        <th colspan="5" style="background-color: #FFC000">MATERIAL</th>
        <th rowspan="2" style="background-color: #FFC000">STATUS</th>
        <th rowspan="2" style="background-color: #FFC000">CREATED BY</th>
    </tr>
    <tr>
        <th style="background-color: #FFC000">Description</th>
        <th style="background-color: #FFC000">Unit</th>
        <th style="background-color: #FFC000">Coef</th>
        <th style="background-color: #FFC000">Rate</th>
        <th style="background-color: #FFC000">Amount</th>

        <th style="background-color: #FFC000">Code</th>
        <th style="background-color: #FFC000">Description</th>
        <th style="background-color: #FFC000">Unit</th>
        <th style="background-color: #FFC000">Qty</th>
        <th style="background-color: #FFC000">Unit Price</th>
        <th style="background-color: #FFC000">Amount</th>

        <th style="background-color: #FFC000">Description</th>
        <th style="background-color: #FFC000">Unit</th>
        <th style="background-color: #FFC000">Qty</th>
        <th style="background-color: #FFC000">Unit Price</th>
        <th style="background-color: #FFC000">Amount</th>
    </tr>
    </thead>
    <tbody>
    @foreach($workItem as $mp)
        @php($max = max($mp->numOfManPower, $mp->numOfEquipment, $mp->numOfMaterial))
        @php($isNotEmpty = $mp->numOfManPower > 0 || $mp->numOfMaterial > 0 || $mp->numOfEquipment > 0)
        <tr>
            <td style="border: 1px solid #000000; {!! $isNotEmpty ? 'background-color: #d9d9d9' : '' !!}">{{$mp->code}}</td>
            <td style="border: 1px solid #000000; {!! $isNotEmpty ? 'background-color: #d9d9d9' : '' !!}">{{$mp->workItemDescription}}</td>
            <td style="border: 1px solid #000000; {!! $isNotEmpty ? 'background-color: #d9d9d9' : '' !!}">{{$mp->workItemType}}</td>
            <td style="border: 1px solid #000000; {!! $isNotEmpty ? 'background-color: #d9d9d9' : '' !!}">{{$mp->volume}}</td>
            <td style="border: 1px solid #000000; {!! $isNotEmpty ? 'background-color: #d9d9d9' : '' !!}">{{$mp->unit}}</td>
            <td style="border: 1px solid #000000; {!! $isNotEmpty ? 'background-color: #d9d9d9' : '' !!}"></td>
            <td style="border: 1px solid #000000; {!! $isNotEmpty ? 'background-color: #d9d9d9' : '' !!}"></td>
            <td style="border: 1px solid #000000; {!! $isNotEmpty ? 'background-color: #d9d9d9' : '' !!}"></td>
            <td style="border: 1px solid #000000; {!! $isNotEmpty ? 'background-color: #d9d9d9' : '' !!}"></td>
            <td style="border: 1px solid #000000; {!! $isNotEmpty ? 'background-color: #d9d9d9' : '' !!}"></td>
            <td style="border: 1px solid #000000; {!! $isNotEmpty ? 'background-color: #d9d9d9' : '' !!}"></td>
            <td style="border: 1px solid #000000; {!! $isNotEmpty ? 'background-color: #d9d9d9' : '' !!}"></td>
            <td style="border: 1px solid #000000; {!! $isNotEmpty ? 'background-color: #d9d9d9' : '' !!}"></td>
            <td style="border: 1px solid #000000; {!! $isNotEmpty ? 'background-color: #d9d9d9' : '' !!}"></td>
            <td style="border: 1px solid #000000; {!! $isNotEmpty ? 'background-color: #d9d9d9' : '' !!}"></td>
            <td style="border: 1px solid #000000; {!! $isNotEmpty ? 'background-color: #d9d9d9' : '' !!}"></td>
            <td style="border: 1px solid #000000; {!! $isNotEmpty ? 'background-color: #d9d9d9' : '' !!}"></td>
            <td style="border: 1px solid #000000; {!! $isNotEmpty ? 'background-color: #d9d9d9' : '' !!}"></td>
            <td style="border: 1px solid #000000; {!! $isNotEmpty ? 'background-color: #d9d9d9' : '' !!}"></td>
            <td style="border: 1px solid #000000; {!! $isNotEmpty ? 'background-color: #d9d9d9' : '' !!}"></td>
            <td style="border: 1px solid #000000; {!! $isNotEmpty ? 'background-color: #d9d9d9' : '' !!}"></td>
            <td style="border: 1px solid #000000; {!! $isNotEmpty ? 'background-color: #d9d9d9' : '' !!}">{{$mp->status}}</td>
            <td style="border: 1px solid #000000; {!! $isNotEmpty ? 'background-color: #d9d9d9' : '' !!}">{{$mp->createdBy}}</td>
        </tr>
        @for($i=0; $i<$max; $i++)
            <tr>
                <td style="border: 1px solid #000000;"></td>
                <td style="border: 1px solid #000000;"></td>
                <td style="border: 1px solid #000000;"></td>
                <td style="border: 1px solid #000000;"></td>
                <td style="border: 1px solid #000000;"></td>
                <td style="border: 1px solid #000000;">{{isset($mp->manPowerList[$i]) ? $mp->manPowerList[$i]?->description : ''}}</td>
                <td style="border: 1px solid #000000;">{{isset($mp->manPowerList[$i]) ? $mp->manPowerList[$i]?->unit : ''}}</td>
                <td style="border: 1px solid #000000;">{{isset($mp->manPowerList[$i]) ? str_replace(',','.', $mp->manPowerList[$i]?->coef) : ''}}</td>
                <td style="border: 1px solid #000000;">{{isset($mp->manPowerList[$i]) ? $mp->manPowerList[$i]?->unit_price : ''}}</td>
                <td style="border: 1px solid #000000;">{{isset($mp->manPowerList[$i]) ? $mp->manPowerList[$i]?->amount: ''}}</td>
                <td style="border: 1px solid #000000;">-</td>
                <td style="border: 1px solid #000000;">{{isset($mp->equipmentToolList[$i]) ? $mp->equipmentToolList[$i]?->description : ''}}</td>
                <td style="border: 1px solid #000000;">{{isset($mp->equipmentToolList[$i]) ? $mp->equipmentToolList[$i]?->unit : ''}}</td>
                <td style="border: 1px solid #000000;">{{isset($mp->equipmentToolList[$i]) ? $mp->equipmentToolList[$i]?->quantity : ''}}</td>
                <td style="border: 1px solid #000000;">{{isset($mp->equipmentToolList[$i]) ? $mp->equipmentToolList[$i]?->unit_price : ''}}</td>
                <td style="border: 1px solid #000000;">{{isset($mp->equipmentToolList[$i]) ? $mp->equipmentToolList[$i]?->amount : ''}}</td>
                <td style="border: 1px solid #000000;">{{isset($mp->materialList[$i]) ? $mp->materialList[$i]?->description : ''}}</td>
                <td style="border: 1px solid #000000;">{{isset($mp->materialList[$i]) ? $mp->materialList[$i]?->unit : ''}}</td>
                <td style="border: 1px solid #000000;">{{isset($mp->materialList[$i]) ? $mp->materialList[$i]?->quantity : ''}}</td>
                <td style="border: 1px solid #000000;">{{isset($mp->materialList[$i]) ? $mp->materialList[$i]?->unit_price : ''}}</td>
                <td style="border: 1px solid #000000;">{{isset($mp->materialList[$i]) ? $mp->materialList[$i]?->amount : ''}}</td>
                <td style="border: 1px solid #000000;"></td>
                <td style="border: 1px solid #000000;"></td>
            </tr>
        @endfor
    @endforeach
    </tbody>
</table>
