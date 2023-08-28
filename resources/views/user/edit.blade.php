@extends('layouts.main')
@section('main')
    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-12">
                    <h4>User</h4>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                        <li class="breadcrumb-item">dashboard</li>
                        <li class="breadcrumb-item">user</li>
                        <li class="breadcrumb-item active">Update User</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- Container-fluid starts-->
    <div class="container-fluid">
        <div class="col-sm-12">
            <div class="row">
                @if($errors->any())
                    <div class="col-md-12 alert alert-danger">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </div>
                @endif
            </div>
            <div class="row">
                @if(session('message'))
                    @include('flash')
                @endif
                <div class="card">
                    <div class="mt-5 mb-4 p-3">
                        <form method="post" action="/user/{{$user->id}}">
                            <div class="row">
                                @csrf
                                @method('put')
                                @include('user.form')
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
<!-- Container-fluid Ends-->
