@extends('layouts.main')
@section('main')
    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-12">
                    <h4>Man Power</h4>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="index.html">Home</a></li>
                        <li class="breadcrumb-item">Work Item List</li>
                        <li class="breadcrumb-item active">{{$workItem->description}}</li>
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
                    <div class="card-header-costume">
                        <div class="float-start">
                            <label>Man Power {{$workItem->description}}</label>
                        </div>
                    </div>
                    <div class="card-body mt-4 p-3">
                        <div class="mb-5 mt-2">
                            <form method="post"
                                  data-method="put"
                                  data-id="{{$workItem->id}}"
                                  class="js-edit-work-item-man-power"
                                  action="/work-item/{{$workItem->id}}/man-power/update">
                                <div class="row">
                                    @csrf
                                    <div class="col-sm-12 col-lg-12 col-xl-12">
                                        <div class="table-responsive">
                                            <table class="table table-striped js-table-work-item-item">
                                                <thead>
                                                <tr>
                                                    <th scope="col" class="text-left min-w-160 ">Code - Description</th>
                                                    <th scope="col" class="text-left">Unit</th>
                                                    <th scope="col" class="text-left">Coef</th>
                                                    <th scope="col" class="text-left min-w-100">Rate</th>
                                                    <th scope="col" class="text-left min-w-100">Amount</th>
                                                    <th scope="col" class="text-left min-w-30"></th>
                                                </tr>
                                                </thead>
                                                <tbody class="js-table-body-work-item-item">
                                                @if(isset($workItem->manPowers))
                                                    @foreach($workItem->manPowers as $exManPower)
                                                        @include('work_item.work_item_man_power.man_power', ['isEdit' => true])
                                                    @endforeach
                                                @endif
                                                </tbody>
                                            </table>
                                            <table class="table">
                                                <tr>
                                                    <th scope="col" class="text-left min-w-160 "></th>
                                                    <th scope="col" class="text-left"></th>
                                                    <th scope="col" class="text-left"></th>
                                                    <th scope="col" class="text-left min-w-100"></th>
                                                    <th scope="col" class="text-center min-w-100 js-item-total"></th>
                                                </tr>
                                            </table>
                                            <div class="float-end mt-2 cursor-pointer js-add-new-item"
                                                 data-template="#js-template-table-work_item_man_power">
                                                <i class="fa fa-plus-circle"></i> Add New Man Power</div>
                                        </div>
                                    </div>

                                    <div class="mt-5">
                                        <button class="btn btn-success float-end js-save-work-item-man-power js-save-item" style="margin-left: 3px" type="submit">
                                            <div class="loader-box" style="height: auto">
                                                Save <div style="margin-left:3px" class="loader-34 d-none"></div>
                                            </div>
                                        </button>
                                        <a href="/work-item/{{$workItem->id}}"><div class="btn btn-danger float-end">Back</div></a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
