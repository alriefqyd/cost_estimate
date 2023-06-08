<div class="col-sm-12 col-lg-12 col-xl-12 p-0 m-0">
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col" class="text-left min-w-160">
                        Project Title <i class="fa fa-sort cursor-pointer js-order-sort" data-sort="user_name"></i>
                    </th>
                    <th scope="col" class="text-left min-w-135">
                        Project Sponsor  <i class="fa fa-sort cursor-pointer js-order-sort" data-sort="user_name"></i>
                    </th>
                    <th scope="col" class="text-left min-w-140">
                        Project Manager  <i class="fa fa-sort cursor-pointer js-order-sort" data-sort="user_name"></i>
                    </th>
                    <th scope="col" class="text-left min-w-140">
                        Mechanical  <i class="fa fa-sort cursor-pointer js-order-sort" data-sort="user_name"></i>
                    </th>
                    <th scope="col" class="text-left min-w-140">
                        Civil  <i class="fa fa-sort cursor-pointer js-order-sort" data-sort="user_name"></i>
                    </th>
                    <th scope="col" class="text-left min-w-140">
                        Electrical  <i class="fa fa-sort cursor-pointer js-order-sort" data-sort="user_name"></i>
                    </th>
                    <th scope="col" class="text-left min-w-140">
                        Instrument  <i class="fa fa-sort cursor-pointer js-order-sort" data-sort="user_name"></i>
                    </th>
                    <th scope="col" class="text-left min-w-100">
                        Total Cost  <i class="fa fa-sort cursor-pointer js-order-sort" data-sort="user_name"></i>
                    </th>
                    {{--<th scope="col" class="text-left min-w-50">Date</th>--}}
                </tr>
            </thead>
            <tbody>
            @foreach($projects as $project)
                <tr>
                    <td class="min-w-120">
                        <a href="/project/{{$project->id}}" class="font-weight-bold">{{$project->project_no}}</a>
                        <br> {{$project->project_title}}
                    </td>
                    <td>{{$project->project_sponsor}}</td>
                    <td>{{$project->project_manager}}</td>
                    <td>{{$project->designEngineerMechanical?->profiles?->full_name}}</td>
                    <td>{{$project->designEngineerCivil?->profiles?->full_name}}</td>
                    <td>{{$project->designEngineerElectrical?->profiles?->full_name}}</td>
                    <td>{{$project->designEngineerInstrument?->profiles?->full_name}}</td>
                    <td>{{number_format($project->getTotalWithContingencyCost(),2,',','.')}}</td>
                    {{--<td>{{$project?->created_at}}</td>--}}
                </tr>

            @endforeach
            </tbody>
        </table>
    </div>
</div>
