@inject('setting',App\Models\Setting::class)
@inject('equipmentTools',App\Models\EquipmentTools::class)

@extends('layouts.main')
@section('main')
    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-6">
                    <h4>Tools Equipment</h4>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">Home</a></li>
                        <li class="breadcrumb-item active">Tools Equipment list</li>
                    </ol>
                </div>
                @can('create',App\Models\EquipmentTools::class)
                    <div class="col-md-6 col-sm-6 text-end"><span class="f-w-600 m-r-5"></span>
                        <div class="select2-drpdwn-product select-options d-inline-block">
                            <div class="form-group mb-0 me-0"></div><a class="btn btn-outline-primary" href="/tool-equipment/create"> Create New Tools Equipment</a>
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
                        <form method="get" action="/tool-equipment">
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <select class="select2 col-sm-12 js-search-form"
                                            name="category"
                                            data-placeholder="Category">
                                        <option></option>
                                        @foreach($equipment_tools_category as $etc)
                                            <option {{isset(request()->category) && request()->category == $etc->id ? 'selected' : ''}} value="{{$etc->id}}">{{$etc->description}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-4 mb-1">
                                    <input type="text" value="{{request()->q}}" name="q" placeholder="Man Power Code/Title" class="form-control js-search-form" style="height: 40px">
                                </div>
                                <div class="col-md-2 mb-1" >
                                    <input type="hidden" name="order" value="{{request()->order}}" class="js-filter-order">
                                    <input type="hidden" name="sort" value="{{request()->sort}}" class="js-filter-sort">
                                </div>
                            </div>
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <div class="btn-group btn-group-square " role="group" aria-label="Basic example">
                                        <input type="hidden" name="status" value="{{request()->status}}" class="js-status-filter">
                                        <button class="btn btn-outline-light txt-dark {{request()->status == $equipmentTools::DRAFT ? 'active' : ''}} js-btn-status" data-value="{{$equipmentTools::DRAFT}}" type="button">
                                            {{$equipmentTools::DRAFT}} ({{$draftEquipmentTools}})
                                        </button>
                                        <button class="btn btn-outline-light txt-dark {{request()->status == $equipmentTools::REVIEWED ? 'active' : ''}} js-btn-status" data-value="{{$equipmentTools::REVIEWED}}" type="button">
                                            {{$equipmentTools::REVIEWED}} ({{$reviewedEquipmentTools}})
                                        </button>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    @can('export', \App\Models\EquipmentTools::class)
                                        <div class="btn btn-outline-success js-btn-export float-end m-1"
                                             data-file-name="Tools equipment.xlsx"
                                             data-url="/tool-equipment/export/">
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
                                    @can('import', \App\Models\EquipmentTools::class)
                                        <div class="btn btn-outline-success float-end m-1 js-btn-import-equipment"
                                             data-bs-toggle="modal" data-original-title="test" data-bs-target="#modalImportEquipment">
                                            Import
                                            <div class="loader-box float-end d-none" style="height: 0px; width: 20px; margin-top: 9%">
                                                <div class="loader-34"></div>
                                            </div>
                                        </div>
                                    @endcan
                                </div>
                            </div>
                            @if(auth()->user()->isToolsEquipmentReviewerRole())
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
                                    @if(auth()->user()->isToolsEquipmentReviewerRole())
                                        <th><input type="checkbox" class="js-select-all-project-to-review js-check-review-all custom-checkbox" data-url="equipmentTools"></th>
                                    @endif
                                    <th scope="col" class="text-left">Code <i class="fa fa-sort cursor-pointer js-order-sort" data-sort="equipment_tools.code"></i></th>
                                    <th scope="col" class="text-left">Description <i class="fa fa-sort cursor-pointer js-order-sort" data-sort="equipment_tools.description"></i></th>
                                    <th scope="col" class="text-left">Category <i class="fa fa-sort cursor-pointer js-order-sort" data-sort="category"></i></th>
                                    <th scope="col" class="text-left">Qty <i class="fa fa-sort cursor-pointer js-order-sort" data-sort="equipment_tools.quantity"></i></th>
                                    <th scope="col" class="text-left">Unit <i class="fa fa-sort cursor-pointer js-order-sort" data-sort="equipment_tools.unit"></i></th>
                                    <th scope="col" class="text-left">Local Rate <i class="fa fa-sort cursor-pointer js-order-sort" data-sort="equipment_tools.local_rate"></i></th>
                                    <th scope="col" class="text-left">National Rate <i class="fa fa-sort cursor-pointer js-order-sort" data-sort="equipment_tools.national_rate"></i></th>
                                    <th scope="col" class="text-left">Created By <i class="fa fa-sort cursor-pointer js-order-sort" data-sort="equipment_tools.created_by"></i></th>
                                    <th scope="col" class="text-left">Status <i class="fa fa-sort cursor-pointer js-order-sort" data-sort="equipment_tools.status"></i></th>
                                    @can('delete',App\Models\EquipmentTools::class)
                                        <th scope="col" class="text-left">Action</th>
                                    @endcan
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($equipment_tools as $item)
                                    <tr>
                                        @if(auth()->user()->isToolsEquipmentReviewerRole())
                                            <td class="text-center">
                                                <input type="checkbox" class="js-select-project-to-review js-check-review custom-checkbox"
                                                       data-url="equipmentTools"
                                                       value="{{$item->id}}">
                                            </td>
                                        @endif
                                        <td class="min-w-150"><a href="/tool-equipment/{{$item->id}}" class="font-weight-bold">{{$item->code}}</td>
                                        <td class="min-w-170">{{$item->description}}</td>
                                        <td class="min-w-170">{{$item?->equipmentToolsCategory?->description}}</td>
                                        <td class="min-w-60">{{$item->quantity}}</td>
                                        <td class="min-w-80">{{$item->unit}}</td>
                                        <td>{{number_format($item->local_rate,2,',','.')}}</td>
                                        <td class="min-w-150">{{number_format($item->national_rate,2,',','.')}}</td>
                                        <td class="min-w-80">{{$item->createdBy?->profiles?->full_name}}</td>
                                        <td class="min-w-80">{{$item->status}}</td>
                                        @can('delete',App\Models\EquipmentTools::class)
                                            <td><a data-bs-toggle="modal" data-original-title="test" data-bs-target="#deleteConfirmationModal"
                                               data-id="{{$item->id}}" class="text-danger js-delete-tool-equipment">Delete</a></td>
                                        @endcan
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
        @if($equipment_tools->total() > 1)
            <div class="row mb-5">
                <div class="col-md-12">
                    <nav aria-label="Page navigation example float-end">
                        <ul class="pagination">
                            {{$equipment_tools->onEachSide(1)->links('project.pagination')}}
                        </ul>
                    </nav>
                </div>
            </div>
        @endif
    </div>

    <div class="modal fade js-modal-delete-tool-equipment" id="deleteConfirmationModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Delete Tool Equipment</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this item?
                </div>
                <div class="modal-footer">
                    <button class="btn btn-success" type="button" data-bs-dismiss="modal">Close</button>
                    <button class="btn btn-danger js-delete-confirmation-tool-equipment" type="button">Delete</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade js-modal-approve-list" id="" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Update Equipment Tools</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to update this item?
                </div>
                <div class="modal-footer">
                    <button class="btn btn-danger" type="button" data-bs-dismiss="modal">Close</button>
                    <button class="btn btn-success js-approve-confirmation-equipment-tools" type="button">Reviewed</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade js-modal-import js-modal-import-equipment" id="modalImportEquipment" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <form action="/tool-equipment/import" class="js-form-import" data-url="/tool-equipment/import" data-redirect="tool-equipment" method="post" enctype="multipart/form-data">
            @csrf
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Import Tool Equipment</h5>
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
