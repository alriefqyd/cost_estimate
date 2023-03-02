@extends('layouts.main')
@section('main')
<div class="container-fluid">
    <div class="page-header">
        <div class="row">
            <div class="col-sm-6">
                <h3>project Management</h3>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                    <li class="breadcrumb-item active">project list</li>
                </ol>
            </div>
            <div class="col-md-6 col-sm-6 text-end"><span class="f-w-600 m-r-5"></span>
                <div class="select2-drpdwn-product select-options d-inline-block">
                    <div class="form-group mb-0 me-0"></div><a class="btn btn-outline-primary" href="/project/create"> Create New Cost Estimate</a>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="container-fluid product-wrapper">
    <div class="row project-cards">

        <div class="col-sm-12">
            <div class="card">
                <div class="card-body m-0 p-3">
                    <div class="mb-5 mt-2">
                        <div class="row">
                            <div class="col-md-1">
                                <label>Filter By</label>
                            </div>
                            <div class="col-md-3 mb-1 mb-1-responsive">
                                <select class="select2 col-sm-12"
                                        data-placeholder="Project Sponsor">
                                    <option></option>
                                    <option value="WY">Peter</option>
                                    <option value="WY">Hanry Die</option>
                                    <option value="WY">John Doe</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-1">
                                <input type="text" placeholder="Project No/Project Name" class="form-control" style="height: 40px">
                            </div>
                            <div class="col-md-2 mb-1" >
                                <button class="btn btn-outline-success" style="height: 40px">Search</button>
                            </div>
                        </div>
                    </div>
                    @include('project.table')
                </div>
            </div>
        </div>
        @if(sizeof($projects) > 10)
            <div class="col-md-12">
                <div class="card">
                    <div class="row">
                        <div class="col-md-6 p-0">
                        </div>
                        <div class="col-md-6 p-0">
                            {{$projects->onEachSide(1)->links()}}
                        </div>
                    </div>
                </div>
            </div>
        @endif
    </div>
</div>
<!-- Container-fluid Ends-->
@endsection

