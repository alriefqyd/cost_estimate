@extends('layouts.main')
@section('main')
@inject('wbsLevel3Controller','App\Http\Controllers\WbsLevel3Controller')
    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-6">
                    <h3>Project Detail</h3>
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
                            <div class="col-md-6">
                                <a href="/project/edit/{{$project->id}}">
                                    <button class="btn btn-outline-success float-end m-r-10">Edit Project Info</button>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body card-body-custom">
                        <div class="col-sm-10 float-start">
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
                                    @can('update',App\Models\WorkBreakdownStructure::class)
                                        <a href="/project/{{$project->id}}/wbs/edit">
                                            <button class="btn btn-outline-primary float-end m-r-10" type="button">
                                                Edit WBS
                                            </button>
                                        </a>
                                    @endcan
                                @else
                                    @can('create', App\Models\WorkBreakdownStructure::class)
                                        <a href="/project/{{$project->id}}/wbs/create}}">
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
                                    <a href="/project/{{$project->id}}/work-item/create">
                                        <button class="btn btn-outline-primary float-end m-r-10" type="button">
                                            {{sizeof($estimateAllDisciplines) > 0 ? 'Edit Data' : 'Add New Data'}}
                                        </button>
                                    </a>
                                @endcan
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        @include('project.detail_table')
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
<!-- Container-fluid Ends-->
