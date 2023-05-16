@extends('layouts.main')
@section('main')
    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-12">
                    <h3>Tools Equipment</h3>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                        <li class="breadcrumb-item">Tools Equipment Category list</li>
                        <li class="breadcrumb-item active">Add Tools Equipment Category</li>
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
                    <div class="card-body m-0 p-3">
                        <div class="mb-5 mt-2">
                            <form method="post" class="js-form-tools-equipment" action="/tool-equipment-category">
                                <div class="row">
                                    @csrf
                                    @include('equipment_tool_category.form')
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
