@inject('setting',App\Models\Setting::class)

@extends('layouts.main')
@section('main')
    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-6">
                    <h4>Work Item Category</h4>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="">Home</a></li>
                        <li class="breadcrumb-item active">Work Item Category list</li>
                    </ol>
                </div>
                @can('create', App\Models\WorkItemType::class)
                    <div class="col-md-6 col-sm-6 text-end"><span class="f-w-600 m-r-5"></span>
                        <div class="select2-drpdwn-product select-options d-inline-block">
                            <div class="form-group mb-0 me-0"></div><a class="btn btn-outline-primary" href="/work-item-category/create"> Create New Work Item Category</a>
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
                        <form method="get" action="/work-item-category">
                            <div class="row">
                                <div class="col-md-6 mb-1">
                                    <input type="text" value="{{request()->q}}" name="q" placeholder="Code / Description" class="form-control" style="height: 40px">
                                    <input type="hidden" name="order" value="{{request()->order}}" class="js-filter-order">
                                    <input type="hidden" name="sort" value="{{request()->sort}}" class="js-filter-sort">
                                </div>
                                <div class="col-md-2 mb-1" >
                                    <input type="submit" class="btn btn-outline-success btn btn-search-tool-equipment-category" value="search" style="height: 40px"></input>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-sm-12 col-lg-12 col-xl-12">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th scope="col" class="text-left">Code <i class="fa fa-sort cursor-pointer js-order-sort" data-sort="work_item_types.code"></i></th>
                                    <th scope="col" class="text-left">Description <i class="fa fa-sort cursor-pointer js-order-sort" data-sort="work_item_types.title"></i></th>
                                    @can('delete', App\Models\WorkItemType::class)
                                        <th scope="col" class="text-left">Action</th>
                                    @endcan
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($work_item_category as $item)
                                    <tr>
                                        <td><a href="/work-item-category/{{$item->id}}" class="font-weight-bold">{{$item->code}}</td>
                                        <td>{{$item->title}}</td>
                                        @can('delete', App\Models\WorkItemType::class)
                                            <td><a data-bs-toggle="modal" data-original-title="test" data-bs-target="#deleteConfirmationModal"
                                               data-id="{{$item->id}}" class="text-danger js-delete-work-item-category">Delete</a></td>
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
        @if($work_item_category->total() > 1)
            <div class="row mb-5">
                <div class="col-md-12">
                    <nav aria-label="Page navigation example float-end">
                        <ul class="pagination">
                            {{$work_item_category->onEachSide(1)->links('project.pagination')}}
                        </ul>
                    </nav>
                </div>
            </div>
        @endif
    </div>

    <div class="modal fade js-modal-delete-work-item-category" id="deleteConfirmationModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Delete Work Item Category</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this item ?
                </div>
                <div class="modal-footer">
                    <button class="btn btn-success" type="button" data-bs-dismiss="modal">Close</button>
                    <button class="btn btn-danger js-delete-confirmation-work-item-category" type="button">Delete</button>
                </div>
            </div>
        </div>
    </div>
@endsection
