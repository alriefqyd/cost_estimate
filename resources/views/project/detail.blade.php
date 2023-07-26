@inject('projectModel', App\Models\Project::class)
@extends('layouts.main')
@section('main')

    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-6">
                    <h4>Project Detail</h4>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">Project</a></li>
                        <li class="breadcrumb-item active">Project Detail</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- Container-fluid starts-->
    <div class="container-fluid">
        <div class="row">
            @if(session('message'))
                @include('flash')
            @endif
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header pb-0 card-header-custom">
                        <div class="row">
                            <div class="col-md-6">
                                <p class="float start">Project Info
                                <i class="fa fa-chevron-circle-up cursor-pointer js-chev-hide-content"></i>
                                <i class="fa fa-chevron-circle-down cursor-pointer js-chev-show-content d-none"></i>
                                </p>
                            </div>
                            @can('update',$project)
                                <div class="col-md-6">
                                    <a href="/project/edit/{{$project->id}}">
                                        <button class="btn btn-outline-success float-end m-r-10">Edit Project Info</button>
                                    </a>
                                </div>
                            @endcan
                        </div>
                    </div>
                    <div class="card-body card-body-custom">
                        <div class="col-sm-10 float-start" style="margin-left: 20px!important">
                            <p class="font-weight-600-height-7">Date : {{$project_date}} </p>
                            <p class="font-weight-600-height-7">Project No : {{$project->project_no}} </p>
                            <p class="font-weight-600-height-7">Project Title : {{$project->project_title}} </p>
                            <div class="linebreak"></div>
                            <p class="font-weight-600-height-7">Project Sponsor : {{$project->project_sponsor}} </p>
                            <p class="font-weight-600-height-7">Project Manager : {{$project->project_manager}}</p>
                            <p class="font-weight-600-height-7">Project Engineer : {{$project->project_engineer}} </p>
                            <div class="linebreak"></div>
                            <p class="font-weight-600-height-7">Design Engineer - Civil/Structure : {{$project?->designEngineerCivil?->profiles?->full_name}}</p>
                            <p class="font-weight-600-height-7">Design Engineer - Mechanical : {{$project?->designEngineerMechanical?->profiles?->full_name}}</p>
                            <p class="font-weight-600-height-7">Design Engineer - Electrical : {{$project?->designEngineerElectrical?->profiles?->full_name}} </p>
                            <p class="font-weight-600-height-7">Design Engineer - Instrument : {{$project?->designEngineerInstrument?->profiles?->full_name}}</p>
                            <div class="linebreak"></div>
                            <p class="font-weight-600-height-7">Status : <span class="js-detail-status">{{$project->status}}</span></p>
                            @if($project->status !== $projectModel::APPROVE && $project->isReviewer())
                                <button class="btn btn-outline-success js-btn-approve-modal" data-bs-toggle="modal" data-original-title="test" data-bs-target="#approveModal"><i class="fa fa-check"></i> Approve</button>

                                <div class="modal fade" id="approveModal" tabindex="-1" role="dialog" aria-labelledby="approveModalLabel" aria-hidden="true">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalLabel">Approve Cost Estimate</h5>
                                                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">Are you sure you want to approve this cost estimate?</div>
                                            <div class="modal-footer">
                                                <button class="btn btn-danger" type="button" data-bs-dismiss="modal">Close</button>
                                                <button class="btn btn-success js-btn-approve-cost-estimate" type="button">Approve</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header card-header-custom pb-0">
                        <div class="row">
                            <div class="col-md-6">
                                <p class="float start">Work Breakdown Structure
                                    <i class="fa fa-chevron-circle-up cursor-pointer js-chev-hide-content"></i>
                                    <i class="fa fa-chevron-circle-down cursor-pointer js-chev-show-content d-none"></i>
                                </p>
                            </div>
                            <div class="col-md-6">
                                @if(sizeof($wbs) > 0)
                                    @can('update',App\Models\WbsLevel3::class)
                                        <a href="/project/{{$project->id}}/wbs/edit">
                                            <button class="btn btn-outline-primary float-end m-r-10" type="button">
                                                Edit WBS
                                            </button>
                                        </a>
                                    @endcan
                                @else
                                    @can('create', App\Models\WbsLevel3::class)
                                        <a href="/project/{{$project->id}}/wbs/create">
                                            <button class="btn btn-outline-primary float-end m-r-10" type="button">
                                                Create WBS
                                            </button>
                                        </a>
                                    @endcan
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        @if(sizeof($wbs) < 1)
                            <div class="col-lg-12 text-center">
                                <p >There is no data to display</p>
                            </div>
                        @else
                        <div class="col-lg-12 text-center">
                            <div class="table-responsive table-striped">
                                <table class="table table-bordered">
                                    <thead>
                                    <tr>
                                        <td style="width:25%">Location/Equipment</td>
                                        <td style="width:25%">Discipline</td>
                                        <td style="width:50%">Work Element</td>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($wbs as $key=>$value)
                                        <tr>
                                            <td>{{$key}}</td>
                                            <td></td>
                                            <td></td>
                                        </tr>
                                        @foreach($value as $wbs)
                                            <tr>
                                                <td>
                                                </td>
                                                <td>
                                                    {{$wbs->wbsDiscipline?->title}}
                                                </td>
                                                <td>{{$wbs->workElements?->title}}</td>
                                            </tr>
                                        @endforeach
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="card">
                    <div class="card-header card-header-custom pb-0" >
                        <div class="row">
                            <div class="col-md-6">
                                <p class="float start">Estimate All Discipline
                                    <i class="fa fa-chevron-circle-up cursor-pointer js-chev-hide-content"></i>
                                    <i class="fa fa-chevron-circle-down cursor-pointer js-chev-show-content d-none"></i>
                                </p>
                            </div>
                            <div class="col-md-6">
                                @canAny(['create','update'], App\Models\EstimateAllDiscipline::class)
                                    <a href="/project/{{$project->id}}/estimate-discipline/create">
                                        <button class="btn btn-outline-primary float-end m-r-10" type="button">
                                            {{sizeof($estimateAllDisciplines) > 0 ? 'Edit Data' : 'Add New Data'}}
                                        </button>
                                    </a>
                                @endcan
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="col-sm-12 col-lg-12 col-xl-12">
                            <div class="clearfix"></div>
                            @if(sizeof($estimateAllDisciplines) > 0)
                                <a href="/cost-estimate-summary/export/{{$project->id}}">
                                    <button data-id="{{$project->id}}"
                                        data-name="Cost Estimate - {{$project->project_no}} - {{$project->project_title}}.xlsx"
                                        class="js-download-summary-xlsx btn btn-success mb-3">
                                        <div class="float-start">Download XLSX</div>
                                        <div class="loader-box float-end d-none" style="height: 0px; width: 20px; margin-top: 9%">
                                            <div class="loader-34"></div>
                                        </div>
                                    </button>
                                </a>
                            @endif
                            @include('project.detail_table')
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
<!-- Container-fluid Ends-->
