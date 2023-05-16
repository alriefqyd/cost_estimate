@inject('setting',App\Models\Setting::class)

@extends('layouts.main')
@section('main')
    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-6">
                    <h3>Tools Equipment Category</h3>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="">Home</a></li>
                        <li class="breadcrumb-item active">Tools Equipment Category list</li>
                    </ol>
                </div>
                <div class="col-md-6 col-sm-6 text-end"><span class="f-w-600 m-r-5"></span>
                    <div class="select2-drpdwn-product select-options d-inline-block">
                        <div class="form-group mb-0 me-0"></div><a class="btn btn-outline-primary" href="/tool-equipment-category/create"> Create New Tools Equipment Category</a>
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
                        <form method="get" action="/tool-equipment-category">
                            <div class="row">
                                <div class="col-md-6 mb-1">
                                    <input type="text" value="{{request()->q}}" name="q" placeholder="Code / Description" class="form-control" style="height: 40px">
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
                                    <th scope="col" class="text-left">Code</th>
                                    <th scope="col" class="text-left">Description</th>
                                    <th scope="col" class="text-left">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($equipment_tools_category as $item)
                                    <tr>
                                        <td><a href="/tool-equipment-category/{{$item->id}}" class="font-weight-bold">{{$item->code}}</td>
                                        <td>{{$item->description}}</td>
                                        <td><a data-bs-toggle="modal" data-original-title="test" data-bs-target="#deleteConfirmationModal"
                                               data-id="{{$item->id}}" class="text-danger js-delete-tool-equipment-category">Delete</a></td>
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
        @if($equipment_tools_category->total() > 1)
            <div class="row mb-5">
                <div class="col-md-12">
                    <nav aria-label="Page navigation example float-end">
                        <ul class="pagination">
                            {{$equipment_tools_category->onEachSide(1)->links('project.pagination')}}
                        </ul>
                    </nav>
                </div>
            </div>
        @endif
    </div>

    <div class="modal fade js-modal-delete-tool-equipment-category" id="deleteConfirmationModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Delete Tool Equipment Category</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this item ?
                </div>
                <div class="modal-footer">
                    <button class="btn btn-success" type="button" data-bs-dismiss="modal">Close</button>
                    <button class="btn btn-danger js-delete-confirmation-tool-equipment-category" type="button">Delete</button>
                </div>
            </div>
        </div>
    </div>
@endsection
