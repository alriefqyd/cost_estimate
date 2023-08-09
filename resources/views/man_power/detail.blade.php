@extends('layouts.main')
@section('main')
    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-6">
                    <h4>Man Powers</h4>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="">Home</a></li>
                        <li class="breadcrumb-item">Man Power Detail</li>
                        <li class="breadcrumb-item active">{{$man_power->title}}</li>
                    </ol>
                </div>
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
