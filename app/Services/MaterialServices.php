<?php

namespace App\Services;

use App\Models\Material;

class MaterialServices
{
    public function getMaterial($request, $isCount, $status){
        $order = $request->order;
        $sort =  $request->sort;

        $filter = request(['q','category','status','creator']);
        if($isCount) $filter = request(['q','category','creator']);
        $material = Material::with(['materialsCategory','createdBy.profiles'])->filter($filter)
            ->when(isset($request->sort), function($query) use ($request,$order,$sort) {
                return $query->when($request->order == 'category', function ($qq) use ($request, $order, $sort) {
                    return $qq->whereHas('materialsCategory', function ($relation) use ($sort) {
                        $relation->orderBy('description', $sort);
                    });
                })->when($request->order != 'category', function ($qq) use ($request, $order, $sort) {
                    return $qq->orderBy($order, $sort);
                });
            })->when(!auth()->user()->isMaterialReviewerRole(), function($query){
                return $query->where(function($subQuery){
                    return $subQuery->where('status',Material::REVIEWED)
                        ->orWhere('created_by', auth()->user()->id);
                });
            })->when(isset($status), function ($q) use ($status) {
                return $q->where('status', $status);
            })->when(!isset($request->sort), function($query) use ($request,$order) {
                return $query->orderBy('code', 'ASC');
            });

        return $material;
    }
}
