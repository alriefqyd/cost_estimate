@extends('layouts.main')
@section('main')
<div class="container-fluid">
    <div class="page-header">
        <div class="row">
            <div class="col-sm-6">
                <h3>project Info</h3>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                    <li class="breadcrumb-item">dashboard</li>
                    <li class="breadcrumb-item">Cost Estimate list</li>
                    <li class="breadcrumb-item active">Add Project Info</li>
                </ol>
            </div>
        </div>
    </div>
</div>
<!-- Container-fluid starts-->
<div class="container-fluid">
    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <div class="row mb-5">
                        @if($errors->any())
                            <div class="col-md-12 alert alert-danger">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </div>
                        @endif
                    </div>
                    <div class="row">
                        <form class="needs-validation js-add-project-form" method="post" action="/project" novalidate="">
                            @csrf
                            @include('project.form')
                            <button type="submit" class="btn btn-success">Save Data</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
<!-- Container-fluid Ends-->
