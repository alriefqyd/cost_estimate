<table>
    <tr>
        <td colspan="3">PT VALE INDONESIA, TBK</td>
    </tr>
    <tr>
        <td colspan="3">
            DEPARTMENT ENGINEERING AND CONSTRUCTION - ENGINEERING SERVICE
        </td>
    </tr>
    <tr></tr>
    <tr>
        <td colspan="3">
            ES STANDARD COST ESTIMATE - TOOLS EQUIPMENT CATEGORY
        </td>
    </tr>
    <thead>
    <tr>
        <th rowspan="2" style="background-color: #FFC000">NO</th>
        <th rowspan="2" style="background-color: #FFC000">CODE</th>
        <th rowspan="2" style="background-color: #FFC000">DESCRIPTION</th>
    </tr>
    </thead>
    <tbody>
    <tr></tr>
    @foreach($category as $mp)
        <tr>
            <td>{{$loop->iteration}}</td>
            <td>{{$mp->code}}</td>
            <td>{{$mp->description}}</td>
        </tr>
    @endforeach


    </tbody>
</table>
