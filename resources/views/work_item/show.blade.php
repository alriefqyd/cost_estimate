@extends('layouts.main')
@section('main')
    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-6">
                    <h4>Work Item</h4>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="">Home</a></li>
                        <li class="breadcrumb-item">Work Item Detail</li>
                        <li class="breadcrumb-item active">{{$work_item->code}}</li>
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
                <div class="card">
                    <div class="card-header-costume">
                        <div class="float-start">
                            <label>Work Item Detail</label>
                        </div>
                        @can('update',App\Models\WorkItem::class)
                            <div class="float-end">
                                <a href="/work-item/edit/{{$work_item->id}}">
                                    <div class="btn btn-outline-success">Edit</div>
                                </a>
                            </div>
                        @endcan
                    </div>
                    <div class="col-md-12 mt-3 mb-3">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <tr>
                                    <td class="min-w-250">code :</td>
                                    <td>{{$work_item->code}}</td>
                                </tr>
                                <tr>
                                    <td class="min-w-250">Work Type :</td>
                                    <td>{{$work_item->workItemTypes->title}}</td>
                                </tr>
                                <tr>
                                    <td class="min-w-250">Description :</td>
                                    <td>{{$work_item->description}}</td>
                                </tr>
                                <tr>
                                    <td class="min-w-250">Volume :</td>
                                    <td>{{$work_item->volume}}</td>
                                </tr>
                                <tr>
                                    <td class="min-w-250">Unit :</td>
                                    <td>{{$work_item->unit}}</td>
                                </tr>
                                <tr>
                                    <td class="min-w-250">Total Work Item Price :</td>
                                    <td> {{number_format($work_item?->getTotalSum(),2,',','.')}}</td>
                                </tr>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header-costume">
                        <div class="float-start">
                            <label>Man Power</label>
                        </div>
                        @canAny(['update','create'],App\Models\WorkItem::class)
                        <div class="float-end">
                            @if(sizeof($work_item->manPowers) > 0)
                                <a href="/work-item/{{$work_item?->id}}/man-power/edit">
                                    <button class="btn btn-outline-success">
                                        Edit
                                    </button>
                                </a>
                            @else
                                <a href="/work-item/{{$work_item?->id}}/man-power/">
                                    <button class="btn btn-outline-success">
                                        Create
                                    </button>
                                </a>
                            @endif
                        </div>
                        @endcan
                    </div>
                    <div class="col-md-12 mt-3 mb-5">
                        <div class="table-responsive mt-3">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Description</th>
                                        <th>Unit</th>
                                        <th>Coef</th>
                                        <th>Rate</th>
                                        <th>Amount</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($work_item->manPowers as $manPower)
                                        <tr>
                                            <td>{{$manPower->title}}</td>
                                            <td>{{$manPower->pivot?->labor_unit}}</td>
                                            <td>{{$manPower->pivot?->labor_coefisient}}</td>
                                            <td>{{number_format($manPower->overall_rate_hourly,2,',','.')}}</td>
                                            <td>{{number_format($manPower->pivot?->amount,2,',','.')}}</td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td><label>Total :</label></td>
                                        <td colspan="4" class="text-end">
                                            <label>{{number_format($work_item->manPowers->sum('pivot.amount'),2,'.',',')}}</label>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header-costume">
                        <div class="float-start">
                            <label>Tools & Equipment</label>
                        </div>
                        <div class="float-end">
                            @canAny(['update','create'],App\Models\WorkItem::class)
                                @if(sizeof($work_item->equipmentTools) > 0)
                                    <a href="/work-item/{{$work_item?->id}}/tools-equipment/edit">
                                        <button class="btn btn-outline-success">
                                            Edit
                                        </button>
                                    </a>
                                @else
                                    <a href="/work-item/{{$work_item?->id}}/tools-equipment/">
                                        <button class="btn btn-outline-success">
                                            Create
                                        </button>
                                    </a>
                                @endif
                            @endcan
                        </div>
                    </div>
                    <div class="col-md-12 mt-3 mb-5">
                        <div class="table-responsive mt-3">
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th>Code</th>
                                    <th>Description</th>
                                    <th>Unit</th>
                                    <th>Quantity</th>
                                    <th>Unit Price</th>
                                    <th>Amount</th>
                                </tr>
                                </thead>
                                <tbody>
                                    @foreach($work_item->equipmentTools as $tools)
                                        <tr>
                                            <td>{{$tools->code}}</td>
                                            <td>{{$tools->description}}</td>
                                            <td>{{$tools->pivot?->unit}}</td>
                                            <td>{{number_format($tools->pivot?->quantity,2,',','.')}}</td>
                                            <td>{{number_format($tools->local_rate,2,',','.')}}</td>
                                            <td>{{number_format($tools->pivot?->amount,2,',','.')}}</td>
                                            <td></td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td><label>Total :</label></td>
                                        <td colspan="5" class="text-end">
                                            <label>{{number_format($work_item->equipmentTools->sum('pivot.amount'),2,'.',',')}}</label>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header-costume">
                        <div class="float-start">
                            <label>Material</label>
                        </div>
                        <div class="float-end">
                            @canAny(['update','create'],App\Models\WorkItem::class)
                                @if(sizeof($work_item->materials) > 0)
                                    <a href="/work-item/{{$work_item?->id}}/material/edit">
                                        <button class="btn btn-outline-success">
                                            Edit
                                        </button>
                                    </a>
                                @else
                                    <a href="/work-item/{{$work_item?->id}}/material/">
                                        <button class="btn btn-outline-success">
                                            Create
                                        </button>
                                    </a>
                                @endif
                            @endcan
                        </div>
                    </div>
                    <div class="col-md-12 mt-3 mb-5">
                        <div class="table-responsive mt-3">
                            <table class="table table-striped">
                                <thead>
                                <tr>
                                    <th>Code</th>
                                    <th>Description</th>
                                    <th>Unit</th>
                                    <th>Quantity</th>
                                    <th>Unit Rate</th>
                                    <th>Amount</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach($work_item->materials as $material)
                                    <tr>
                                        <td>{{$material->code}}</td>
                                        <td>{{$material->tool_equipment_description}}</td>
                                        <td>{{$material->pivot?->unit}}</td>
                                        <td>{{number_format($material->pivot?->quantity,2,',','.')}}</td>
                                        <td>{{number_format($material->rate,2,',','.')}}</td>
                                        <td>{{number_format($material->pivot?->amount,2,',','.')}}</td>
                                        <td></td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td><label>Total :</label></td>
                                    <td colspan="5" class="text-end">
                                        <label>{{number_format($work_item->materials->sum('pivot.amount'),2,'.',',')}}</label>
                                    </td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
