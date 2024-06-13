<?php

namespace App\Class;

use App\Models\ManPower;

class ManPowerServices
{
    public function getManPower($request,$isCount,$status){
        $order = $request->order;
        $sort =  $request->sort;
        $filter = request(['q','skill_level','status']);
        if($isCount) $filter = request(['q','skill_level']);
        $data = $man_power = ManPower::with(['createdBy'])->filter($filter)
            ->when(isset($request->sort), function($query) use ($request,$order,$sort){
                return $query->orderBy($order,$sort);
            })->when(!isset($request->sort), function($query) use ($request,$order) {
                return $query->orderBy('code', 'ASC');
            })->when(!auth()->user()->isManPowerReviewer(), function($query) {
                return $query->where(function ($q) {
                    return $q->where('status', ManPower::REVIEWED)->orWhere('created_by', auth()->user()->id);
                });
            })->when(isset($status), function ($q) use ($status){
                return $q->where('status', $status);
            })->orderBy('code', 'ASC');

        return $data;
    }
}
