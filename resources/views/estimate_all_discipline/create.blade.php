@extends('layouts.main')
@section('main')
    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-12">
                    <h4>Estimate Discipline</h4>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                        <li class="breadcrumb-item">dashboard</li>
                        <li class="breadcrumb-item">project</li>
                        <li class="breadcrumb-item active">New Estimate Discipline</li>
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
                    <div class="card-body p-3">
                        <h6 class="float-start">Project : {{$project->project_title}}</h6>
                        @if($project->wbsLevel3s())
                            <a href="/project/{{$project->id}}/wbs/edit">
                                <button class="float-end btn btn-primary">
                                    Work Breakdown Structure
                                </button>
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            @if($errors->any())
                <div class="col-md-12 alert alert-danger">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </div>
            @endif
        </div>
        @include('estimate_all_discipline.form')
    </div>
@endsection
<!-- Container-fluid Ends-->
