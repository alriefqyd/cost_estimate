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
        <td colspan="10">
            ES STANDARD COST ESTIMATE - TOOLS EQUIPMENT LIST
        </td>
    </tr>
    <thead>
    <tr>
        <th rowspan="2" style="background-color: #FFC000">NO</th>
        <th rowspan="2" style="background-color: #FFC000">CODE</th>
        <th rowspan="2" style="background-color: #FFC000">MATERIAL DESCRIPTION</th>
        <th rowspan="2" style="background-color: #FFC000">MATERIAL CATEGORY</th>
        <th rowspan="2" style="background-color: #FFC000">QTY</th>
        <th rowspan="2" style="background-color: #FFC000">UNIT</th>
        <th rowspan="2" style="background-color: #FFC000">LOCAL RATE</th>
        <th rowspan="2" style="background-color: #FFC000">NATIONAL RATE</th>
        <th rowspan="2" style="background-color: #FFC000">LAST UPDATED DATE</th>
        <th rowspan="2" style="background-color: #FFC000">REMARK</th>
    </tr>
    </thead>
    <tbody>
    <tr></tr>
    @foreach($toolsEquipment as $mp)
        <tr>
            <td>{{$loop->iteration}}</td>
            <td>{{$mp->code}}</td>
            <td>{{$mp->description}}</td>
            <td>{{$mp->equipmentToolsCategory?->description}}</td>
            <td>{{$mp->quantity}}</td>
            <td>{{$mp->unit}}</td>
            <td>{{floatval($mp->local_rate)}}</td>
            <td>{{floatval($mp->national_rate)}}</td>
            <td>{{$mp->updated_at ? $mp->updated_at->format('d-M-Y') : ''}}</td>
            <td>{{$mp->remark}}</td>
        </tr>
    @endforeach


    </tbody>
</table>
