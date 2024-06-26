@extends('layouts.main')
@section('main')
    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-6">
                    <h4>Man Power</h4>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">Home</a></li>
                        <li class="breadcrumb-item"><a href="/work-item">Work Item List</a></li>
                        <li class="breadcrumb-item active">Edit Work Item</li>
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
            </div>
            <div class="row js-confirm-row">
                <div class="card">
                    <div class="card-header-costume">
                        <div class="float-start">
                            <label>Work Item</label>
                        </div>
                    </div>
                    <div class="card-body mt-4 p-3">
                        <div class="mb-5 mt-2">
                            <form method="post" action="/work-item/{{$work_item->id}}">
                                <div class="row">
                                    @csrf
                                    @method('put')
                                    @include('work_item.form',['isEdit' => true])
                                </div>
                                <div class="row js-work-item {{!isset($work_item->id) ? 'd-none' : ''}}">
                                    <div class="col-md-12 mt-5 text-end">
                                        <a href="/work-item/">
                                            <div class="btn js-btn-save-work-item btn-outline-danger">Cancel</div>
                                        </a>
                                        @if($isUserCanUpdate)
                                            <button class="btn js-btn-save-work-item js-save-confirm-form btn-outline-success">Save</button>
                                        @endif
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
