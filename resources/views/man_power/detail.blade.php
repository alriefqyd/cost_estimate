@extends('layouts.main')
@section('main')
    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-6">
                    <h4>Man Powers</h4>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">Home</a></li>
                        <li class="breadcrumb-item"><a href="/man-power/">Man Power List</a></li>
                        <li class="breadcrumb-item active">{{$man_power->title}}</li>
                    </ol>
                </div>
                @if(auth()->user()->isManPowerReviewer() && $man_power->status === App\Models\ManPower::DRAFT)
                    <div class="col-md-6 col-sm-6 text-end d-flex justify-content-end align-items-center gap-2">
                        <button type="button" class="btn btn-review-list js-add-to-review-cart" title="Add to Review List"
                                data-entity="manPower" data-id="{{$man_power->id}}"
                                data-code="{{$man_power->code}}" data-label="{{$man_power->title}}">
                            <i class="fa fa-flag me-1"></i> <span class="js-review-list-label">Add to Review List</span>
                        </button>
                        <button type="button" class="btn btn-success js-direct-approve" title="Set to Review"
                                data-entity="manPower" data-id="{{$man_power->id}}">
                            <i class="fa fa-check me-1"></i> Set to Review
                        </button>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div class="container-fluid product-wrapper">
        <div class="col-sm-12">
            <div class="row js-confirm-row">
                @if($errors->any())
                    <div class="col-md-12 alert alert-danger">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </div>
                @endif
                @if(session('message'))
                    @include('flash')
                @endif
                <div class="card">
                    <div class="card-body m-0 p-3">
                        <div class="mb-5 mt-2">
                            <form method="post" action="/man-power/{{$man_power->id}}">
                                <div class="row">
                                    @csrf
                                    @method('put')
                                    @include('man_power.form')
{{--                                    <button type="submit" class="btn btn-success float-end">Save Data</button>--}}
{{--                                    <button type="" class="btn btn-light float-end m-r-5">Cancel</button>--}}
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
