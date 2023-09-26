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
    </tr>
    </thead>
    <tbody>
    @foreach($workItem as $mp)
        <tr>
            <td style="border: 1px solid #000000; {!! $mp->numOfManPower > 0 ? 'background-color: #d9d9d9' : '' !!}">{{$mp->code}}</td>
            <td style="border: 1px solid #000000; {!! $mp->numOfManPower > 0 ? 'background-color: #d9d9d9' : '' !!}">{{$mp->workItemDescription}}</td>
            <td style="border: 1px solid #000000; {!! $mp->numOfManPower > 0 ? 'background-color: #d9d9d9' : '' !!}">{{$mp->workItemType}}</td>
            <td style="border: 1px solid #000000; {!! $mp->numOfManPower > 0 ? 'background-color: #d9d9d9' : '' !!}">{{$mp->volume}}</td>
            <td style="border: 1px solid #000000; {!! $mp->numOfManPower > 0 ? 'background-color: #d9d9d9' : '' !!}">{{$mp->unit}}</td>
            <td style="border: 1px solid #000000; {!! $mp->numOfManPower > 0 ? 'background-color: #d9d9d9' : '' !!}"></td>
            <td style="border: 1px solid #000000; {!! $mp->numOfManPower > 0 ? 'background-color: #d9d9d9' : '' !!}"></td>
            <td style="border: 1px solid #000000; {!! $mp->numOfManPower > 0 ? 'background-color: #d9d9d9' : '' !!}"></td>
            <td style="border: 1px solid #000000; {!! $mp->numOfManPower > 0 ? 'background-color: #d9d9d9' : '' !!}"></td>
            <td style="border: 1px solid #000000; {!! $mp->numOfManPower > 0 ? 'background-color: #d9d9d9' : '' !!}"></td>
            <td style="border: 1px solid #000000; {!! $mp->numOfManPower > 0 ? 'background-color: #d9d9d9' : '' !!}"></td>
            <td style="border: 1px solid #000000; {!! $mp->numOfManPower > 0 ? 'background-color: #d9d9d9' : '' !!}"></td>
            <td style="border: 1px solid #000000; {!! $mp->numOfManPower > 0 ? 'background-color: #d9d9d9' : '' !!}"></td>
            <td style="border: 1px solid #000000; {!! $mp->numOfManPower > 0 ? 'background-color: #d9d9d9' : '' !!}"></td>
            <td style="border: 1px solid #000000; {!! $mp->numOfManPower > 0 ? 'background-color: #d9d9d9' : '' !!}"></td>
        </tr>
        @foreach($mp->manPowerList as $m)
            <tr>
                <td style="border: 1px solid #000000;"></td>
                <td style="border: 1px solid #000000;"></td>
                <td style="border: 1px solid #000000;"></td>
                <td style="border: 1px solid #000000;"></td>
                <td style="border: 1px solid #000000;"></td>
                <td style="border: 1px solid #000000;">{{$m?->description}}</td>
                <td style="border: 1px solid #000000;">{{$m?->unit}}</td>
                <td style="border: 1px solid #000000;">{{str_replace(',','.',$m?->coef)}}</td>
                <td style="border: 1px solid #000000;">{{$m->unit_price}}</td>
                <td style="border: 1px solid #000000;">{{$m?->amount}}</td>
                <td style="border: 1px solid #000000;">{{$m?->amount}}</td>
                <td style="border: 1px solid #000000;">{{$m?->amount}}</td>
                <td style="border: 1px solid #000000;">{{$m?->amount}}</td>
            </tr>
        @endforeach
    @endforeach
    </tbody>
</table>
