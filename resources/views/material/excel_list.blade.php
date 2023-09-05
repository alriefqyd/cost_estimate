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
            ES STANDARD COST ESTIMATE - MATERIAL LIST
        </td>
    </tr>
    <thead>
        <tr>
            <th rowspan="2" style="background-color: #FFC000">NO</th>
            <th rowspan="2" style="background-color: #FFC000">CODE</th>
            <th rowspan="2" style="background-color: #FFC000">TOOL AND EQUIPMENT DESCRIPTION</th>
            <th rowspan="2" style="background-color: #FFC000">CATEGORY</th>
            <th rowspan="2" style="background-color: #FFC000">QUANTITY</th>
            <th rowspan="2" style="background-color: #FFC000">UNIT</th>
            <th rowspan="2" style="background-color: #FFC000">RATE</th>
            <th rowspan="2" style="background-color: #FFC000">REF. OR MATERIAL NUMBER</th>
            <th rowspan="2" style="background-color: #FFC000">LAST UPDATED DATE</th>
            <th rowspan="2" style="background-color: #FFC000">REMARK</th>
            <th rowspan="2" style="background-color: #FFC000">STOCK CODE</th>
        </tr>
    </thead>
    <tbody>
        <tr></tr>
        @foreach($materials as $mp)
            <tr>
                <td>{{$loop->iteration}}</td>
                <td>{{$mp->code}}</td>
                <td>{{$mp->tool_equipment_description}}</td>
                <td>{{$mp->materialsCategory?->description}}</td>
                <td>{{$mp->quantity}}</td>
                <td>{{$mp->unit}}</td>
                <td>{{floatval($mp->rate)}}</td>
                <td>{{$mp->ref_material_number}}</td>
                <td>{{$mp->last_updated}}</td>
                <td>{{$mp->remark}}</td>
                <td>{{$mp->stock_code}}</td>
            </tr>
        @endforeach
    </tbody>
</table>
