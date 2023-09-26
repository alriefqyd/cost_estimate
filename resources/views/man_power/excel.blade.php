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
        <td colspan="13">
            ES STANDARD COST ESTIMATE - MANPOWER LIST
        </td>
    </tr>
    <thead>
        <tr>
            <th rowspan="2" style="background-color: #FFC000">NO</th>
            <th rowspan="2" style="background-color: #FFC000">CODE</th>
            <th rowspan="2" style="background-color: #FFC000">SKILL LEVEL</th>
            <th rowspan="2" style="background-color: #FFC000">TITLE</th>
            <th colspan="2" style="background-color: #FFC000">BASIC RATE</th>
            <th colspan="8" style="background-color: #FFC000">BENEFIT RATE</th>
            <th rowspan="2" style="background-color: #FFC000">Safety</th>
            <th rowspan="2" style="background-color: #FFC000">Total Benefit Hourly</th>
            <th rowspan="2" style="background-color: #FFC000">Overall Rate Hourly</th>
            <th rowspan="2" style="background-color: #FFC000">Monthly</th>
        </tr>
        <tr>
            <th style="background-color: #FFC000">Monthly</th>
            <th style="background-color: #FFC000">Hourly</th>
            <th style="background-color: #FFC000">General Allowance</th>
            <th style="background-color: #FFC000">BPJS</th>
            <th style="background-color: #FFC000">BPJS Kesehatan</th>
            <th style="background-color: #FFC000">THR</th>
            <th style="background-color: #FFC000">Public Holiday</th>
            <th style="background-color: #FFC000">Leave</th>
            <th style="background-color: #FFC000">Pesangon</th>
            <th style="background-color: #FFC000">Asuransi</th>
        </tr>
    </thead>
    @foreach($manPower as $mp)
        <tr>
            <td>{{$loop->iteration}}</td>
            <td>{{$mp->code}}</td>
            <td>{{$mp->getSkillLevel()}}</td>
            <td>{{$mp->title}}</td>
            <td>{{floatval($mp->basic_rate_month)}}</td>
            <td>{{floatval($mp->basic_rate_hour)}}</td>
            <td>{{floatval($mp->general_allowance)}}</td>
            <td>{{floatval($mp->bpjs)}}</td>
            <td>{{floatval($mp->bpjs_kesehatan)}}</td>
            <td>{{floatval($mp->thr)}}</td>
            <td>{{floatval($mp->public_holiday)}}</td>
            <td>{{floatval($mp->leave)}}</td>
            <td>{{floatval($mp->pesangon)}}</td>
            <td>{{floatval($mp->asuransi)}}</td>
            <td>{{floatval($mp->safety)}}</td>
            <td>{{floatval($mp->total_benefit_hourly)}}</td>
            <td>{{floatval($mp->overall_rate_hourly)}}</td>
            <td>{{floatval($mp->monthly)}}</td>
        </tr>
    @endforeach
</table>
