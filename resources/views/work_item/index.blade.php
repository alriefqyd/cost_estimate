@inject('setting',App\Models\Setting::class)
@inject('workItem',App\Models\WorkItem::class)
@extends('layouts.main')
@section('main')
    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-6">
                    <h4>Work Item</h4>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">Home</a></li>
                        <li class="breadcrumb-item active">Work Item list</li>
                    </ol>
                </div>
                @can('create',App\Models\WorkItem::class)
                    <div class="col-md-6 col-sm-6 text-end"><span class="f-w-600 m-r-5"></span>
                        <div class="select2-drpdwn-product select-options d-inline-block">
                            <div class="form-group mb-0 me-0"></div><a class="btn btn-outline-primary" href="/work-item/create"> Create New Work Item</a>
                        </div>
                    </div>
                @endcan
            </div>
        </div>
    </div>
    <div class="container-fluid product-wrapper">
        <div class="col-sm-12">
            <div class="row">
                <div class="card">
                    <div class="mt-5 mb-4">
                        <form method="get" action="/work-item">
                            <div class="row">
                                <label>Filter By</label>
                                <div class="col-md-3">
                                    <select class="select2 js-search-form js-select-category-work-item-list col-sm-12"
                                            name="category"
                                            data-placeholder="Category">
                                        <option></option>
                                        @foreach($work_item_category as $etc)
                                            <option {{isset(request()->category) && request()->category == $etc->id ? 'selected' : ''}} value="{{$etc->id}}">{{$etc->title}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3 mb-1">
                                    <input type="text" value="{{request()->q}}" name="q" placeholder="Work Item Code/Title" class="form-control js-search-form  js-search-code-name-work-item-list" style="height: 40px">
                                    <input type="hidden" name="order" value="{{request()->order}}" class="js-filter-order">
                                    <input type="hidden" name="sort" value="{{request()->sort}}" class="js-filter-sort">
                                </div>
                            </div>
                            <div class="row mb-1">
                                <div class="col-md-6">
                                    <select class="select2 multiple js-search-form col-sm-12"
                                            multiple="multiple"
                                            name="creator[]"
                                            data-placeholder="Creator">
                                        <option></option>
                                        @foreach($engineers as $eng)
                                            <option {{isset(request()->creator) && in_array($eng['id'],request()->creator) ? 'selected' : ''}} value="{{$eng['id']}}">{{$eng['full_name']}}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="btn-group btn-group-square " role="group" aria-label="Basic example">
                                        <input type="hidden" name="status" value="{{request()->status}}" class="js-status-filter">
                                        <button class="btn btn-outline-light txt-dark {{request()->status == $workItem::DRAFT ? 'active' : ''}} js-btn-status" data-value="{{$workItem::DRAFT}}" type="button">
                                            {{$workItem::DRAFT}} ({{$workItemDraft}})
                                        </button>
                                        <button class="btn btn-outline-light txt-dark {{request()->status == $workItem::REVIEWED ? 'active' : ''}} js-btn-status" data-value="{{$workItem::REVIEWED}}" type="button">
                                            {{$workItem::REVIEWED}} ({{$workItemReviewed}})
                                        </button>
                                    </div>
                                </div>
                            </div>
                            @if(auth()->user()->isWorkItemReviewer())
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
                                    @if(auth()->user()->isWorkItemReviewer())
                                        <th><input type="checkbox" class="js-select-all-project-to-review js-check-review-all custom-checkbox" data-url="workItem"></th>
                                    @endif
                                    <th class="text-left">Code <i class="fa fa-sort cursor-pointer js-order-sort" data-sort="work_items.code"></i>
                                    </th>
                                    <th class="text-left">
                                        Description <i class="fa fa-sort cursor-pointer js-order-sort" data-sort="work_items.description"></i>
                                    </th>
                                    <th class="min-w-100 text-left">
                                        Category <i class="fa fa-sort cursor-pointer js-order-sort" data-sort="work_item_types.title"></i>
                                    </th>
                                    <th class="text-left">
                                        Vol <i class="fa fa-sort cursor-pointer js-order-sort" data-sort="work_items.volume"></i>
                                    </th>
                                    <th class="text-left" >
                                        Unit <i class="fa fa-sort cursor-pointer js-order-sort" data-sort="work_items.unit"></i>
                                    </th>
                                    <th class="text-left" >
                                        Total Price
                                    </th>
                                    <th class="text-left">
                                        Status
                                    </th>
                                    <th class="text-left">
                                        Created By
                                    </th>
                                    @can('delete', \App\Models\WorkItem::class)
                                        <th>
                                            Action
                                        </th>
                                    @endCan
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($work_item as $item)
                                    <tr>
                                        @if(auth()->user()->isWorkItemReviewer())
                                            <td class="text-center">
                                                <input type="checkbox" class="js-select-project-to-review js-check-review custom-checkbox"
                                                       data-url="workItem"
                                                       value="{{$item->id}}">
                                            </td>
                                        @endif
                                        <td class="min-w-100"><a href="/work-item/{{$item->id}}" class="font-weight-bold">{{$item->code}}</td>
                                        <td class="min-w-250">{{$item->description}}</td>
                                        <td class="max-w-200">{{$item?->category}}</td>
                                        <td class="min-w-50">{{$item->volume}}</td>
                                        <td class="min-w-65">{{$item->unit}}</td>
                                        <td class="min-w-120">{{number_format($item?->getTotalSum(),2,',','.')}}</td>
                                        <td class="min-w-90">{{$item->status}}</td>
                                        <td class="">{{$item->full_name}}</td>
                                        @can('delete',App\Models\WorkItem::class)
                                            <td>
                                                <a data-bs-toggle="modal" data-original-title="test" data-bs-target="#deleteConfirmationModal"
                                                   data-id="{{$item->id}}" class="text-danger js-delete-work-item-modal">Delete</a>
                                            </td>
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
        @if($work_item->total() > 1)
            <div class="row mb-5">
                <div class="col-md-12">
                    <nav aria-label="Page navigation example float-end">
                        <ul class="pagination">
                            {{$work_item->onEachSide(1)->links('project.pagination')}}
                        </ul>
                    </nav>
                </div>
            </div>
        @endif
    </div>

    <div class="modal fade js-modal-approve-list" id="" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Update Work Items</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to update this item?
                </div>
                <div class="modal-footer">
                    <button class="btn btn-success" type="button" data-bs-dismiss="modal">Close</button>
                    <button class="btn btn-danger js-approve-confirmation-work-item" type="button">Reviewed</button>
                </div>
            </div>
        </div>
    </div>

    @can('delete',App\Models\WorkItem::class)
        <div class="modal fade js-modal-delete-work-item" id="deleteConfirmationModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Delete Work Item</h5>
                        <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Are you sure you want to delete this item?
                    </div>
                    <div class="modal-footer">
                        <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Close</button>
                        <button class="btn btn-danger js-delete-work-item" type="button">Delete</button>
                    </div>
                </div>
            </div>
        </div>
    @endcan
@endsection
