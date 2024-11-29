@inject('setting',App\Models\Setting::class)
@inject('manPower',App\Models\ManPower::class)
@extends('layouts.main')
@section('main')
    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-6">
                    <h4>Man Power</h4>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">Home</a></li>
                        <li class="breadcrumb-item active">Man Power list</li>
                    </ol>
                </div>
                @can('create',App\Models\ManPower::class)
                    <div class="col-md-6 col-sm-6 text-end"><span class="f-w-600 m-r-5"></span>
                        <div class="select2-drpdwn-product select-options d-inline-block">
                            <div class="form-group mb-0 me-0"></div><a class="btn btn-outline-primary" href="/man-power/create"> Create New Man Power</a>
                        </div>
                    </div>
                @endcan
            </div>
        </div>
    </div>
    <div class="container-fluid product-wrapper">
        <div class="col-sm-12">
            <div class="row">
                @if(session('message'))
                    @include('flash')
                @endif
                <div class="card">
                    <div class="mt-5 mb-4">
                        <form method="get" action="/man-power">
                            <div class="row">
                                <div class="col-md-2">
                                    <select class="select2 col-sm-12 js-search-form"
                                            name="skill_level"
                                            data-placeholder="Skill Level">
                                        <option></option>
                                        @foreach($setting::SKILL_LEVEL as $key => $value)
                                            <option {{isset(request()->skill_level) && request()->skill_level == $key ? 'selected' : ''}} value="{{$key}}">{{$value}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4 mb-1">
                                    <input type="text" value="{{request()->q}}" name="q" placeholder="Man Power Code/Title" class="form-control" style="height: 40px">
                                </div>
                                <div class="col-md-1 mb-1" >
                                    <input type="hidden" name="order" value="{{request()->order}}" class="js-filter-order">
                                    <input type="hidden" name="sort" value="{{request()->sort}}" class="js-filter-sort">
                                </div>
                            </div>
                            <div class="row mb-5">
                                <div class="col-md-6">
                                    <div class="btn-group btn-group-square " role="group" aria-label="Basic example">
                                        <input type="hidden" name="status" value="{{request()->status}}" class="js-status-filter">
                                        <button class="btn btn-outline-light txt-dark {{request()->status == $manPower::DRAFT ? 'active' : ''}} js-btn-status" data-value="{{$manPower::DRAFT}}" type="button">
                                            {{$manPower::DRAFT}} ({{$draftManPower}})
                                        </button>
                                        <button class="btn btn-outline-light txt-dark {{request()->status == $manPower::REVIEWED ? 'active' : ''}} js-btn-status" data-value="{{$manPower::REVIEWED}}" type="button">
                                            {{$manPower::REVIEWED}} ({{$reviewedManPower}})
                                        </button>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    @can('export', \App\Models\ManPower::class)
                                        <div class="btn btn-outline-success js-btn-export float-end m-1"
                                                data-file-name="Man Power.xlsx"
                                                data-url="/man-power/export/">
                                            <div class="float-start">
                                                Export
                                            </div>
                                            <div class="float-end">
                                                <div class="loader-box m-2 d-none" style="height:0px">
                                                    <div class="loader-3"></div>
                                                </div>
                                            </div>
                                        </div>
                                    @endcan
                                    @can('import', \App\Models\ManPower::class)
                                        <div class="btn btn-outline-success float-end m-1 js-btn-import-man-power"
                                                data-bs-toggle="modal" data-original-title="test" data-bs-target="#modalImportManPower">
                                            Import
                                            <div class="loader-box float-end d-none" style="height: 0px; width: 20px; margin-top: 9%">
                                                <div class="loader-34"></div>
                                            </div>
                                        </div>
                                    @endcan
                                </div>
                            </div>
                            @if(auth()->user()->isManPowerReviewer())
                                <div class="row">
                                    <div class="col-md-6">

                                    </div>
                                    <div class="col-md-6">
                                        <button class="btn btn-outline-light txt-dark float-end js-btn-to-review" disabled="disabled" type="button">
                                            Set to Reviewed (<span class="js-select-to-reviewed">0</span>)
                                        </button>
                                    </div>
                                </div>
                            @endif
                        </form>
                    </div>

                    <div class="col-sm-12 col-lg-12 col-xl-12">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        @if(auth()->user()->isManPowerReviewer())
                                            <th><input type="checkbox" class="js-select-all-project-to-review js-check-review-all custom-checkbox" data-url="manPower"></th>
                                        @endif
                                        <th scope="col" class="text-left">Code <i class="fa fa-sort cursor-pointer js-order-sort" data-sort="code"></i></th>
                                        <th scope="col" class="text-left">Skill Level <i class="fa fa-sort cursor-pointer js-order-sort" data-sort="skill_level"></i></th>
                                        <th scope="col" class="text-left">Title <i class="fa fa-sort cursor-pointer js-order-sort" data-sort="title"></i></th>
                                        <th scope="col" class="text-left">Basic Rate Monthly <i class="fa fa-sort cursor-pointer js-order-sort" data-sort="basic_rate_month"></i></th>
                                        <th scope="col" class="text-left">Basic Rate Hour <i class="fa fa-sort cursor-pointer js-order-sort" data-sort="basic_rate_hour"></i></th>
                                        <th scope="col" class="text-left">Overall Rate Hourly <i class="fa fa-sort cursor-pointer js-order-sort" data-sort="overall_rate_hourly"></i></th>
                                        <th scope="col" class="text-left">Created By <i class="fa fa-sort cursor-pointer js-order-sort" data-sort="created_by"></i></th>
                                        <th scope="col" class="text-left">Status</th>
                                        @can('delete',App\Models\ManPower::class)
                                            <th scope="col" class="text-left">Action</th>
                                        @endcan
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($man_power as $item)
                                    <tr>
                                        @if(auth()->user()->isManPowerReviewer())
                                            <td class="text-center">
                                                <input type="checkbox" class="js-select-project-to-review js-check-review custom-checkbox" data-url="manPower" value="{{$item->id}}">
                                            </td>
                                        @endif
                                        <td><a href="/man-power/{{$item->id}}" class="font-weight-bold">{{$item->code}}</td>
                                        <td class="min-w-100">{{$item->getSkillLevel()}}</td>
                                        <td class="min-w-150">{{$item->title}}</td>
                                        <td class="min-w-200">{{number_format($item->basic_rate_month,2)}}</td>
                                        <td class="min-w-150">{{number_format($item->basic_rate_hour,2)}}</td>
                                        <td class="min-w-170">{{number_format($item->overall_rate_hourly,2)}}</td>
                                        <td class="min-w-170">{{$item->createdBy?->profiles?->full_name}}</td>
                                        @can('delete',App\Models\ManPower::class)
                                            <td><a data-bs-toggle="modal" data-original-title="test" data-bs-target="#deleteConfirmationModal"
                                                data-id="{{$item->id}}" class="text-danger js-delete-man-power">Delete</a></td>
                                        @endcan
                                        <td class="min-w-100">{{$item->status}}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="cols-sm-12 col-lg-12 col-xl-12" style="margin-bottom: 20px"></div>
                </div>
            </div>
        </div>
        @if($man_power->total() > 1)
            <div class="row mb-5">
                <div class="col-md-12">
                    <nav aria-label="Page navigation example float-end">
                        <ul class="pagination">
                            {{$man_power->onEachSide(1)->links('project.pagination')}}
                        </ul>
                    </nav>
                </div>
            </div>
        @endif
    </div>

    <div class="modal fade js-modal-delete-man-power" id="deleteConfirmationModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Delete Man Power</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this item?
                </div>
                <div class="modal-footer">
                    <button class="btn btn-success" type="button" data-bs-dismiss="modal">Close</button>
                    <button class="btn btn-danger js-delete-confirmation-man-power" type="button">Delete</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade js-modal-approve-list" id="" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Update Man Powers</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to update this item?
                </div>
                <div class="modal-footer">
                    <button class="btn btn-danger" type="button" data-bs-dismiss="modal">Close</button>
                    <button class="btn btn-success js-approve-confirmation-man-power" type="button">Reviewed</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade js-modal-import-man-power js-modal-import" id="modalImportManPower" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <form action="/man-power/import" class="js-form-import" data-url="/man-power/import/" data-redirect="man-power" method="post" enctype="multipart/form-data">
            @csrf
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Import Man Power</h5>
                        <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <input type="file" name="file">
                        <div class="mt-3">
                            <div class="progress d-none">
                                <div class="progress-bar progress-bar-animated bg-primary progress-bar-striped" role="progressbar" style="width: 0%" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100"></div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-danger" type="button" data-bs-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-success js-import-btn-confirmation-man-power">Import</button>
                    </div>
                </div>
            </div>
        </form>
    </div>
@endsection
