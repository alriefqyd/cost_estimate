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
                                <button class="btn btn-outline-success float-end m-r-10">Edit Project Info</button>
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
                            <p class="font-weight-600-height-7">Design Engineer - Civil/Structure : {{$project?->designEngineerCivil?->name}}</p>
                            <p class="font-weight-600-height-7">Design Engineer - Mechanical : {{$project?->designEngineerMechanical?->name}}</p>
                            <p class="font-weight-600-height-7">Design Engineer - Electrical : {{$project?->designEngineerElectrical?->name}} </p>
                            <p class="font-weight-600-height-7">Design Engineer - Instrument : {{$project?->designEngineerInstrument?->name}}</p>
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
                                <a href="/project/{{$project->id}}/wbs/{{sizeof($wbs) > 0 ? 'edit' : 'create'}}">
                                    <button class="btn btn-outline-primary float-end m-r-10" type="button">
                                        {{sizeof($wbs) > 0 ? 'Edit WBS' : 'Create WBS'}}
                                    </button>
                                </a>
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
                                <a href="/project/{{$project->id}}/work-item/create">
                                    <button class="btn btn-outline-primary float-end m-r-10" type="button">
                                        {{sizeof($estimateAllDisciplines) > 0 ? 'Edit Data' : 'Add New Data'}}
                                    </button>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        @include('project.detail_table')
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="default-according" id="accordion2">
                        <div class="card">
                            <div class="card-header bg-light-primary" id="headingnine">
                                <h5 class="mb-0">
                                    <button class="btn btn-link collapsed text-black" data-bs-toggle="collapse" data-bs-target="#collapsenine" aria-expanded="false" aria-controls="collapsenine">Cost Estimate Summary</button>
                                </h5>
                            </div>
                            <div class="collapse" id="collapsenine" aria-labelledby="headingnine" data-bs-parent="#accordion2">
                                <div class="row m-4">
                                    <div class="col-md-12 mb-4">
                                        <button class="btn btn-success float-end"><i class="fa fa-download"></i> Download as xlsx</button>
                                    </div>
                                    @include('project.summary_table_cost_estimate')
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{--<div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="default-according" id="accordion2">
                        <div class="card">
                            <div class="card-header" id="headingnine">
                                <h5 class="mb-0">
                                    <button class="btn btn-link collapsed text-black" data-bs-toggle="collapse" data-bs-target="#collapsenine" aria-expanded="false" aria-controls="collapsenine">Cost Estimate History</button>
                                </h5>
                            </div>
                            <div class="collapse" id="collapsenine" aria-labelledby="headingnine" data-bs-parent="#accordion2">
                                <div class="card-body">Anim pariatur cliche reprehenderit, enim eiusmod high life accusamus terry richardson ad squid. 3 wolf moon officia aute, non cupidatat skateboard dolor brunch. Food truck quinoa nesciunt laborum eiusmod. Brunch 3 wolf moon tempor, sunt aliqua put a bird on it squid single-origin coffee nulla assumenda shoreditch et. Nihil anim keffiyeh helvetica, craft beer labore wes anderson cred nesciunt sapiente ea proident. Ad vegan excepteur butcher vice lomo. Leggings occaecat craft beer farm-to-table, raw denim aesthetic synth nesciunt you probably haven't heard of them accusamus labore sustainable VHS.</div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>--}}
        </div>
    </div>
@endsection
<!-- Container-fluid Ends-->
