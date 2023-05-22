<?php

namespace App\Http\Controllers;

use App\Models\ManPowersWorkItems;
use App\Models\WorkItem;
use App\Models\WorkItemType;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class WorkItemController extends Controller
{
    public function index(){
        $workItem = WorkItem::with('workItemTypes')->filter(request(['q','category']))->orderBy('code','ASC')->paginate(20)->withQueryString();
        $workItemCategory = WorkItemType::select('id','title')->get();

        return view('work_item.index',[
            'work_item' => $workItem,
            'work_item_category' => $workItemCategory
        ]);
    }

    public function show(WorkItem $workItem){
        return view('work_item.show',[
            'work_item' => $workItem
        ]);
    }

    public function create(){
        $workItemCategory = WorkItemType::select('id','title')->get();
        return view('work_item.create', [
            'work_item_type' => $workItemCategory
        ]);
    }

    public function edit(WorkItem $workItem){
        $workItemCategory = WorkItemType::select('id','title')->get();
        return view('work_item.edit', [
            'work_item' => $workItem,
            'work_item_type' => $workItemCategory
        ]);
    }

    public function store(Request $request){
        $this->validate($request,[
            'code' => 'required|unique:work_items',
            'work_item_type_id' => 'required',
            'description' => 'required',
            'volume' => 'required',
            'unit' => 'required'
        ]);

        try{
            DB::beginTransaction();
            $workItem = new WorkItem([
                'code' => $request->code,
                'work_item_type_id' => $request->work_item_type_id,
                'description' => $request->description,
                'volume' => $request->volume,
                'unit' => $request->unit,
            ]);
            $workItem->save();
            DB::commit();
            return redirect('work-item/'.$workItem->id);
        } catch(\Exception $e){
            DB::rollBack();
            return redirect('work-item/create')->withErrors($e->getMessage());
        }
    }

    public function update(WorkItem $workItem, Request $request){
        $this->validate($request,[
            Rule::unique('man_powers')->ignore($workItem->id),
            'work_item_type_id' => 'required',
            'description' => 'required',
            'volume' => 'required',
            'unit' => 'required'
        ]);

        try{
            DB::beginTransaction();
            $workItem->code = $request->code;
            $workItem->work_item_type_id = $request->work_item_type_id;
            $workItem->description = $request->description;
            $workItem->volume = $request->volume;
            $workItem->unit = $request->unit;

            $workItem->save();
            DB::commit();
            return redirect('work-item/'.$workItem->id);
        } catch(\Exception $e){
            DB::rollBack();
            return redirect('work-item/edit')->withErrors($e->getMessage());
        }
    }

    public function createManPower(WorkItem $workItem){
        return view('work_item.work_item_man_power.create',[
            'workItem' => $workItem
        ]);
    }

    public function editManPower(WorkItem $workItem, Request $request){
        return view('work_item.work_item_man_power.edit',[
            'workItem' => $workItem
        ]);
    }

    public function storeManPower(WorkItem $workItem,Request $request){
        try{
            $this->processStoreManPower($workItem,$request);
            return response()->json([
                'status' => 200,
                'message' => 'Data Saved Successfully'
            ]);
        } catch (Exception $e){
            return response()->json([
                'status' => 500,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function updateManPower(WorkItem $workItem, Request $request){
        try{
            $this->deleteExistingManPower($workItem,$request);
            $this->processStoreManPower($workItem,$request);
            return response()->json([
                'status' => 200,
                'message' => 'Data Saved Successfully'
            ]);
        } catch (Exception $e){
            return response()->json([
                'status' => 500,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function processStoreManPower(WorkItem $workItem,Request $request){
        if(sizeof($request->data) > 0){
            foreach($request->data as $item){
                $additionalData = [
                    'labor_unit' => $item['unit'],
                    'labor_coefisient' => $item['coef'],
                    'amount' => $this->removeCommaCurrencyFormat($item['amount']),
                    'created_at' => now(),
                    'updated_at' => now()
                ];

                $workItem->manPowers()->attach($item['man_power'], $additionalData);
                $workItem->save();
            }
        }
    }

    public function deleteExistingManPower(WorkItem $workItem, Request $request){
        $workItem->manPowers()->detach();
    }

    public function getWorkItems(Request $request){
        try {

            $item = WorkItem::with(['manPowers','workItemTypes','equipmentTools','materials'])
                ->whereHas('workItemTypes',function ($query) use ($request){
                    return $query->Where('title','like','%'.$request->q.'%');
                })->orWhere('description','like','%'."$request->q".'%')
                ->get()->groupBy('workItemTypes.id');
            return $item;
        } catch (\Exception $e){
            return $e->getMessage();
        }
    }
    /**
     * Set Work Item to Show in List Estimate Discipline Select2
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
                            "title" => $manPower->title,
                            "pivot" => $manPower?->pivot,
                            "overall_rate_hourly" => $manPower?->overall_rate_hourly
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
                            "tool_equipment_description" => $material->tool_equipment_description,
                            "unit" => $material?->pivot?->unit,
                            "pivot" => $material?->pivot,
                            "rate" => $material?->pivot?->rate,
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
        if(!$quantity) return null;
        $detailAmount = array();
        foreach($quantity as $item){
            $sum = $item->pivot->quantity * $item->local_rate;
            $detailAmount[] = $sum;
        }

        if(sizeof($detailAmount) < 1) return 0;
        return array_sum($detailAmount);
    }

    public function getTotalAmountMaterials($materials){
        if(!$materials) return null;
        $detailAmount = array();
        foreach ($materials as $material){
            $sum = $material->pivot->quantity * $material->rate;
            $detailAmount[] = $sum;
        }
        if(sizeof($detailAmount) < 1) return 0;
        return array_sum($detailAmount);
    }

    public function getTotalCost($costs,$type,$toCurrency){
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

    public function getTotalCostFromEstimateDiscipline(){

    }

    public function getResultCount($value,$factorial){
        if(!$value) return '';
        if(!$factorial) $factorial = 1;
        $newValue = $value * $factorial;
        return $this->toCurrency($newValue);
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

    public function removeCommaCurrencyFormat($val){
        if(!$val) return "";
        return str_replace(',','',$val);
    }

    /**
     * Sum total price category by work element in project detail page estimate discipline
     * @return array
     */
    public function sumTotalByWorkElement($estimateDiscipline){
        $totalPriceLabor = 0;
        $totalPriceEquipment = 0;
        $totalPriceMaterial = 0;

        if($estimateDiscipline){
            foreach($estimateDiscipline as $v){
                $totalPriceLabor += $v->labor_cost_total_rate * $v->volume;
                $totalPriceEquipment += $v->tool_unit_rate_total * $v->volume;
                $totalPriceMaterial += $v->material_unit_rate_total * $v->volume;
            }
        }

        $totalWorkCostByElement = $totalPriceLabor + $totalPriceEquipment + $totalPriceMaterial;

        $data = [
            'totalLaborByWorkElement' => $this->toCurrency($totalPriceLabor),
            'totalEquipmentByWorkElement' => $this->toCurrency($totalPriceEquipment),
            'totalMaterialByWorkElement' => $this->toCurrency($totalPriceMaterial),
            'totalWorkCostByElement' => $totalWorkCostByElement
        ];

        return $data;
    }

    public function sumTotalEstimateDiscipline($value){

    }
}
