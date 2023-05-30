@inject('setting',App\Models\Setting::class)

@extends('layouts.main')
@section('main')
    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-6">
                    <h3>Work Breakdown Structure</h3>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="">Home</a></li>
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
                    <div class="col-sm-12 mt-5">
                            <label class="float-start">Work Element List</label>
                            <a href="/work-breakdown-structure/{{request()->id}}/work-element/create"><button class="btn btn-outline-primary float-end">Create New Work Element</button></a>
                    </div>
                    <div class="col-sm-12 col-lg-12 col-xl-12 mt-4 mb-5">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th scope="col" class="text-left">Description <i class="fa fa-sort cursor-pointer js-order-sort" data-sort="category"></i></th>
                                    <th scope="col" class="text-left">Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($wbs?->children as $item)
                                    <tr>
                                        <td class="min-w-200"><a href="/work-breakdown-structure/work-element/{{$item->id}}" class="font-weight-bold">{{$item->title}}</a></td>
                                        <td><a data-bs-toggle="modal" data-original-title="test" data-bs-target="#deleteConfirmationModal"
                                               data-id="{{$item->id}}" class="text-danger js-delete-work-element">Delete</a></td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        {{--<div class="modal fade js-modal-work-element" id="deleteConfirmationModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
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
        </div>--}}
        @endif
    </div>

@endsection
