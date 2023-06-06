@inject('setting',App\Models\Setting::class)

@extends('layouts.main')
@section('main')
    <div class="container-fluid">
        <div class="page-header">
            <div class="row">
                <div class="col-sm-6">
                    <h3>Material</h3>
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="">Home</a></li>
                        <li class="breadcrumb-item active">Material list</li>
                    </ol>
                </div>
                @can('create',App\Models\Material::class)
                    <div class="col-md-6 col-sm-6 text-end"><span class="f-w-600 m-r-5"></span>
                        <div class="select2-drpdwn-product select-options d-inline-block">
                            <div class="form-group mb-0 me-0"></div><a class="btn btn-outline-primary" href="/material/create"> Create New Material</a>
                        </div>
                    </div>
                @endcan
            </div>
        </div>
    </div>
    <div class="container-fluid product-wrapper">
        <div class="col-sm-12">
            <div class="row">
                @if(session('message'))
                    @include('flash')
                @endif
                <div class="card">
                    <div class="mt-5 mb-4">
                        <form method="get" action="/material">
                            <div class="row">
                                <div class="col-md-4">
                                    <select class="select2 col-sm-12"
                                            name="category"
                                            data-placeholder="Category">
                                        <option></option>
                                        @foreach($material_category as $mc)
                                            <option {{isset(request()->category) && request()->category == $mc->id ? 'selected' : ''}} value="{{$mc->id}}">{{$mc->description}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-6 mb-1">
                                    <input type="text" value="{{request()->q}}" name="q" placeholder="Code / Description / Stock Code / Ref Material Numbar" class="form-control" style="height: 40px">
                                </div>
                                <div class="col-md-1 mb-1" >
                                    <input type="hidden" name="order" value="{{request()->order}}" class="js-filter-order">
                                    <input type="hidden" name="sort" value="{{request()->sort}}" class="js-filter-sort">
                                    <input type="submit" class="btn btn-outline-success btn btn-search-man-power" value="search" style="height: 40px"></input>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="col-sm-12 col-lg-12 col-xl-12">
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th scope="col" class="text-left">Code <i class="fa fa-sort cursor-pointer js-order-sort" data-sort="code"></i></th>
                                        <th scope="col" class="text-left">Category <i class="fa fa-sort cursor-pointer js-order-sort" data-sort="category"></i></th>
                                        <th scope="col" class="text-left">Tool & Equip Description <i class="fa fa-sort cursor-pointer js-order-sort" data-sort="tool_equipment_description"></i></th>
                                        <th scope="col" class="text-left">Qty <i class="fa fa-sort cursor-pointer js-order-sort" data-sort="quantity"></i></th>
                                        <th scope="col" class="text-left">Unit <i class="fa fa-sort cursor-pointer js-order-sort" data-sort="unit"></i></th>
                                        <th scope="col" class="text-left">Rate <i class="fa fa-sort cursor-pointer js-order-sort" data-sort="rate"></i></th>
                                        <th scope="col" class="text-left">Ref Material Num <i class="fa fa-sort cursor-pointer js-order-sort" data-sort="ref_material_number"></i></th>
                                        <th scope="col" class="text-left">Stock Code <i class="fa fa-sort cursor-pointer js-order-sort" data-sort="stock_code"></i></th>
                                        @can('delete',App\Models\Material::class)
                                            <th scope="col" class="text-left">Action</th>
                                        @endcan
                                    </tr>
                                </thead>
                                <tbody>
                                @foreach($material as $item)
                                    <tr>
                                        <td><a href="/material/{{$item->id}}" class="font-weight-bold">{{$item->code}}</td>
                                        <td>{{$item?->materialsCategory?->description}}</td>
                                        <td class="min-w-200">{{$item->tool_equipment_description}}</td>
                                        <td class="min-w-50">{{$item->quantity}}</td>
                                        <td class="min-w-65">{{$item->unit}}</td>
                                        <td>{{number_format($item->rate,2)}}</td>
                                        <td class="min-w-150">{{$item->ref_material_number}}</td>
                                        <td class="min-w-120">{{$item->stock_code}}</td>
                                        @can('delete',App\Models\Material::class)
                                        <td><a data-bs-toggle="modal" data-original-title="test" data-bs-target="#deleteConfirmationModal"
                                                data-id="{{$item->id}}" class="text-danger js-delete-material">Delete</a></td>
                                        @endcan
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
        @if($material->total() > 1)
            <div class="row mb-5">
                <div class="col-md-12">
                    <nav aria-label="Page navigation example float-end">
                        <ul class="pagination">
                            {{$material->onEachSide(1)->links('project.pagination')}}
                        </ul>
                    </nav>
                </div>
            </div>
        @endif
    </div>

    <div class="modal fade js-modal-delete-material" id="deleteConfirmationModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Delete Material</h5>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Are you sure you want to delete this item?
                </div>
                <div class="modal-footer">
                    <button class="btn btn-success" type="button" data-bs-dismiss="modal">Close</button>
                    <button class="btn btn-danger js-delete-confirmation-material" type="button">Delete</button>
                </div>
            </div>
        </div>
    </div>
@endsection
