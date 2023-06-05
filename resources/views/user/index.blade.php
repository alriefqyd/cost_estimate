@inject('setting',App\Models\Setting::class)

@extends('layouts.main')
@section('main')
    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-6">
                    <h3>User List</h3>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="">Home</a></li>
                        <li class="breadcrumb-item active">User List</li>
                    </ol>
                </div>
                <div class="col-md-6 col-sm-6 text-end"><span class="f-w-600 m-r-5"></span>
                    <div class="select2-drpdwn-product select-options d-inline-block">
                        <div class="form-group mb-0 me-0"></div><a class="btn btn-outline-primary" href="/user/create"> Create New User</a>
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
                        <form method="get" action="/user">
                            <div class="row">
                                <div class="col-md-10 mb-1">
                                    <input type="text" value="{{request()->q}}" name="q" placeholder="Name/Email" class="form-control" style="height: 40px">
                                </div>
                                <div class="col-md-1 mb-1" >
                                    <input type="hidden" name="order" value="{{request()->order}}" class="js-filter-order">
                                    <input type="hidden" name="sort" value="{{request()->sort}}" class="js-filter-sort">
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
                                    <th scope="col" class="text-left">User Name <i class="fa fa-sort cursor-pointer js-order-sort" data-sort="user_name"></i></th>
                                    <th scope="col" class="text-left">Full Name <i class="fa fa-sort cursor-pointer js-order-sort" data-sort="full_name"></i></th>
                                    <th scope="col" class="text-left">Email <i class="fa fa-sort cursor-pointer js-order-sort" data-sort="email"></i></th>
                                    <th scope="col" class="text-left">Position <i class="fa fa-sort cursor-pointer js-order-sort" data-sort="position"></i></th>
                                    <th scope="col" class="text-left">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($users as $item)
                                    <tr>
                                        <td class="min-w-200"><a href="/user/{{$item->id}}" class="font-weight-bold">{{$item->user_name}}</a></td>
                                        <td class="min-w-200">{{$item->profiles?->full_name}}</td>
                                        <td class="min-w-200">{{$item->profiles?->email}}</td>
                                        <td class="min-w-200">{{$item->profiles?->getPosition()}}</td>
                                        <td><a data-bs-toggle="modal" data-original-title="test" data-bs-target="#deleteConfirmationUser"
                                               data-id="{{$item->id}}" class="text-danger js-delete-user">Delete</a></td>
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
        @if($users->total() > 1)
            <div class="row mb-5">
                <div class="col-md-12">
                    <nav aria-label="Page navigation example float-end">
                        <ul class="pagination">
                            {{$users->onEachSide(1)->links('project.pagination')}}
                        </ul>
                    </nav>
                </div>
            </div>
        @endif
    </div>

    {{--<div class="modal fade js-modal-delete-wbs" id="deleteConfirmationModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Delete WBS</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this item?
                </div>
                <div class="modal-footer">
                    <button class="btn btn-success" type="button" data-bs-dismiss="modal">Close</button>
                    <button class="btn btn-danger js-delete-confirmation-wbs" type="button">Delete</button>
                </div>
            </div>
        </div>
    </div>--}}
@endsection
