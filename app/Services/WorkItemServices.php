<?php

namespace App\Services;

use App\Class\WorkItemClass;
use App\Class\WorkItemRelationItemListClass;
use App\Models\WorkItem;
use Exception;

class WorkItemServices
{
    public function getWorkItemList(){
        try{
            $workItemList = WorkItem::with(['manPowers','equipmentTools','materials','workItemTypes'])->get();
            $processWorkItem = $workItemList->map(function($workItem){

                $manPowers = [];
                $equipments = [];
                $materials = [];

                foreach($workItem->manPowers as $mp) {
                    $subItem = new WorkItemRelationItemListClass();
                    $subItem->id = $mp->id;
                    $subItem->description = $mp->title;
                    $subItem->unit = $mp->pivot->labor_unit;
                    $subItem->coef = $mp->pivot->labor_coefisient;
                    $subItem->amount = $mp->pivot->amount;
                    $subItem->unit_price = $mp->overall_rate_hourly;
                    array_push($manPowers,$subItem);
                }

                foreach($workItem->equipmentTools as $et){
                    $subItem = new WorkItemRelationItemListClass();
                    $subItem->id = $et->id;
                    $subItem->description = $et->description;
                    $subItem->unit_price = $et->pivot->unit_price;
                    $subItem->quantity = $et->pivot->quantity;
                    $subItem->amount = $et->pivot->amount;
                    array_push($equipments, $subItem);
                }

                $workItemClass = new WorkItemClass();
                $workItemClass->id = $workItem->id;
                $workItemClass->code = $workItem->code;
                $workItemClass->workItemDescription = $workItem->description;
                $workItemClass->volume = $workItem->volume;
                $workItemClass->workItemType = $workItem->workItemTypes->title;
                $workItemClass->workItemTypeId = $workItem->work_item_type_id;
                $workItemClass->reviewedBy = $workItem->reviewed_by;
                $workItemClass->manPowerList = $manPowers;
                $workItemClass->equipmentToolList = $equipments;
                $workItemClass->numOfManPower = count($manPowers);
                $workItemClass->numOfEquipment = count($equipments);

                return $workItemClass;
            });
            return $processWorkItem;

        } catch (Exception $e) {
            return $e->getMessage();
        }
    }
}
