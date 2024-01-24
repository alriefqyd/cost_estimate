@extends('layouts.main')
@section('main')
    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-6">
                    <h4>Project Management</h4>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">Home</a></li>
                        <li class="breadcrumb-item"><a href="/project">Cost Estimate list</a></li>
                        <li class="breadcrumb-item"><a href="/project/{{$project->id}}">{{\Illuminate\Support\Str::limit($project->project_title,60)}}</a></li>
                        <li class="breadcrumb-item active">Edit Project Info</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- Container-fluid starts-->
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12">
                @if(session('message'))
                    @include('flash')
                @endif
                <div class="card">
                    <div class="card-body">
                        <div class="row m-b-50">
                            @if($errors->any())
                                <div class="col-md-12 alert alert-danger">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </div>
                            @endif
                        </div>

                        <div class="row">
                            <form class="needs-validation js-add-project-form" method="post" action="/project/{{$project->id}}" novalidate="">
                                @csrf
                                @method('put')
                                @include('project.form')
                                <button type="submit" class="btn btn-success float-end">Save Data</button>
                                <a href="/project/{{$project->id}}/"><div class="btn btn-danger float-end m-r-5">Cancel</div></a>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
<!-- Container-fluid Ends-->
