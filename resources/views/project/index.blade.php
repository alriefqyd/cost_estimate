@extends('layouts.main')
@section('main')
<div class="container-fluid">
    <div class="page-header">
        <div class="row">
            <div class="col-sm-6">
                <h4>Project Management</h4>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                    <li class="breadcrumb-item active">project list</li>
                </ol>
            </div>
            @can('create', App\Models\Project::class)
                <div class="col-md-6 col-sm-6 text-end"><span class="f-w-600 m-r-5"></span>
                    <div class="select2-drpdwn-product select-options d-inline-block">
                        <div class="form-group mb-0 me-0"></div><a class="btn btn-outline-primary" href="/project/create"> Create New Cost Estimate</a>
                    </div>
                </div>
            @endcan
        </div>
    </div>
</div>
<div class="container-fluid product-wrapper">
    <div class="row project-cards">
        @if(session('message'))
            @include('flash')
        @endif
        <div class="card">
            <div class="card-body m-0 card-body-custom">
                <div class="mb-5 mt-2">
                    <label>Filter By</label>
                    <form class="js-form-project-search" method="get" action="/project">
                        <div class="row margin-05">
                            <div class="col-md-3 mb-1-responsive">
                                <select class="select2 col-sm-12 js-search-form"
                                        name="sponsor"
                                        data-placeholder="Project Sponsor">
                                    <option>Process Plant</option>
                                    <option>Utilities</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <input type="text" name="q" value="{{request()->q}}" placeholder="Project No/Project Name" class="form-control js-search-form" style="height: 40px">
                            </div>
                            {{--<div class="col-md-2 mb-1" >
                                <button class="btn btn-outline-success btn btn-search-project" style="height: 40px">Search <i class="fa fa-search"></i></button>
                            </div>--}}
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-2 mb-1-responsive">
                                <select class="select2 col-sm-12 js-search-form"
                                        name="mechanical"
                                        data-placeholder="Mechanical">
                                    <option disabled selected></option>
                                    @foreach($mechanicalEngineerList as $data)
                                        <option value="{{$data->id}}" {{request()->mechanical == $data->id ? 'selected' : ''}}>{{$data->profiles->full_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2 mb-1 mb-1-responsive">
                                <select class="select2 col-sm-12 js-search-form"
                                        name="civil"
                                        data-placeholder="Civil">
                                    <option disabled selected></option>
                                    @foreach($civilEngineerList as $civil)
                                        <option value="{{$civil->id}}" {{request()->civil == $civil->id ? 'selected' : ''}}>{{$civil->profiles->full_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2 mb-1-responsive">
                                <select class="select2 col-sm-12 js-search-form"
                                        name="electrical"
                                        data-placeholder="Electrical">
                                    <option disabled selected></option>
                                    @foreach($electricalEngineerList as $data)
                                        <option value="{{$data->id}}" {{request()->electrical == $data->id ? 'selected' : ''}}>{{$data->profiles->full_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-2 mb-1-responsive">
                                <select class="select2 col-sm-12 js-search-form"
                                        name="instrument"
                                        data-placeholder="Instrument">
                                    <option disabled selected></option>
                                    @foreach($instrumentEngineerList as $data)
                                        <option value="{{$data->id}}" {{request()->instrument == $data->id ? 'selected' : ''}}>{{$data->profiles->full_name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="btn-group btn-group-square" role="group" aria-label="Basic example">
                                    <input type="hidden" name="status" value="{{request()->status}}" class="js-status-filter">
                                    <button class="btn btn-outline-light txt-dark {{request()->status == 'DRAFT' ? 'active' : ''}} js-btn-status" data-value="DRAFT" type="button">
                                        Draft ({{$projectDraft}})
                                    </button>
                                    <button class="btn btn-outline-light txt-dark {{request()->status == 'PUBLISH' ? 'active' : ''}} js-btn-status" data-value="PUBLISH" type="button">
                                        Publish ({{$projectPublish}})
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
            </div>
                @include('project.table')
            </div>
        </div>
    </div>
    @if($projects->total() > 1)
        <div class="row mb-5">
            <div class="col-md-12">
                <nav aria-label="Page navigation example float-end">
                    <ul class="pagination">
                        {{$projects->onEachSide(1)->links('project.pagination')}}
                    </ul>
                </nav>
            </div>
        </div>
    @endif
</div>
<!-- Container-fluid Ends-->
@endsection

