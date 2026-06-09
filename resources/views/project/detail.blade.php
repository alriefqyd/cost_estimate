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
                                    <a href="/project/edit/{{$project->id}}" class="{{$project->estimate_discipline_status == 'PUBLISH' ? 'd-none' : ''}}">
                                        <button class="btn btn-outline-success float-end m-r-10">Edit Project Info</button>
                                    </a>
                                </div>
                            @endcan
                        </div>
                    </div>
                    <div class="card-body card-body-custom">
                        <div class="col-sm-11 float-start" style="margin-left: 20px!important">
                            <input type="hidden" class="js-hidden-id-project" value="{{$project->id}}">
                            <table class="table">
                                <tr>
                                    <td class="max_width40_td">Date</td>
                                    <td>:</td>
                                    <td>{{$project_date}}</td>
                                </tr>
                                <tr>
                                    <td class="max_width40_td">Project No</td>
                                    <td>:</td>
                                    <td class="m-2">{{$project->project_no}}</td>
                                </tr>
                                <tr>
                                    <td class="max_width40_td">Project Title</td>
                                    <td>:</td>
                                    <td class="m-2" >{{$project->project_title}}</td>
                                </tr>
                                <tr>
                                    <td class="max_width40_td">Project Sponsor</td>
                                    <td>:</td>
                                    <td class="m-2">{{$project->project_sponsor}}</td>
                                </tr>
                                <tr>
                                    <td class="max_width40_td">Project Manager</td>
                                    <td>:</td>
                                    <td class="m-2">{{$project->projectManager?->profiles?->full_name}}</td>
                                </tr>
                                <tr>
                                    <td class="max_width40_td">Project Engineer</td>
                                    <td>:</td>
                                    <td class="m-2">{{$project->projectEngineer?->profiles?->full_name}}</td>
                                </tr>
                                <tr>
                                    <td class="max_width40_td">Project Area</td>
                                    <td>:</td>
                                    <td class="m-2">{{$project->projectArea?->name}}</td>
                                </tr>
                                <tr>
                                    <td class="max_width40_td">Status</td>
                                    <td>:</td>
                                    <td>
                                        <div class="mb-2 js-detail-status">{{$project->status}}</div>
{{--                                        @if($project->getProjectStatusApproval() == $projectModel::WAITING_FOR_APPROVAL--}}
{{--                                                && auth()->user()->isCostEstimateReviewer() && $project->status != $projectModel::APPROVE)--}}
{{--                                            <div>--}}
{{--                                                <button class="btn btn-outline-success js-btn-approve-modal" data-bs-toggle="modal" data-original-title="test" data-bs-target="#approveModal"><i class="fa fa-check"></i> Approve Cost Estimate</button>--}}

{{--                                                <div class="modal fade" id="approveModal" tabindex="-1" role="dialog" aria-labelledby="approveModalLabel" aria-hidden="true">--}}
{{--                                                    <div class="modal-dialog" role="document">--}}
{{--                                                        <div class="modal-content">--}}
{{--                                                            <div class="modal-header">--}}
{{--                                                                <h5 class="modal-title" id="exampleModalLabel">Approve Cost Estimate</h5>--}}
{{--                                                                <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>--}}
{{--                                                            </div>--}}
{{--                                                            <div class="modal-body">Are you sure you want to approve this cost estimate?</div>--}}
{{--                                                            <div class="modal-footer">--}}
{{--                                                                <button class="btn btn-danger" type="button" data-bs-dismiss="modal">Close</button>--}}
{{--                                                                <button class="btn btn-success js-btn-approve-cost-estimate" type="button">Approve</button>--}}
{{--                                                            </div>--}}
{{--                                                        </div>--}}
{{--                                                    </div>--}}
{{--                                                </div>--}}
{{--                                            </div>--}}
{{--                                        @endif--}}
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3">
                                        <p class="font-weight-bold" style="color: black"> Design Engineer </p>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <i data-feather="user-check" data-discipline="{{\App\Models\Setting::DESIGN_ENGINEER_LIST['civil']}}" {!! $isAuthorizeToReviewCivil && $project->getStatusEstimateDiscipline('design_engineer_civil') ? 'data-bs-toggle="modal" data-bs-target=".js-modal-approve-discipline"' : ''!!} class="{!! $isAuthorizeToReviewCivil && $project->getStatusEstimateDiscipline('design_engineer_civil') ? 'js-modal-approval cursor-pointer' : 'color-grey'!!}  m-r-10" style="width: 17px"></i>
                                        Civil
                                        @if(isset($project->design_engineer_civil))
                                            {!!$project->getStatusApprovalDiscipline($project->civil_approval_status, $project->getProfileUser($project->civil_approver)?->full_name) !!}
                                        @endif
                                    </td>
                                    <td>:</td>
                                    <td class="max_width50_td">
                                        {{$project?->designEngineerCivil?->profiles?->full_name  ?? 'NR'}}
                                        @if(isset($remark->civil) && $project->civil_approval_status == \App\Models\Project::REJECTED))
                                            <p class="f-12 m-t-5" data-text="{{$remark->civil}}"><b>Remark :</b> <span class="js-text-full-remark"> {{\Illuminate\Support\Str::limit($remark->civil ?? '', 180)}} </span> {!!\Illuminate\Support\Str::length($remark->civil ?? '') > 180 ? '<a class="js-full-text" href="">read more</a>' : ''!!}</p>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <i data-feather="user-check" data-discipline="{{\App\Models\Setting::DESIGN_ENGINEER_LIST['mechanical']}}" {!! $isAuthorizeToReviewMechanical && $project->getStatusEstimateDiscipline('design_engineer_mechanical') ? 'data-bs-toggle="modal" data-bs-target=".js-modal-approve-discipline"' : ''!!} class="{{$isAuthorizeToReviewMechanical && $project->getStatusEstimateDiscipline('design_engineer_mechanical')? 'js-modal-approval  cursor-pointer' : 'color-grey'}} m-r-10" style="width: 17px" ></i>
                                            Mechanical
                                        @if(isset($project->design_engineer_mechanical))
                                            {!! $project->getStatusApprovalDiscipline($project->mechanical_approval_status, $project->getProfileUser($project->mechanical_approver)?->full_name) !!}
                                        @endif

                                    </td>
                                    <td>:</td>
                                    <td class="max_width40_td">
                                        {{$project?->designEngineerMechanical?->profiles?->full_name  ?? 'NR'}}
                                        @if(isset($remark->mechanical) && $project->mechanical_approval_status == \App\Models\Project::REJECTED)
                                            <p class="f-12 m-t-5" data-text="{{$remark->mechanical}}"><b>Remark :</b> <span class="js-text-full-remark">{{\Illuminate\Support\Str::limit($remark->mechanical ?? '', 180)}} </span>{!!\Illuminate\Support\Str::length($remark->mechanical ?? '') > 180 ? '<a class="js-full-text" href="">read more</a>' : ''!!}</p>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <i data-feather="user-check" data-discipline="{{\App\Models\Setting::DESIGN_ENGINEER_LIST['architect']}}" {!! $isAuthorizeToReviewArchitect && $project->getStatusEstimateDiscipline('design_engineer_architect') ? 'data-bs-toggle="modal" data-bs-target=".js-modal-approve-discipline"' : ''!!} class="{{$isAuthorizeToReviewArchitect && $project->getStatusEstimateDiscipline('design_engineer_architect')? 'js-modal-approval  cursor-pointer' : 'color-grey'}} m-r-10" style="width: 17px" ></i>
                                        Architecture
                                        @if(isset($project->design_engineer_architect))
                                            {!! $project->getStatusApprovalDiscipline($project->architect_approval_status, $project->getProfileUser($project->architect_approver)?->full_name) !!}
                                        @endif

                                    </td>
                                    <td>:</td>
                                    <td class="max_width40_td">
                                        {{$project?->designEngineerArchitect?->profiles?->full_name  ?? 'NR'}}
                                        @if(isset($remark->architect) && $project->architect_approval_status == \App\Models\Project::REJECTED)
                                            <p class="f-12 m-t-5" data-text="{{$remark->architect}}"><b>Remark :</b> <span class="js-text-full-remark">{{\Illuminate\Support\Str::limit($remark->architect ?? '', 180)}} </span>{!!\Illuminate\Support\Str::length($remark->architect ?? '') > 180 ? '<a class="js-full-text" href="">read more</a>' : ''!!}</p>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <i data-feather="user-check" data-discipline="{{\App\Models\Setting::DESIGN_ENGINEER_LIST['electrical']}}" {!! $isAuthorizeToReviewElectrical && $project->getStatusEstimateDiscipline('design_engineer_electrical')? 'data-bs-toggle="modal" data-bs-target=".js-modal-approve-discipline"' : ''!!}  class="{!! $isAuthorizeToReviewElectrical && $project->getStatusEstimateDiscipline('design_engineer_electrical')? 'js-modal-approval cursor-pointer' : 'color-grey'!!} m-r-10" style="width: 17px"></i>
                                        Electrical
                                        @if(isset($project->design_engineer_electrical))
                                            {!!$project->getStatusApprovalDiscipline($project->electrical_approval_status,$project->getProfileUser($project->electrical_approver)?->full_name) !!}
                                        @endif
                                    </td>
                                    <td>:</td>
                                    <td class="max_width40_td">
                                        {{$project?->designEngineerElectrical?->profiles?->full_name  ?? 'NR'}}
                                        @if(isset($remark->electrical) && $project->electrical_approval_status == \App\Models\Project::REJECTED)
                                            <p class="f-12 m-t-5" data-text="{{$remark->electrical}}"><b>Remark :</b> <span class="js-text-full-remark">{{\Illuminate\Support\Str::limit($remark->electrical ?? '', 180)}} </span> {!!\Illuminate\Support\Str::length($remark->electrical ?? '') > 180 ? '<a class="js-full-text" href="">read more</a>' : ''!!}</p>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <i data-feather="user-check" data-discipline="{{\App\Models\Setting::DESIGN_ENGINEER_LIST['instrument']}}" {!! $isAuthorizeToReviewInstrument && $project->getStatusEstimateDiscipline('design_engineer_instrument') ? 'data-bs-toggle="modal" data-bs-target=".js-modal-approve-discipline"' : ''!!} class="{{$isAuthorizeToReviewInstrument && $project->getStatusEstimateDiscipline('design_engineer_instrument')? 'js-modal-approval cursor-pointer' : 'color-grey'}} m-r-10" style="width: 17px"></i>
                                            Instrument
                                        @if(isset($project->design_engineer_instrument))
                                            {!!$project->getStatusApprovalDiscipline($project->instrument_approval_status, $project->getProfileUser($project->instrument_approver)?->full_name) !!}
                                        @endif
                                    </td>
                                    <td>:</td>
                                    <td class="max_width40_td">
                                        {{$project?->designEngineerInstrument?->profiles?->full_name ?? 'NR'}}
                                        @if(isset($remark->instrument) && $project->instrument_approval_status == \App\Models\Project::REJECTED)
                                            <p class="f-12 m-t-5" data-text="{{$remark->instrument}}"><b>Remark :</b> <span class="js-text-full-remark">{{\Illuminate\Support\Str::limit($remark->instrument ?? '', 180)}} </span>{!!\Illuminate\Support\Str::length($remark->instrument ?? '') > 180 ? '<a class="js-full-text" href="">read more</a>' : ''!!}</p>
                                        @endif
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <i data-feather="user-check" data-discipline="{{\App\Models\Setting::DESIGN_ENGINEER_LIST['it']}}" {!! $isAuthorizeToReviewIt && $project->getStatusEstimateDiscipline('design_engineer_it') ? 'data-bs-toggle="modal" data-bs-target=".js-modal-approve-discipline"' : ''!!} class="{{$isAuthorizeToReviewIt && $project->getStatusEstimateDiscipline('design_engineer_it')? 'js-modal-approval cursor-pointer' : 'color-grey'}} m-r-10" style="width: 17px"></i>
                                        IT
                                        @if(isset($project->design_engineer_it))
                                            {!!$project->getStatusApprovalDiscipline($project->it_approval_status, $project->getProfileUser($project->it_approver)?->full_name) !!}
                                        @endif
                                    </td>
                                    <td>:</td>
                                    <td class="max_width40_td">
                                        {{$project?->designEngineerIt?->profiles?->full_name ?? 'NR'}}
                                        @if(isset($remark->it) && $project->it_approval_status == \App\Models\Project::REJECTED)
                                            <p class="f-12 m-t-5" data-text="{{$remark->it}}"><b>Remark :</b> <span class="js-text-full-remark">{{\Illuminate\Support\Str::limit($remark->it ?? '', 180)}} </span>{!!\Illuminate\Support\Str::length($remark->it ?? '') > 180 ? '<a class="js-full-text" href="">read more</a>' : ''!!}</p>
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
                                        <a href="/project/{{$project->id}}/wbs/edit" class="{{$project->estimate_discipline_status == 'PUBLISH' ? 'd-none' : ''}}">
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
                            @if($project->isDesignEngineer())
                                @if(!$project->getStatusEstimateDiscipline(null))
                                <div class="col-md-6">
                                    @canAny(['create','update'], App\Models\EstimateAllDiscipline::class)
                                        <a href="/project/{{$project->id}}/estimate-discipline/create">
                                            <button class="btn btn-outline-primary float-end m-r-10" type="button">
                                                {{sizeof($estimateAllDisciplines) > 0 ? 'Edit Data' : 'Add New Data'}}
                                            </button>
                                        </a>
                                    @endcan
                                </div>
                                @endif
                            @endif
                        </div>
                    </div>
                    <div class="card-body" style="padding: 50px 10px 5px 10px">
                        <div class="col-sm-12 col-lg-12 col-xl-12">
                            <div class="clearfix"></div>
                            <div class="col-md-6">
                                 @if(sizeof($estimateAllDisciplines) > 0 && $project->status == $projectModel::APPROVE)
{{--                                 @if(sizeof($estimateAllDisciplines) > 0)--}}
                                    <div class="btn-group">
                                        <button type="button" class="js-btn-dropdown-dwd btn btn-success dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                                            <span class="text-dwd"> Download Options</span>
                                            <div class="loader-box float-end d-none" style="height: 0px; width: 20px; margin-top: 5%">
                                                <div class="loader-34"></div>
                                            </div>
                                        </button>
                                        <ul class="dropdown-menu">
                                            <li>
                                                <button class="dropdown-item js-download-summary-xlsx"
                                                   data-id="{{$project->id}}"
                                                   data-isDetail="false"
                                                   data-name="Cost Estimate Summary- {{$project->project_no}} - {{$project->project_title}}.pdf"
                                                  >
                                                    Download Cost Estimate Summary
                                                </button>
                                            </li>
                                            <li>
                                                <button class="dropdown-item js-download-summary-xlsx mb-3"
                                                   data-id="{{$project->id}}"
                                                   data-isDetail="true"
                                                   data-name="Cost Estimate Estimate All Discipline - {{$project->project_no}} - {{$project->project_title}}.pdf"
                                                   >
                                                    Download Estimate All Discipline
                                                </button>
                                            </li>
                                        </ul>
                                    </div>
                                 @endif
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

        <div data-id="{{$project->id}}" class="modal bd-example-modal-lg js-modal-approve-discipline fade" id="approveModalDiscipline" tabindex="-1" role="dialog" aria-labelledby="approveModalDiscipline" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Update  By Discipline</h5>
                        <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body text-center">
                        <span class="js-form-approval"></span>
                        <div class="loading-spinner mb-2">
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-danger" type="button" data-bs-dismiss="modal">Close</button>
                        <button class="btn btn-success js-btn-approve-discipline-cost-estimate" type="button">Update</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@include('layouts.loading')

@endsection
<!-- Container-fluid Ends-->
