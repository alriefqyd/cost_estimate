@inject('setting',App\Models\Setting::class)

@extends('layouts.main')
@section('main')
    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-6">
                    <h3>Work Breakdown Structure</h3>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="">Home</a></li>
                        <li class="breadcrumb-item active">Setting Work Breakdown Structure</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="container-fluid product-wrapper">
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
                <div class="card">
                    <label class="m-5">{{$isWorkElement ? 'Work Element' : 'Discipline'}}</label>
                    <div class="mt-5 mb-4">
                        <form method="post" action="{{$isWorkElement ? '/work-breakdown-structure/work-element/' : '/work-breakdown-structure'}}">
                            <div class="row">
                                @csrf
                                @include('setting_work_breakdown_structure.form')
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endSection
