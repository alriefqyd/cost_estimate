@inject('workItem', App\Models\WorkItem::class)
@extends('layouts.main')
@section('main')
    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-6">
                    <h4>Work Item</h4>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="/">Home</a></li>
                        <li class="breadcrumb-item"><a href="/work-item">Work Item List</a></li>
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
                                <tr>
                                    <td class="min-w-250">Status</td>
                                    <td>
                                        <label class="js-status-work-item m-1">{{$work_item->status}}</label>
                                        @if(auth()->user()->profiles?->position == 'project_manager')
                                        <i class="fa fa-pencil-square-o js-btn-edit-status-work-item cursor-pointer"></i>
                                            <div class="col-md-3 js-select-status-work-item d-none">
                                                <select class="select2 js-select-work-item col-md-3">
                                                    <option value="{{$workItem::DRAFT}}" {{$work_item->status == $workItem::DRAFT ? 'selected' : ''}}>{{$workItem::DRAFT}}</option>
                                                    <option value="{{$workItem::REVIEWED}}" {{$work_item->status == $workItem::REVIEWED ? 'selected' : ''}}>{{$workItem::REVIEWED}}</option>
                                                </select>
                                            </div>

                                            <div class="modal fade" id="approveModalWorkItem" tabindex="-1" role="dialog" aria-labelledby="approveModalLabel" aria-hidden="true">
                                                <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">Approve work item</h5>
                                                            <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">Are you sure you want to approve this work item?</div>
                                                        <div class="modal-footer">
                                                            <button class="btn btn-danger" type="button" data-bs-dismiss="modal">Close</button>
                                                            <button class="btn btn-success js-btn-approve-work-item" type="button">Approve</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endif
                                    </td>
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
                                <a href="/work-item/{{$work_item?->id}}/work-item-man-power/edit">
                                    <button class="btn btn-outline-success">
                                        Edit
                                    </button>
                                </a>
                            @else
                                <a href="/work-item/{{$work_item?->id}}/work-item-man-power/">
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
                                        <th>Rate (IDR)</th>
                                        <th>Amount (IDR)</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($work_item->manPowers as $manPower)
                                        <tr>
                                            <td>{{$manPower->title}}</td>
                                            <td>{{$manPower->pivot?->labor_unit}}</td>
                                            <td>{{$manPower->pivot?->labor_coefisient}}</td>
                                            <td>{{number_format($manPower->overall_rate_hourly,2,',','.')}}</td>
                                            <td>{{number_format($manPower->getAmount(),2,',','.')}}</td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td><label>Total :</label></td>
                                        <td colspan="4" class="text-end">
                                            <label>Rp @currencyFormat($work_item->getTotalCostManPower())</label>
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
                                    <a href="/work-item/{{$work_item?->id}}/work-item-tools-equipment/edit">
                                        <button class="btn btn-outline-success">
                                            Edit
                                        </button>
                                    </a>
                                @else
                                    <a href="/work-item/{{$work_item?->id}}/work-item-tools-equipment/">
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
                                            <td> @currencyFormat($tools->pivot?->amount)</td>
                                            <td></td>
                                        </tr>
                                    @endforeach
                                    <tr>
                                        <td><label>Total :</label></td>
                                        <td colspan="6" class="text-end">
                                            <label>Rp @currencyFormat($work_item->getTotalCostEquipment())</label>
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
                                    <a href="/work-item/{{$work_item?->id}}/work-item-material/edit">
                                        <button class="btn btn-outline-success">
                                            Edit
                                        </button>
                                    </a>
                                @else
                                    <a href="/work-item/{{$work_item?->id}}/work-item-material/">
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
                                        <td>{{number_format($material->getAmount(),2,',','.')}}</td>
                                    </tr>
                                @endforeach
                                <tr>
                                    <td><label>Total :</label></td>
                                    <td colspan="5" class="text-end">
                                        <label>Rp @currencyFormat($work_item->getTotalCostMaterial())</label>
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
