<div class="col-sm-12 col-lg-12 col-xl-12 p-0 m-0">
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th scope="col" class="text-left">Project Title</th>
                    <th scope="col" class="text-left">Project Sponsor</th>
                    <th scope="col" class="text-left">Project Manager</th>
                    <th scope="col" class="text-left">Mechanical</th>
                    <th scope="col" class="text-left">Civil</th>
                    <th scope="col" class="text-left">Electrical</th>
                    <th scope="col" class="text-left">Instrument</th>
                    <th scope="col" class="text-left">Total Work Cost</th>
                    <th scope="col" class="text-left">Date</th>
                </tr>
            </thead>
            <tbody>
            @foreach($projects as $project)
                <tr>
                    <td>
                        <a href="/project/{{$project->id}}" class="font-weight-bold">{{$project->project_no}}</a>
                        <br> {{$project->project_title}}
                    </td>
                    <td>{{$project->project_sponsor}}</td>
                    <td>{{$project->project_manager}}</td>
                    <td>{{$project->designEngineerMechanical?->profiles?->full_name}}</td>
                    <td>{{$project->designEngineerCivil?->profiles?->full_name}}</td>
                    <td>{{$project->designEngineerElectrical?->profiles?->full_name}}</td>
                    <td>{{$project->designEngineerInstrument?->profiles?->full_name}}</td>
                    <td>238.784.878,8</td>
                    <td>{{$project?->created_at}}</td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
