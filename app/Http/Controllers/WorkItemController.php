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
     * Set Work Item to Show iin List Estimate Discipline
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
                            "quantity" => number_format($equipment?->pivot?->quantity,2),
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
                            "quantity" => number_format($material?->pivot?->quantity,2),
                            "rate" => $this->toCurrency($material?->pivot?->unit_price),
                            "amount" => $this->toCurrency($material?->pivot?->amount),
                        );
                    }
                }

                $totalRateManPowers = $this->getTotalRateManPowers($manPowers);
                $totalRateEquipments = $this->getTotalRateEquipments($equipmentTools);
                $totalRateMaterials = $this->getTotalRateMaterials($materials);

                $children[] = array(
                    "id" => $subItems->id,
                    "text" => $subItems->description,
                    "vol" => $subItems->unit,
                    "manPowers" => $manPowersArr,
                    "manPowersTotalRate" => $totalRateManPowers,
                    "equipmentTools" => $equipmentToolsArr,
                    "equipmentToolsRate" => $totalRateEquipments,
                    "materials" => $materialsArr,
                    "materialsRate" => $totalRateMaterials,

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

    public function getTotalAmountToolsEquipment($equipment){
        $quantity = $equipment;
        $detailAmount = array();
        foreach($quantity as $item){
            $sum = $item->pivot->quantity * $item->local_rate;
            $detailAmount[] = $sum;
        }

        if(sizeof($detailAmount) < 1) return 0;
        return array_sum($detailAmount);
    }

    public function getTotalAmountMaterials($materials){
        $detailAmount = array();
        foreach ($materials as $material){
            $sum = $material->pivot->quantity * $material->rate;
            $detailAmount[] = $sum;
        }
        if(sizeof($detailAmount) < 1) return 0;
        return array_sum($detailAmount);
    }

    public function getTotalCost($costs,$type, $toCurrency){
        $cost = array();
        $costCategory = '';

        foreach ($costs as $item){
            switch ($type){
                case 'man_power':
                    $cost[] = $item->workItems?->manPowers()->sum('amount') * $item?->volume;
                break;
                case 'tool_equipments':
                    $cost[] = $this->getTotalAmountToolsEquipment($item?->workItems?->equipmentTools) * $item?->volume;
                    break;
                case 'materials':
                    $cost[] = $this->getTotalAmountMaterials($item?->workItems?->materials) * $item?->volume;
                    break;
                default:
                    $cost = null;
            }

        }

        if($toCurrency) return $this->toCurrency(array_sum($cost));
        else return array_sum($cost);
    }

    public function removeCurrencyFormat($value){
        if(!$value) return '';
        return number_format($value, 0);
    }

    public function getTotalRateManPowers($value){
        $data = $value->map(function($el){
            $rate = $el->overall_rate_hourly ?: 0;
            $coef = $el?->pivot?->labor_coefisient ?: 1;
            $tot = $rate * $this->toDecimalRound($coef);
            return $tot;
        })->all();

        return $this->toCurrency(array_sum($data));
    }

    public function getTotalRateEquipments($value){
        $data = $value->map(function($el){
            $rate = $el->local_rate;
            $qty = $el->pivot->quantity;
            $tot = $rate * (float) $qty;
            return $tot;
        })->all();

        return $this->toCurrency(array_sum($data));
    }

    public function getTotalRateMaterials($value){
        $data = $value->map(function($el){
            $rate = $el->rate;
            $qty = $el->pivot->quantity;
            $tot = $rate * (float) $qty;
            return $tot;
        })->all();

        return $this->toCurrency(array_sum($data));
    }
}
