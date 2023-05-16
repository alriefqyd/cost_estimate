@inject('setting',App\Models\Setting::class)

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
                        <div class="form-group mb-0 me-0"></div><a class="btn btn-outline-primary" href="/man-power/create"> Create New Man Power</a>
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
                                                name="skill_level"
                                                data-placeholder="Skill Level">
                                            <option></option>
                                            @foreach($setting::SKILL_LEVEL as $key => $value)
                                                <option {{isset(request()->skill_level) && request()->skill_level == $key ? 'selected' : ''}} value="{{$key}}">{{$value}}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-6 mb-1">
                                        <input type="text" value="{{request()->q}}" name="q" placeholder="Man Power Code/Title" class="form-control" style="height: 40px">
                                    </div>
                                    <div class="col-md-2 mb-1" >
                                        <input type="submit" class="btn btn-outline-success btn btn-search-man-power" value="search" style="height: 40px"></input>
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
                                        <th scope="col" class="text-left">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($man_power as $item)
                                    <tr>
                                        <td><a href="/man-power/{{$item->id}}" class="font-weight-bold">{{$item->code}}</td>
                                        <td>{{$item->getSkillLevel()}}</td>
                                        <td>{{$item->title}}</td>
                                        <td>{{number_format($item->basic_rate_month,2)}}</td>
                                        <td>{{number_format($item->basic_rate_hour,2)}}</td>
                                        <td>{{number_format($item->overall_rate_hourly,2)}}</td>
                                        <td><a data-bs-toggle="modal" data-original-title="test" data-bs-target="#deleteConfirmationModal"
                                                data-id="{{$item->id}}" class="text-danger js-delete-man-power">Delete</a></td>
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

    <div class="modal fade js-modal-delete-man-power" id="deleteConfirmationModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Delete Man Power</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this item?
                </div>
                <div class="modal-footer">
                    <button class="btn btn-success" type="button" data-bs-dismiss="modal">Close</button>
                    <button class="btn btn-danger js-delete-confirmation-man-power" type="button">Delete</button>
                </div>
            </div>
        </div>
    </div>
@endsection
