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
                                <select class="select2 col-sm-12"
                                        data-placeholder="Project Sponsor">
                                    <option>Process Plant</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <input type="text" name="q" value="{{request()->q}}" placeholder="Project No/Project Name" class="form-control" style="height: 40px">
                            </div>
                            {{--<div class="col-md-2 mb-1" >
                                <button class="btn btn-outline-success btn btn-search-project" style="height: 40px">Search <i class="fa fa-search"></i></button>
                            </div>--}}
                        </div>
                        <div class="row mb-2">
                            <div class="col-md-2 mb-1-responsive">
                                <select class="select2 col-sm-12"
                                        data-placeholder="Mechanical">
                                    <option>Mechanical Engineer</option>
                                </select>
                            </div>
                            <div class="col-md-2 mb-1 mb-1-responsive">
                                <select class="select2 col-sm-12"
                                        data-placeholder="Civil">
                                    <option>Civil Engineer</option>
                                </select>
                            </div>
                            <div class="col-md-2 mb-1-responsive">
                                <select class="select2 col-sm-12"
                                        data-placeholder="Electrical">
                                    <option>Electrical Engineer</option>
                                </select>
                            </div>
                            <div class="col-md-2 mb-1-responsive">
                                <select class="select2 col-sm-12"
                                        data-placeholder="Instrument">
                                    <option>Instrument Engineer</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-3">
                                <div class="btn-group btn-group-square" role="group" aria-label="Basic example">
                                    <button class="btn btn-outline-light txt-dark active" type="button">Draft (90)</button>
                                    <button class="btn btn-outline-light txt-dark" type="button">Publish (101)</button>
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

