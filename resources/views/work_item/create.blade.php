@extends('layouts.main')
@section('main')
    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-6">
                    <h3>Man Power</h3>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                        <li class="breadcrumb-item">Work Item List</li>
                        <li class="breadcrumb-item active">Add Work Item</li>
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
            <div class="row">
                <div class="card">
                    <div class="card-header-costume">
                        <div class="float-start">
                            <label>Work Item</label>
                        </div>
                    </div>
                    <div class="card-body mt-4 p-3">
                        <div class="mb-5 mt-2">
                            <form method="post" action="/work-item">
                                <div class="row">
                                    @csrf
                                    @include('work_item.form',['isEdit' => false])
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
