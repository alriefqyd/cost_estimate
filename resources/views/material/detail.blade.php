@extends('layouts.main')
@section('main')
    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-6">
                    <h4>Material</h4>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="">Home</a></li>
                        <li class="breadcrumb-item"><a href="/material">Material list</a></li>
                        <li class="breadcrumb-item active">{{\Illuminate\Support\Str::limit($material->tool_equipment_description, 60)}}</li>
                    </ol>
                </div>
                @if(auth()->user()->isMaterialReviewerRole() && $material->status === App\Models\Material::DRAFT)
                    <div class="col-md-6 col-sm-6 text-end d-flex justify-content-end align-items-center gap-2">
                        <button type="button" class="btn btn-review-list js-add-to-review-cart" title="Add to Review List"
                                data-entity="material" data-id="{{$material->id}}"
                                data-code="{{$material->code}}" data-label="{{$material->tool_equipment_description}}">
                            <i class="fa fa-flag me-1"></i> <span class="js-review-list-label">Add to Review List</span>
                        </button>
                        <button type="button" class="btn btn-success js-direct-approve" title="Set to Review"
                                data-entity="material" data-id="{{$material->id}}">
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
                            <form method="post" action="/material/{{$material->id}}">
                                <div class="row">
                                    @csrf
                                    @method('put')
                                    @include('material.form')
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
                <div class="card">
                    <div class="card-header-costume">
                        <div class="float-start">
                            <label>History</label>
                        </div>
                    </div>
                    <div class="col-md-12 mt-3 mb-5">
                        @php $groupedChangeLogs = $changeLogs->groupBy(fn($log) => $log->created_at->format('d F Y')); @endphp
                        @forelse($groupedChangeLogs as $date => $logsForDate)
                            <div class="mb-3">
                                <h6 class="mb-2">{{$date}}</h6>
                                <ul class="mb-0">
                                    @foreach($logsForDate as $log)
                                        <li>
                                            <span class="text-muted">{{$log->created_at->format('H:i')}}</span>
                                            — <strong>{{$log->user?->profiles?->full_name ?? $log->user?->name ?? 'System'}}</strong>
                                            : {{$log->description}}
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @empty
                            <p class="text-center text-muted mb-0">No history yet</p>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
