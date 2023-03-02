<?php

namespace App\Http\Controllers;

use App\Models\ManPowersWorkItems;
use App\Models\WorkItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class WorkItemController extends Controller
{
    public function getWorkItems(Request $request){
//        $workItem = WorkItem::with(['workItemTypes','manPowers'])->
//            when(isset($request->q),function ($query) use ($request){
//               return $query->whereHas('workItemTypes',function ($q) use ($request) {
//                   return $q->where('title', 'like', '%' . $request->q . '%');
//               });
//            })->orWhere('description','like','%'.$request->q.'%');
////            dd($workItem->get()->groupBy('workItemTypes.id')[175]);
//        if($request->q) return $workItem->get()->groupBy('workItemTypes.id');
//
//        $item = DB::table('man_powers_work_items')
//        return $workItem->first();

//        select wi.code,wi.description,wi.unit,wi.unit,mp.title,mpwi.labor_unit,mpwi.labor_coefisient,mp.overall_rate_hourly,mpwi.amount,mpwi.id
//        from man_powers_work_items mpwi join man_powers mp on mpwi.labor_id = mp.id join work_items wi on mpwi.work_item_id = wi.id where wi.code = "2080.07";

        try {
            $item = WorkItem::with(['manPowers','workItemTypes','equipmentTools','materials'])
                ->whereHas('workItemTypes',function ($query) use ($request){
                    return $query->Where('title','like','%'.$request->q.'%');
                })->orWhere('description','like','%'.$request->q.'%')
                ->get()->groupBy('workItemTypes.id');
            return $item;
        } catch (\Exception $e){
            return $e->getMessage();
        }

//        $item = DB::table('man_powers_work_items as mpwi')
//            ->join('man_powers as mp','mpwi.labor_id','=','mp.id')
//            ->join('work_items as wi','mpwi.work_item_id','=','wi.id')
//            ->join('work_item_types as wit','wi.work_item_type_id','=','wit.id')
//            ->groupBy('wit.id')
//            ->get();
//        echo $item;
//        dd($item);

//        $items = DB::table('work_items as wi')
//            ->join('work_item_types as wit','wit.id','wi.work_item_type_id')
//            ->where('wit.title','like','%'.$request->q.'%')
//            ->orWhere('wi.description','like','%'.$request->q.'%')
//            ->get()->groupBy('wit.title');
//
//        dd($items);
    }
    /**
     * Get Work Element List Based on Project and Discipline
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function setWorkItems(Request $request){
        $workItem = $this->getWorkItems($request);
        foreach ($workItem as $k => $v){
            $children = array();
            foreach ($v as $subItems){
                $manPowersArr = array();
                $equipmentToolsArr = array();
                $materialsArr = array();
                $manPowers = $subItems?->manPowers;
                $equipmentTools = $subItems?->equipmentTools;
                $materials = $subItems?->materials;
                if($manPowers){
                    foreach ($manPowers as $manPower){
                        $manPowersArr[] = array(
                            "manPowerTitle" => $manPower->title,
                            "amountPivot" => $this->toCurrency($manPower->pivot->amount),
                            "coefisient" => $manPower?->pivot?->coefisient,
                            "laborCoefisient" =>$this->toDecimalRound($manPower?->pivot?->labor_coefisient),
                            "rateHourly" => $this->toCurrency($manPower?->overall_rate_hourly)
                        );
                    }
                }
                if($equipmentTools){
                    foreach ($equipmentTools as $equipment){
                        $equipmentToolsArr[] = array(
                            "description" => $equipment->description,
                            "quantity" => $equipment?->pivot?->quantity,
                            "unit" => $equipment?->pivot?->unit,
                            "unitPrice" => $this->toCurrency($equipment?->pivot?->unit_price),
                            "amount" => $this->toCurrency($equipment?->pivot?->amount),
                            "remark" => $equipment->remark
                        );
                    }
                }
                if($materials){
                    foreach ($materials as $material){
                        $materialsArr[] = array(
                            "description" => $material->tool_equipment_description,
                            "unit" => $material?->pivot?->unit,
                            "quantity" => $material?->pivot?->quantity,
                            "rate" => $this->toCurrency($material?->pivot?->unit_price),
                            "amount" => $this->toCurrency($material?->pivot?->amount),
                        );
                    }
                }
                $children[] = array(
                    "id" => $subItems->id,
                    "text" => $subItems->description,
                    "vol" => $subItems->unit,
                    "manPowers" => $manPowersArr,
                    "equipmentTools" => $equipmentToolsArr,
                    'materials' => $materialsArr,
                );
            }

            $response[] = array(
                "text" => $v[0]?->workItemTypes()?->get()[0]?->title,
                "children" => $children
            );
        }
        return response()->json($response);
    }

    public function getWorkItemRelated(Request $request){
        DB::table('view_work_item_list')->get();
    }

    public function toCurrency($val){
        if(!$val) return '';
        return number_format($val, 2);
    }

    public function toDecimalRound($val){
        if(!$val) return '';
        $val = str_replace(',','.',$val);
        return round($val, 2);
    }
}
