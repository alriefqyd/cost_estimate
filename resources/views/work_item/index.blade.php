@inject('setting',App\Models\Setting::class)

@extends('layouts.main')
@section('main')
    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-6">
                    <h3>Work Item</h3>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="">Home</a></li>
                        <li class="breadcrumb-item active">Work Item list</li>
                    </ol>
                </div>
                <div class="col-md-6 col-sm-6 text-end"><span class="f-w-600 m-r-5"></span>
                    <div class="select2-drpdwn-product select-options d-inline-block">
                        <div class="form-group mb-0 me-0"></div><a class="btn btn-outline-primary" href="/work-item/create"> Create New Work Item</a>
                    </div>
                </div>
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
                                <div class="col-md-4">
                                    <select class="select2 js-select-category-work-item-list col-sm-12"
                                            name="category"
                                            data-placeholder="Category">
                                        <option></option>
                                        @foreach($work_item_category as $etc)
                                            <option {{isset(request()->category) && request()->category == $etc->id ? 'selected' : ''}} value="{{$etc->id}}">{{$etc->title}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mb-1">
                                    <input type="text" value="{{request()->q}}" name="q" placeholder="Work Item Code/Title" class="form-control js-search-code-name-work-item-list" style="height: 40px">
                                    <input type="hidden" name="order" value="{{request()->order}}" class="js-filter-order">
                                    <input type="hidden" name="sort" value="{{request()->sort}}" class="js-filter-sort">
                                </div>
                                <div class="col-md-2 mb-1" >
                                    <input type="submit" class="btn btn-outline-success btn btn-search-man-power" value="search" style="height: 40px"></input>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col-sm-12 col-lg-12 col-xl-12">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th scope="col" class="text-left">Code <i class="fa fa-sort cursor-pointer js-order-sort" data-sort="work_items.code"></i>
                                    </th>
                                    <th scope="col" class="text-left">
                                        Description <i class="fa fa-sort cursor-pointer js-order-sort" data-sort="work_items.description"></i>
                                    </th>
                                    <th scope="col" class="text-left">
                                        Category <i class="fa fa-sort cursor-pointer js-order-sort" data-sort="work_item_types.title"></i>
                                    </th>
                                    <th scope="col" class="text-left">
                                        Volume <i class="fa fa-sort cursor-pointer js-order-sort" data-sort="work_items.volume"></i>
                                    </th>
                                    <th scope="col" class="text-left" >
                                        Unit <i class="fa fa-sort cursor-pointer js-order-sort" data-sort="work_items.unit"></i>
                                    </th>
                                    <th scope="col" class="text-left" >
                                        Total Price
                                    </th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($work_item as $item)
                                    <tr>
                                        <td class="min-w-100"><a href="/work-item/{{$item->id}}" class="font-weight-bold">{{$item->code}}</td>
                                        <td class="min-w-300">{{$item->description}}</td>
                                        <td class="max-w-250">{{$item?->category}}</td>
                                        <td class="min-w-80">{{$item->volume}}</td>
                                        <td class="min-w-65">{{$item->unit}}</td>
                                        <td class="min-w-100">{{number_format($item?->getTotalSum(),2,',','.')}}</td>
                                        {{--<td><a data-bs-toggle="modal" data-original-title="test" data-bs-target="#deleteConfirmationModal"
                                               data-id="{{$item->id}}" class="text-danger js-delete-tool-equipment">Delete</a></td>--}}
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
@endsection
