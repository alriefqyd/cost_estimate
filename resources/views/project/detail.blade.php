@inject('projectModel', App\Models\Project::class)
@inject('setting', App\Models\Setting::class)
@extends('layouts.main')
@section('main')

    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-6">
                    <h4>Project Detail</h4>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">Home</a></li>
                        <li class="breadcrumb-item"><a href="/project">Project List</a></li>
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
                            <table class="table">
                                <tr>
                                    <td>Date</td>
                                    <td>:</td>
                                    <td>{{$project_date}}</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>Project No</td>
                                    <td>:</td>
                                    <td class="m-2">{{$project->project_no}}</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>Project Title</td>
                                    <td>:</td>
                                    <td class="m-2">{{$project->project_title}}</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>Project Sponsor</td>
                                    <td>:</td>
                                    <td class="m-2">{{$project->project_sponsor}}</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>Project Manager</td>
                                    <td>:</td>
                                    <td class="m-2">{{$project->projectManager?->profiles?->full_name}}</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>Project Engineer</td>
                                    <td>:</td>
                                    <td class="m-2">{{$project->projectEngineer?->profiles?->full_name}}</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td>Project Area</td>
                                    <td>:</td>
                                    <td class="m-2">{{$project->projectArea?->name}}</td>
                                    <td></td>
                                </tr>
                                <tr>
                                    <td colspan="4">
                                        <p class="font-weight-bold" style="color: black"> Design Engineer </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Civil/Structure</td>
                                    <td>:</td>
                                    <td>
                                        <div class="mb-1">{{$project?->designEngineerCivil?->profiles?->full_name}}</div>
                                        {{-- <div class="mb-2 js-form-parent-approval">
                                            @if($project?->designEngineerCivil && auth()->user()->isDisciplineReviewer('civil'))
                                                <div class="col">
                                                    <div class="form-group m-t-15 m-checkbox-inline mb-0 custom-radio-ml">
                                                        <div class="radio radio-primary">
                                                            <input class="js-approve-discipline" id="radio_civil_pending"
                                                                   {{$project->civil_approval_status == $projectModel::PENDING ? 'checked' : ''}}
                                                                   data-discipline="{{$setting::DESIGN_ENGINEER_LIST['civil']}}"
                                                                   type="radio" name="civil_approval_status" value="{{$projectModel::PENDING}}">
                                                            <label class="mb-0 label-radio" for="radio_civil_pending">
                                                                {{$projectModel::PENDING}}
                                                            </label>
                                                        </div>
                                                        <div class="radio radio-primary">
                                                            <input class="js-approve-discipline" id="radio_civil_approve"
                                                                   {{$project->civil_approval_status == $projectModel::APPROVE_BY_DISCIPLINE_REVIEWER ? 'checked' : ''}}
                                                                   data-discipline="{{$setting::DESIGN_ENGINEER_LIST['civil']}}"
                                                                   type="radio" name="civil_approval_status" value="{{$projectModel::APPROVE_BY_DISCIPLINE_REVIEWER}}">
                                                            <label class="mb-0 label-radio" for="radio_civil_approve">
                                                                {{$projectModel::APPROVE}}
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col mt-3">
                                                    <textarea class="form-control js-remark-pending d-none"
                                                              data-discipline="{{$setting::DESIGN_ENGINEER_LIST['civil']}}"
                                                              rows="5" name="remark"></textarea>
                                                </div>
                                            @endif
                                        </div> --}}
                                    </td>
                                    <td>

                                    </td>
                                </tr>
                                <tr>
                                    <td>Mechanical</td>
                                    <td>:</td>
                                    <td>
                                        <div class="mb-2">{{$project?->designEngineerMechanical?->profiles?->full_name}}</div>
                                        {{-- <div class="js-form-parent-approval">
                                            @if($project?->designEngineerMechanical && auth()->user()->isDisciplineReviewer('mechanical'))
                                                <div class="col">
                                                    <div class="form-group m-t-15 m-checkbox-inline mb-0 custom-radio-ml">
                                                        <div class="radio radio-primary">
                                                            <input class="js-approve-discipline" id="radio_mechanical_pending"
                                                                   {{$project->mechanical_approval_status == $projectModel::PENDING ? 'checked' : ''}}
                                                                   data-discipline="{{$setting::DESIGN_ENGINEER_LIST['mechanical']}}"
                                                                   type="radio" name="mechanical_approval_status" value="{{$projectModel::PENDING}}">
                                                            <label class="mb-0 label-radio" for="radio_mechanical_pending">
                                                                {{$projectModel::PENDING}}
                                                            </label>
                                                        </div>
                                                        <div class="radio radio-primary">
                                                            <input class="js-approve-discipline" id="radio_mechanical_approve"
                                                                   {{$project->mechanical_approval_status == $projectModel::APPROVE_BY_DISCIPLINE_REVIEWER ? 'checked' : ''}}
                                                                   data-discipline="{{$setting::DESIGN_ENGINEER_LIST['mechanical']}}"
                                                                   type="radio" name="mechanical_approval_status" value="{{$projectModel::APPROVE_BY_DISCIPLINE_REVIEWER}}">
                                                            <label class="mb-0 label-radio" for="radio_mechanical_approve">
                                                                {{$projectModel::APPROVE}}
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="col mt-3">
                                                        <textarea class="form-control js-remark-pending d-none"
                                                                  data-discipline="{{$setting::DESIGN_ENGINEER_LIST['mechanical']}}"
                                                                  rows="5" name="remark"></textarea>
                                                    </div>
                                                </div>
                                            @endif
                                        </div> --}}
                                    </td>
                                    <td>

                                    </td>
                                </tr>
                                <tr>
                                    <td>Electrical</td>
                                    <td>:</td>
                                    <td>
                                        <div class="mb-2">
                                            {{$project?->designEngineerElectrical?->profiles?->full_name}}
                                        </div>
                                        {{--<div class="js-form-parent-approval">
                                            @if($project?->designEngineerElectrical && auth()->user()->isDisciplineReviewer('electrical'))
                                                <div class="col">
                                                    <div class="form-group m-t-15 m-checkbox-inline mb-0 custom-radio-ml">
                                                        <div class="radio radio-primary">
                                                            <input class="js-approve-discipline" id="radio_electrical_pending"
                                                                   data-discipline="{{$setting::DESIGN_ENGINEER_LIST['electrical']}}"
                                                                   {{$project->electrical_approval_status == $projectModel::PENDING ? 'checked' : ''}}
                                                                   type="radio" name="electrical_approval_status" value="{{$projectModel::PENDING}}">
                                                            <label class="mb-0 label-radio" for="radio_electrical_pending">
                                                                {{$projectModel::PENDING}}
                                                            </label>
                                                        </div>
                                                        <div class="radio radio-primary">
                                                            <input class="js-approve-discipline" id="radio_electrical_approve"
                                                                   data-discipline="{{$setting::DESIGN_ENGINEER_LIST['electrical']}}"
                                                                   {{$project->electrical_approval_status == $projectModel::APPROVE_BY_DISCIPLINE_REVIEWER ? 'checked' : ''}}
                                                                   type="radio" name="electrical_approval_status" value="{{$projectModel::APPROVE_BY_DISCIPLINE_REVIEWER}}">
                                                            <label class="mb-0 label-radio" for="radio_electrical_approve">
                                                                {{$projectModel::APPROVE}}
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="col">
                                                    <div class="col mt-3">
                                                        <textarea class="form-control js-remark-pending d-none"
                                                                  data-discipline="{{$setting::DESIGN_ENGINEER_LIST['mechanical']}}"
                                                                  rows="5" name="remark"></textarea>
                                                    </div>
                                                </div>
                                            @endif
                                        </div> --}}
                                    </td>
                                    <td>

                                    </td>
                                </tr>
                                <tr>
                                    <td>Instrument</td>
                                    <td>:</td>
                                    <td>
                                        <div class="mb-2">{{$project?->designEngineerInstrument?->profiles?->full_name}}</div>
                                        {{--<div class="js-form-parent-approval">
                                            @if($project?->designEngineerInstrument && auth()->user()->isDisciplineReviewer('instrument'))
                                                <div class="col">
                                                    <div class="form-group m-t-15 m-checkbox-inline mb-0 custom-radio-ml">
                                                        <div class="radio radio-primary">
                                                            <input class="js-approve-discipline" id="radio_instrument_pending"
                                                                   {{$project->instrument_approval_status == $projectModel::PENDING ? 'checked' : ''}}
                                                                   data-discipline="{{$setting::DESIGN_ENGINEER_LIST['instrument']}}"
                                                                   type="radio" name="radio1" value="{{$projectModel::PENDING}}">
                                                            <label class="mb-0 label-radio" for="radio_instrument_pending">
                                                                {{$projectModel::PENDING}}
                                                            </label>
                                                        </div>
                                                        <div class="radio radio-primary">
                                                            <input class="js-approve-discipline" id="radio_instrument_approve"
                                                                   {{$project->instrument_approval_status == $projectModel::APPROVE_BY_DISCIPLINE_REVIEWER ? 'checked' : ''}}
                                                                   data-discipline="{{$setting::DESIGN_ENGINEER_LIST['instrument']}}"
                                                                   type="radio" name="radio1" value="{{$projectModel::APPROVE_BY_DISCIPLINE_REVIEWER}}">
                                                            <label class="mb-0 label-radio" for="radio_instrument_approve">
                                                                {{$projectModel::APPROVE}}
                                                            </label>
                                                        </div>
                                                    </div>
                                                </div>
                                            @endif
                                        </div>--}}
                                    </td>
                                    <td>

                                    </td>
                                </tr>
                                <tr>
                                    <td>Status</td>
                                    <td>:</td>
                                    <td>
                                        <div class="mb-2 js-detail-status">{{$project->status}}</div>
                                        <div class="mb-2">
                                            @if($project->status != $projectModel::DRAFT)
                                                @foreach($project->getProjectDisciplineStatusApproval() as $status)
                                                    <li>{{$status}}</li>
                                                @endforeach
                                            @endif
                                        </div>
                                        {{-- @if($project->getProjectStatusApproval() == $projectModel::WAITING_FOR_APPROVAL
                                                && auth()->user()->isCostEstimateReviewer() && $project->status != $projectModel::APPROVE)
                                            <div>
                                                <button class="btn btn-outline-success js-btn-approve-modal" data-bs-toggle="modal" data-original-title="test" data-bs-target="#approveModal"><i class="fa fa-check"></i> Approve Cost Estimate</button>

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
                                            </div>
                                        @endif --}}
                                    </td>
                                    <td>

                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        Remark
                                    </td>
                                    <td>:</td>
                                    <td>
                                        <span class="js-remark"> {!! $project->remark ?? '-' !!}</span>
                                        @if(auth()->user()->isDisciplineReviewer(''))
                                            <i class="fa fa-edit cursor-pointer js-edit-remark-project-btn" data-bs-toggle="modal" data-bs-target=".bd-example-modal-lg"></i>
                                        @endif
                                    </td>
                                </tr>
                            </table>
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
                                                <td>{{$wbs->work_element}}</td>
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
                            <div class="col-md-6">
                                {{-- @if(sizeof($estimateAllDisciplines) > 0 && $project->status == $projectModel::APPROVE) --}}
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
                               {{-- @endif --}}
                            </div>
                            <div class="col-md-6 float-end">
                                <div class="btn btn-primary js-fullscreen-detail mb-2 float-end">Maximize Table <i data-feather="maximize" style="width: 12px !important;"></i></div>
                            </div>
                            <div class="clearfix"></div>
                            <span class="js-fullscreen-table">
                                @include('project.detail_table')
                            </span>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div data-id="{{$project->id}}" class="modal js-modal-approve-discipline fade" id="approveModalDiscipline" tabindex="-1" role="dialog" aria-labelledby="approveModalDiscipline" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Update Status By Discipline</h5>
                        <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">Are you sure you want to update status ?</div>
                    <div class="modal-footer">
                        <button class="btn btn-danger" type="button" data-bs-dismiss="modal">Close</button>
                        <button class="btn btn-success js-btn-approve-discipline-cost-estimate" type="button">Yes</button>
                    </div>
                </div>
            </div>
        </div>

        @if(auth()->user()->isDisciplineReviewer(''))
            <div class="modal fade bd-example-modal-lg js-modal-remark-project" tabindex="-1" role="dialog" aria-labelledby="myLargeModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h4 class="modal-title" id="myLargeModalLabel">
                                Remark
                            </h4>
                            <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="form-group">
                                <textarea class="form-control js-remark-project" rows="5" name="remark">{{$project->remark}}</textarea>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <div class="btn btn-outline-danger" data-bs-dismiss="modal">Cancel</div>
                            <div class="btn btn-outline-primary js-save-project-remark" data-id="{{$project->id}}">Save</div>
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
    </div>
@endsection
<!-- Container-fluid Ends-->
