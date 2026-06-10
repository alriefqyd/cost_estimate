@inject('setting',App\Models\Setting::class)

@extends('layouts.main')
@section('main')
    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-6">
                    <h4>Work Breakdown Structure</h4>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">Home</a></li>
                        <li class="breadcrumb-item"><a href="/work-breakdown-structure">Work Breakdown Structure List</a></li>
                        <li class="breadcrumb-item active">Setting Work Breakdown Structure</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid product-wrapper">
        <div class="col-sm-12">
            <div class="row">
                @if($errors->any())
                    <div class="col-md-12 alert alert-danger">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </div>
                @endif
                @if(session('message'))
                    @include('flash')
                @endif
                <div class="card">
                    <div class="card-body m-0 p-3">
                        <div class="mb-5 mt-2">
                            <form method="post" action="{{$isWorkElement ? '/work-breakdown-structure/work-element/'.request()->id : '/work-breakdown-structure/' . request()->id}}">
                                <div class="row">
                                    @csrf
                                    @method('put')
                                    @include('setting_work_breakdown_structure.form')
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @if(!$isWorkElement)
        <div class="col-sm-12">
            <div class="row">
                <div class="card">
                    <div class="col-sm-12 mt-5 d-flex align-items-center justify-content-between pe-3">
                        <label class="float-start mb-0">Work Element List</label>
                        <div class="d-flex gap-2">
                            <button type="button"
                                class="btn btn-outline-success btn-sm js-save-wbs-order d-none"
                                data-url="/work-breakdown-structure/reorder-work-elements">
                                <i class="fa fa-check me-1"></i> Save Order
                            </button>
                            @can('create',App\Models\WorkBreakdownStructure::class)
                                <a href="/work-breakdown-structure/{{request()->id}}/work-element/create">
                                    <button class="btn btn-outline-primary btn-sm" type="button">Create New Work Element</button>
                                </a>
                            @endcan
                        </div>
                    </div>
                    <div class="col-sm-12 col-lg-12 col-xl-12 mt-4 mb-5">
                        <div class="table-responsive">
                            <table class="table table-striped" id="js-wbs-element-table">
                                <thead>
                                <tr>
                                    @can('update',App\Models\WorkBreakdownStructure::class)
                                        <th scope="col" class="wbs-th-handle"></th>
                                    @endcan
                                    <th scope="col" class="wbs-th-order">#</th>
                                    <th scope="col" class="text-left">Description</th>
                                    @can('delete',App\Models\WorkBreakdownStructure::class)
                                        <th scope="col" class="text-left">Action</th>
                                    @endcan
                                </tr>
                                </thead>
                                <tbody id="js-wbs-sortable-body">
                                @foreach($wbs?->children as $item)
                                    <tr class="js-sortable-row uk-sortable-item" data-id="{{$item->id}}">
                                        @can('update',App\Models\WorkBreakdownStructure::class)
                                            <td class="js-sort-handle wbs-handle-cell">
                                                <i class="fa fa-bars"></i>
                                            </td>
                                        @endcan
                                        <td class="wbs-order-cell js-order-num">{{ $loop->iteration }}</td>
                                        <td class="min-w-200 wbs-title-cell">
                                            <a href="/work-breakdown-structure/work-element/{{$item->id}}" class="font-weight-bold">{{$item->title}}</a>
                                        </td>
                                        @can('delete',App\Models\WorkBreakdownStructure::class)
                                            <td>
                                                <a data-bs-toggle="modal" data-original-title="test" data-bs-target="#deleteConfirmationModal"
                                                   data-id="{{$item->id}}" class="text-danger js-delete-work-element">Delete</a>
                                            </td>
                                        @endcan
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade js-modal-work-element" id="deleteConfirmationModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div clss="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Delete WBS Work Element</h5>
                        <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to delete this item?
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-success" type="button" data-bs-dismiss="modal">Close</button>
                        <button class="btn btn-danger js-delete-confirmation-wbs-work-element" type="button">Delete</button>
                    </div>
                </div>
            </div>
        </div>
        @endif
    </div>

@endsection
