<?php

namespace App\Services;

use App\Models\EquipmentTools;

class EquipmentToolsServices
{
    public function getEquipmentTools($request,$isCount,$status){
        $order = $request->order;
        $sort =  $request->sort;
        $filter = request(['q','category','status']);
        if($isCount) $filter = request(['q','category']);
        $data = EquipmentTools::with(['equipmentToolsCategory','createdBy.profiles'])->filter($filter)
            ->when(isset($request->sort), function($query) use ($request,$order,$sort){
                return $query->when($request->order == 'category', function($qq) use ($request,$order,$sort){
                    return $qq->whereHas('equipmentToolsCategory',function($relation) use ($sort){
                        $relation->orderBy('description',$sort);
                    });
                })->when($request->order != 'category', function($qq) use ($request,$order, $sort){
                    return $qq->orderBy($order,$sort);
                });
            })->when(!auth()->user()->isToolsEquipmentReviewerRole(), function($query) {
                return $query->where(function ($q) {
                    return $q->where('status', EquipmentTools::REVIEWED)->orWhere('created_by', auth()->user()->id);
                });
            })->when(isset($status), function ($q) use ($status){
                return $q->where('status',$status);
            })->when(!isset($request->sort), function($query) use ($request,$order) {
                return $query->orderBy('equipment_tools.code', 'ASC');
            });

        return $data;

    }
}
