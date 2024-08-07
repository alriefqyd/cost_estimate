@extends('layouts.main')
@section('main')
    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-6">
                    <h4>Guidelines</h4>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">Home</a> </li>
                        <li class="breadcrumb-item active">Guidelines</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid">
        <div class="row">
            <div class="col-xl-3 box-col-6 pe-0">
                <div class="job-sidebar">
                    <a class="btn btn-primary job-toggle"></a>
                    <div class="job-left-aside custom-scrollbar">
                        <div class="file-sidebar">
                            <div class="card">
                                <div class="card-body">
                                    <div id="jstree">
                                        <ul>
                                            <li>Home</li>
                                            <li>Cost Estimate
                                                <ul>
                                                    <li>Cost Estimate Overview</li>
                                                    <li>Create Cost Estimate</li>
                                                </ul>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-9 col-md-9 box-col-9">
                <div class="file-content">
                    <div class="card">
                        <div class="card-body js-content-guidelines">
                            {!! $page !!}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

