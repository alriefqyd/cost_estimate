<?php

namespace App\Services;

use App\Class\WorkItemClass;
use App\Class\WorkItemRelationItemListClass;
use App\Models\WorkItem;
use Exception;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;

class WorkItemServices
{
    public function getWorkItem($request, $isCount, $status){
        $order = $request->order;
        $sort =  $request->sort;
        $filter = request(['q','category','status','creator']);
        if($isCount) $filter = request(['q','category','creator']);
        $workItem = WorkItem::leftJoin('work_item_types','work_items.work_item_type_id','work_item_types.id')->filter($filter)
            ->leftJoin('users', 'work_items.created_by', '=', 'users.id')
            ->leftJoin('profiles', 'users.id', '=', 'profiles.user_id')
            ->leftJoin('users as reviewer_user', 'work_items.reviewed_by', '=', 'reviewer_user.id')
            ->leftJoin('profiles as reviewer', 'reviewer_user.id', '=', 'reviewer.user_id')
            ->when(isset($request->sort), function($query) use ($request,$order,$sort) {
                return $query->when($request->order == 'work_items.volume', function ($q) use ($request, $order, $sort) {
                    return $q->orderByRaw('CONVERT(work_items.volume, SIGNED)' . $sort);
                })->when($request->sort != 'work_items.volume', function ($q) use ($request, $order, $sort) {
                    return $q->orderBy($order, $sort);
                });
            })->when(!auth()->user()->isWorkItemReviewer(), function($query) {
                return $query->where(function ($q) {
                    return $q->where('status', WorkItem::REVIEWED)->orwhere('created_by', auth()->user()->id);
                });
            })->when(isset($status), function ($q) use ($status) {
                return $q->where('status', $status);
            })->when(!isset($request->sort), function($query) use ($request,$order){
                return $query->orderBy('work_items.code','ASC');
            })->select(
                'work_items.code',
                'work_items.description',
                'work_items.id',
                'work_item_types.title as category',
                'work_items.volume',
                'work_items.unit',
                'work_items.status',
                'profiles.full_name as creator_name',
                'reviewer.full_name as reviewer_name'
            );
        return $workItem;
    }

    public function getWorkItemList()
    {
        try {
            $processWorkItem = collect(); // Use a collection to store results

            WorkItem::with(['manPowers', 'equipmentTools', 'materials', 'workItemTypes','createdBy.profiles'])
                ->chunk(100, function ($workItems) use ($processWorkItem) {
                    foreach ($workItems as $workItem) {
                        $manPowers = [];
                        $equipments = [];
                        $materials = [];

                        foreach ($workItem->manPowers as $mp) {
                            $subItem = new WorkItemRelationItemListClass();
                            $subItem->id = $mp->id;
                            $subItem->description = $mp->title;
                            $subItem->unit = $mp->pivot->labor_unit;
                            $subItem->coef = $mp->pivot->labor_coefisient;
                            $subItem->unit_price = $mp->overall_rate_hourly;
                            $subItem->amount = (float) $mp->pivot->labor_coefisient * $mp->overall_rate_hourly;
                            array_push($manPowers, $subItem);
                        }

                        foreach ($workItem->equipmentTools as $et) {
                            $subItem = new WorkItemRelationItemListClass();
                            $subItem->id = $et->id;
                            $subItem->description = $et->description;
                            $subItem->unit = $et->pivot->unit;
                            $subItem->unit_price = $et->pivot->unit_price;
                            $subItem->quantity = $et->pivot->quantity;
                            $subItem->amount = $et->pivot->amount;
                            array_push($equipments, $subItem);
                        }

                        foreach ($workItem->materials as $et) {
                            $subItem = new WorkItemRelationItemListClass();
                            $subItem->id = $et->id;
                            $subItem->description = $et->tool_equipment_description;
                            $subItem->unit = $et->unit;
                            $subItem->unit_price = $et->rate;
                            $subItem->quantity = $et->pivot->quantity;
                            $subItem->amount = $et->pivot->amount;
                            array_push($materials, $subItem);
                        }

                        $workItemClass = new WorkItemClass();
                        $workItemClass->id = $workItem->id;
                        $workItemClass->code = $workItem->code;
                        $workItemClass->status = $workItem->status;
                        $workItemClass->createdBy = $workItem->createdBy?->profiles?->full_name;
                        $workItemClass->workItemDescription = $workItem->description;
                        $workItemClass->volume = $workItem->volume;
                        $workItemClass->workItemType = $workItem->workItemTypes->title;
                        $workItemClass->workItemTypeId = $workItem->work_item_type_id;
                        $workItemClass->reviewedBy = $workItem->reviewed_by;
                        $workItemClass->manPowerList = $manPowers;
                        $workItemClass->materialList = $materials;
                        $workItemClass->equipmentToolList = $equipments;
                        $workItemClass->numOfManPower = count($manPowers);
                        $workItemClass->numOfEquipment = count($equipments);
                        $workItemClass->numOfMaterial = count($materials);

                        $processWorkItem->push($workItemClass);
                    }
                });

            return $processWorkItem;
        } catch (Exception $e) {
            return $e->getMessage();
        }
    }


    public function duplicateRelationWorkItem($newWorkItem, $parent){
        $manPowers = DB::table('man_powers_work_items')->where('work_item_id',$parent)->get();
        $toolsEquipments = DB::table('work_items_equipment_tools')->where('work_item_id', $parent)->get();
        $materials = DB::table('work_items_materials')->where('work_item_id', $parent)->get();

        $currentDate = now()->toDateTimeString();
        try {
            if ($manPowers->isNotEmpty()) {
                foreach ($manPowers as $manPower) {
                    DB::table('man_powers_work_items')->insert([
                        'work_item_id' => $newWorkItem->id,
                        'man_power_id' => $manPower->man_power_id,
                        'created_at' => $currentDate,
                        'updated_at' => $currentDate,
                        'labor_unit' => $manPower->labor_unit,
                        'labor_coefisient' => $manPower->labor_coefisient,
                        'amount' => $manPower->amount
                    ]);
                }
            }
            if ($toolsEquipments->isNotEmpty()) {
                foreach ($toolsEquipments as $toolsEquipment) {
                    DB::table('work_items_equipment_tools')->insert([
                        'work_item_id' => $newWorkItem->id,
                        'equipment_tools_id' => $toolsEquipment->equipment_tools_id,
                        'unit' => $toolsEquipment->unit,
                        'quantity' => $toolsEquipment->quantity,
                        'unit_price' => $toolsEquipment->unit_price,
                        'created_at' => $currentDate,
                        'updated_at' => $currentDate,
                        'amount' => $toolsEquipment->amount
                    ]);
                }

            }
            if ($materials->isNotEmpty()) {
                foreach ($materials as $material) {
                    DB::table('work_items_materials')->insert([
                        'work_item_id' => $newWorkItem->id,
                        'materials_id' => $material->materials_id,
                        'unit' => $material->unit,
                        'quantity' => $material->quantity,
                        'unit_price' => $material->unit_price,
                        'created_at' => $currentDate,
                        'updated_at' => $currentDate,
                        'amount' => $material->amount
                    ]);
                }

            }
        } catch (Exception $e){
            return $e->getMessage();
        }
    }

    public function isWorkItemCreateByUser($workItem){
       return auth()->user()->id == $workItem->created_by || auth()->user()->isWorkItemReviewer();
    }
}
