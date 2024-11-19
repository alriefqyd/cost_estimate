<div class="col-sm-12 col-lg-12 col-xl-12 p-0 m-0">
    <div class="table-responsive">
        <table class="table table-striped table-small-text">
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
                    <th scope="col" class="text-left min-w-135">
                        Civil  <i class="fa fa-sort cursor-pointer js-order-sort" data-sort="user_name"></i>
                    </th>
                    <th scope="col" class="text-left min-w-135">
                        Architect  <i class="fa fa-sort cursor-pointer js-order-sort" data-sort="user_name"></i>
                    </th>
                    <th scope="col" class="text-left min-w-135">
                        Electrical  <i class="fa fa-sort cursor-pointer js-order-sort" data-sort="user_name"></i>
                    </th>
                    <th scope="col" class="text-left min-w-140">
                        Instrument  <i class="fa fa-sort cursor-pointer js-order-sort" data-sort="user_name"></i>
                    </th>
                    <th scope="col" class="text-left min-w-140">
                        IT  <i class="fa fa-sort cursor-pointer js-order-sort" data-sort="user_name"></i>
                    </th>
                    <th scope="col" class="text-left min-w-100">
                        Total Cost  <i class="fa fa-sort cursor-pointer js-order-sort" data-sort="user_name"></i>
                    </th>
                    <th scope="col" class="text-left min-w-100">
                        Project Area  <i class="fa fa-sort cursor-pointer js-order-sort" data-sort="area"></i>
                    </th>
                    <th class="text-left min-w-100">
                        Status
                    </th>
                    @can('delete',App\Models\Project::class)
                        <th>Action</th>
                    @endcan
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
                    <td>{{$project->projectManager?->profiles?->full_name}}</td>
                    <td>{{$project->designEngineerMechanical?->profiles?->full_name ?? 'NR'}}</td>
                    <td>{{$project->designEngineerCivil?->profiles?->full_name ?? 'NR'}}</td>
                    <td>{{$project->designEngineerArchitect?->profiles?->full_name ?? 'NR'}}</td>
                    <td>{{$project->designEngineerElectrical?->profiles?->full_name ?? 'NR'}}</td>
                    <td>{{$project->designEngineerInstrument?->profiles?->full_name ?? 'NR'}}</td>
                    <td>{{$project->designEngineerIt?->profiles?->full_name ?? 'NR'}}</td>
                    <td>{{number_format($project->getTotalCostWithContingency(),2,',','.')}}</td>
                    <td>{{$project->projectArea?->name}}</td>
                    <td>{{$project->status}}</td>
                    @can('delete',App\Models\Project::class)
                        <td>
                            <a data-bs-toggle="modal" data-original-title="test" data-bs-target="#deleteConfirmationModal"
                                    data-id="{{$project->id}}" class="text-danger js-delete-project-modal">Delete</a>
                            <a data-bs-toggle="modal" data-original-title="test" data-bs-target="#duplicateProject"
                               data-id="{{$project->id}}" class="text-success js-duplicate-project-modal">Duplicate</a>
                        </td>
                    @endcan
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>

@can('delete',App\Models\Project::class)
<div class="modal fade js-modal-delete-project" id="deleteConfirmationModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Delete Project</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                Are you sure you want to delete this item?
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Close</button>
                <button class="btn btn-danger js-delete-project" type="button">Delete</button>
            </div>
        </div>
    </div>
</div>
@endcan

<div class="modal fade js-modal-duplicate-project" id="duplicateProject" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Duplicate Project</h5>
                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="col-md-12">
                    Are you sure you want to duplicate this item?
                    <input class="form-control js-duplicate_project_id" name="project_id" type="hidden"
                           autocomplete="off"
                           value="">
                </div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Close</button>
                <button class="btn btn-success js-duplicate-project" type="button">Duplicate</button>
            </div>
        </div>
    </div>
</div>
