@extends('layouts.main')
@section('main')
    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-6">
                    <h3>Man Powers</h3>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="">Home</a></li>
                        <li class="breadcrumb-item active">Man Power list</li>
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
        <div class="col-sm-12">
            <div class="row">
                <div class="card">
                    <div class="card-body m-0 p-3">
                        <div class="mb-5 mt-2">
                            <form method="get" action="/man-power">
                                <div class="row">
                                    <div class="col-md-4">
                                        <select class="select2 col-sm-12"
                                                data-placeholder="Skill Level">
                                            <option></option>
                                            <option value="WY">Peter</option>
                                            <option value="WY">Hanry Die</option>
                                            <option value="WY">John Doe</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-1">
                                        <input type="text" name="q" placeholder="Project No/Project Name" class="form-control" style="height: 40px">
                                    </div>
                                    <div class="col-md-2 mb-1" >
                                        <button class="btn btn-outline-success btn btn-search-project" style="height: 40px">Search</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="col-sm-12 col-lg-12 col-xl-12">
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th scope="col" class="text-left">Code</th>
                                        <th scope="col" class="text-left">Skill Level</th>
                                        <th scope="col" class="text-left">Title</th>
                                        <th scope="col" class="text-left">Basic Rate Monthly</th>
                                        <th scope="col" class="text-left">Basic Rate Monthly</th>
                                        <th scope="col" class="text-left">Overall Rate Hourly</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($man_power as $item)
                                    <tr>
                                        <td><a href="/man-power/{{$item->id}}" class="font-weight-bold">{{$item->code}}</td>
                                        <td>{{$item->skill_level}}</td>
                                        <td>{{$item->title}}</td>
                                        <td>{{number_format($item->basic_rate_month,2)}}</td>
                                        <td>{{number_format($item->basic_rate_hour,2)}}</td>
                                        <td>{{number_format($item->overall_rate_hourly,2)}}</td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="cols-sm-12 col-lg-12 col-xl-12" style="margin-bottom: 20px"></div>
                </div>
            </div>
        </div>
        @if($man_power->total() > 1)
            <div class="row mb-5">
                <div class="col-md-12">
                    <nav aria-label="Page navigation example float-end">
                        <ul class="pagination">
                            {{$man_power->onEachSide(1)->links('project.pagination')}}
                        </ul>
                    </nav>
                </div>
            </div>
        @endif
    </div>
@endsection
