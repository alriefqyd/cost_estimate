<?php

namespace App\Http\Controllers;

use App\Exports\WorkItemExport;
use App\Models\Setting;
use App\Models\WorkItem;
use App\Models\WorkItemType;
use App\Services\WorkItemServices;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;

class WorkItemController extends Controller
{
    public function index(Request $request){
        if(!auth()->user()->can('viewAny',WorkItem::class)){
            return view('not_authorized');
        }
        $order = $request->order;
        $sort =  $request->sort;

        $workItem = WorkItem::leftJoin('work_item_types','work_items.work_item_type_id','work_item_types.id')->filter(request(['q','category','status']))
            ->leftJoin('users','work_items.created_by','users.id')
            ->leftJoin('profiles','users.id','profiles.user_id')
            ->when(isset($request->sort), function($query) use ($request,$order,$sort) {
                return $query->when($request->order == 'work_items.volume', function ($q) use ($request, $order, $sort) {
                    return $q->orderByRaw('CONVERT(work_items.volume, SIGNED)' . $sort);
                })->when($request->sort != 'work_items.volume', function ($q) use ($request, $order, $sort) {
                    return $q->orderBy($order, $sort);
                });
            })->when(!auth()->user()->isWorkItemReviewer(), function($query) {
                return $query->where(function($q){
                    return $q->where('status',WorkItem::REVIEWED)->orwhere('created_by',auth()->user()->id);
                });
            })->when(!isset($request->sort), function($query) use ($request,$order){
                return $query->orderBy('work_items.code','ASC');
            })->select('work_items.code','work_items.description','work_items.id','work_item_types.title as category','work_items.volume','work_items.unit','work_items.status','profiles.full_name')->paginate(20)->withQueryString();
        $workItemCategory = WorkItemType::select('id','title')->get();

        return view('work_item.index',[
            'work_item' => $workItem,
            'work_item_category' => $workItemCategory
        ]);
    }

    public function show(WorkItem $workItem){
        if(!auth()->user()->can('viewAny',WorkItem::class)
            || !$workItem->isAuthorized()){
            return view('not_authorized');
        }

        return view('work_item.show',[
            'work_item' => $workItem
        ]);
    }

    public function create(){
        if(!auth()->user()->can('create',WorkItem::class)){
            return view('not_authorized');
        }
        $workItemCategory = WorkItemType::select('id','title','code')->get();
        return view('work_item.create', [
            'work_item_type' => $workItemCategory
        ]);
    }

    public function edit(WorkItem $workItem){
        if(!auth()->user()->can('update',WorkItem::class)){
            return view('not_authorized');
        }
        $workItemCategory = WorkItemType::select('id','title','code')->get();
        return view('work_item.edit', [
            'work_item' => $workItem,
            'work_item_type' => $workItemCategory
        ]);
    }

    public function store(Request $request){
        $workItemService = new WorkItemServices();
        if(!auth()->user()->can('create',WorkItem::class)){
            abort(403);
        }

        $code = $request->code;
        $this->validate($request,[
            Rule::unique('work_items')->where(function ($query) use ($request, $code) {
                return $query->where('code', $code);
            }),
            'work_item_type_id' => 'required',
            'description' => 'required',
            'volume' => 'required',
            'unit' => 'required'
        ]);

        try{
            DB::beginTransaction();
            $workItem = new WorkItem([
                'code' => $code,
                'parent_id' => $request->parent_id,
                'work_item_type_id' => $request->work_item_type_id,
                'description' => $request->description,
                'volume' => $request->volume,
                'unit' => $request->unit,
                'status' => WorkItem::DRAFT,
                'created_by' => auth()->user()->id
            ]);
            $workItem->save();
            $workItemService->duplicateRelationWorkItem($workItem,$request->parent_id);
            DB::commit();
            return redirect('work-item/'.$workItem->id);
        } catch(\Exception $e){
            DB::rollBack();
            return redirect('work-item/create')->withErrors($e->getMessage());
        }
    }

    public function update(WorkItem $workItem, Request $request){
        if(!auth()->user()->can('update',WorkItem::class)){
            abort(403);
        }
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
            return redirect('work-item/edit/'.$workItem->id)->withErrors($e->getMessage());
        }
    }

    public function createManPower(WorkItem $workItem){
        if(!auth()->user()->canAny(['create','update'],WorkItem::class)){
            return (view('not_authorized'));
        }
        return view('work_item.work_item_man_power.create',[
            'workItem' => $workItem
        ]);
    }

    public function editManPower(WorkItem $workItem, Request $request){
        if(!auth()->user()->canAny(['create','update'],WorkItem::class)){
            return (view('not_authorized'));
        }
        return view('work_item.work_item_man_power.edit',[
            'workItem' => $workItem
        ]);
    }

    public function storeManPower(WorkItem $workItem,Request $request){
        if(!auth()->user()->canAny(['create','update'],WorkItem::class)){
            return (view('not_authorized'));
        }
        DB::beginTransaction();
        try{
            $pivotData = $this->processStoreManPower($workItem,$request);
            $workItem->manPowers()->attach($pivotData);
            DB::commit();
            return response()->json([
                'status' => 200,
                'message' => 'Data Saved Successfully'
            ]);
        } catch (Exception $e){
            DB::rollback();
            return response()->json([
                'status' => 500,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function updateManPower(WorkItem $workItem, Request $request){
        if(!auth()->user()->canAny(['create','update'],WorkItem::class)){
            abort(403);
        }
        DB::beginTransaction();
        try{
            $pivotData = $this->processStoreManPower($workItem, $request);
            $uniquePivotData = array_map("unserialize", array_unique(array_map("serialize", $pivotData)));
            $workItem->manPowers()->sync($uniquePivotData);
            DB::commit();
            return response()->json([
                'status' => 200,
                'message' => $pivotData
            ]);
        } catch (Exception $e){
            DB::rollback();
            return response()->json([
                'status' => 500,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function processStoreManPower(WorkItem $workItem,Request $request){
        $pivotData = [];

        if (isset($request->data) && is_array($request->data)) {
            foreach ($request->data as $item) {
                $pivotData[$item['item']] = [
                    'labor_unit'      => $item['unit'],
                    'labor_coefisient'=> $item['coef'],
                    'amount'          => $this->removeCommaCurrencyFormat($item['amount']),
                    'created_at'      => now(),
                    'updated_at'      => now(),
                ];
            }
        }

        return $pivotData;
    }

    public function deleteExistingManPower(WorkItem $workItem, Request $request){
        $workItem->manPowers()->detach();
    }

    public function createToolsEquipment(WorkItem $workItem){
        if(!auth()->user()->canAny(['create','update'],WorkItem::class)){
            return (view('not_authorized'));
        }
        return view('work_item.work_item_tools_equipment.create',[
            'workItem' => $workItem
        ]);
    }

    public function editToolsEquipment(WorkItem $workItem, Request $request){
        if(!auth()->user()->canAny(['create','update'],WorkItem::class)){
            return (view('not_authorized'));
        }
        return view('work_item.work_item_tools_equipment.edit',[
            'workItem' => $workItem
        ]);
    }

    public function storeToolsEquipment(WorkItem $workItem,Request $request){
        if(!auth()->user()->canAny(['create','update'],WorkItem::class)){
            return (view('not_authorized'));
        }
        try{
            DB::beginTransaction();
            $pivotData = $this->processStoreToolEquipment($workItem,$request);
            $workItem->equipmentTools()->attach($pivotData);
            DB::commit();
            return response()->json([
                'status' => 200,
                'message' => 'Data Saved Successfully'
            ]);
        } catch (Exception $e){
            DB::rollback();
            return response()->json([
                'status' => 500,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function updateToolsEquipment(WorkItem $workItem, Request $request){
        if(!auth()->user()->canAny(['create','update'],WorkItem::class)){
            return (view('not_authorized'));
        }
        try{
            $pivotData = $this->processStoreToolEquipment($workItem,$request);
            $uniquePivotData = array_map("unserialize", array_unique(array_map("serialize", $pivotData)));
            $workItem->equipmentTools()->sync($uniquePivotData);
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

    public function processStoreToolEquipment(WorkItem $workItem,Request $request){
        $pivotData = [];
        if(sizeof($request->data) > 0){
            foreach($request->data as $item){
                $pivotData[$item['item']] = [
                    'unit' => $item['unit'],
                    'quantity' => $item['coef'],
                    'amount' => $this->convertToCurrencyDBFormat($item['amount']),
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }
        }
        return $pivotData;
    }

    public function createMaterial(WorkItem $workItem){
        if(!auth()->user()->canAny(['create','update'],WorkItem::class)){
            return (view('not_authorized'));
        }
        return view('work_item.work_item_material.create',[
            'workItem' => $workItem
        ]);
    }

    public function editMaterial(WorkItem $workItem, Request $request){
        if(!auth()->user()->canAny(['create','update'],WorkItem::class)){
            return (view('not_authorized'));
        }
        return view('work_item.work_item_material.edit',[
            'workItem' => $workItem
        ]);
    }

    public function storeMaterial(WorkItem $workItem,Request $request){
        if(!auth()->user()->canAny(['create','update'],WorkItem::class)){
            return (view('not_authorized'));
        }
        try{
            $pivotData = $this->processStoreMaterial($workItem,$request);
            $workItem->materials()->attach($pivotData);
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

    public function updateMaterial(WorkItem $workItem, Request $request){
        if(!auth()->user()->canAny(['create','update'],WorkItem::class)){
            return (view('not_authorized'));
        }
        try{
            $pivotData = $this->processStoreMaterial($workItem,$request);
            $workItem->materials()->sync($pivotData);
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

    public function processStoreMaterial(WorkItem $workItem,Request $request){
        $pivotData = [];
        if(sizeof($request->data) > 0){
            foreach($request->data as $item){
                $pivotData[$item['item']] = [
                    'unit' => $item['unit'],
                    'quantity' => $item['coef'],
                    'amount' => $this->convertToCurrencyDBFormat($item['amount']),
                    'created_at' => now(),
                    'updated_at' => now()
                ];
            }
        }

        return $pivotData;
    }

    public function getWorkItems(Request $request){
        try {
            $item = WorkItem::with(['manPowers','workItemTypes','equipmentTools','materials'])
                ->when(isset($request->q), function($query) use ($request){
                    $query->whereHas('workItemTypes',function ($query) use ($request){
                        return $query->where(function ($q) use ($request) {
                            return $q->Where('title','like','%'.$request->q.'%');
                        })->orWhere('description','like','%'."$request->q".'%');
                    });
                })->when(!auth()->user()->isWorkItemReviewer(), function($query) {
                    return $query->where(function($q){
                        return $q->where('status', WorkItem::REVIEWED)
                            ->orWhere('created_by', auth()->user()->id);
                    });
                })->when(isset($request->category) && $request->isCustom == "false", function($query) use ($request){
                    return $query->where('work_item_type_id', $request->category);
                })->when(isset($request->term), function($query) use ($request){
                    return $query->where('description','like','%'.$request->term.'%');
                })->get()->groupBy('workItemTypes.id');
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
        try{
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
                    $totalRateWorkItem = (float) $totalRateManPowers + (float) $totalRateEquipments + (float) $totalRateMaterials;

                    $children[] = array(
                        "id" => $subItems->id,
                        "code" => $subItems->code,
                        "totalChild" => $subItems->countChildren(),
                        "text" => $subItems->description,
                        "unit" => $subItems->unit,
                        "vol" => $subItems->volume,
                        "manPowers" => $manPowersArr,
                        "manPowersTotalRate" => $this->toCurrency($totalRateManPowers),
                        "manPowersTotalRateInt" => $totalRateManPowers,
                        "equipmentTools" => $equipmentToolsArr,
                        "equipmentToolsRate" => $this->toCurrency($totalRateEquipments),
                        "equipmentToolsRateInt" => $totalRateEquipments,
                        "materials" => $materialsArr,
                        "materialsRate" => $this->toCurrency($totalRateMaterials),
                        "materialsRateInt" => $totalRateMaterials,
                        "totalWorkItemRate" => $totalRateWorkItem,
                        "totalWorkItemRateStr" => number_format($totalRateWorkItem,2,',','.')
                    );
                }

                $response[] = array(
                    "text" => $v[0]?->workItemTypes()?->get()[0]?->title,
                    "children" => $children
                );
            }
            return response()->json($response);
        } catch (Exception $e){
            return response()->json([]);
        }

    }

    public function getWorkItemRelated(Request $request){
        DB::table('view_work_item_list')->get();
    }

    public function toCurrency($val){
        if(!$val) return '';
        return number_format($val, 2,',','.');
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


    public function removeCurrencyFormat($value){
        if(!$value) return '';
        return number_format($value, 0);
    }

    public function getTotalRateManPowers($value){
        $sum = $value->map(function ($mp) {
            $coef = str_replace(',', '.', $mp->pivot->labor_coefisient);
            $rate = $mp->overall_rate_hourly ?: 0; // Set default rate if null
            return $rate * (float) $coef;
        })->sum();

        return $sum;
    }

    public function getTotalRateEquipments($value){
        $sum = $value->map(function ($mp) {
            $coef = str_replace(',', '.', $mp->pivot->quantity);
            $rate = $mp->local_rate ?: 0; // Set default rate if null
            return $rate * (float) $coef;
        })->sum();

        return $sum;
    }

    public function getTotalRateMaterials($value){
        $sum = 0;
        foreach($value as $mp){
            $coef = str_replace(',','.',$mp?->pivot->quantity);
            $tot = $mp?->rate * (float) $coef;
            $sum += $tot;
        }

        return $sum;
    }

    public function removeCommaCurrencyFormat($val){
        if(!$val) return 0;
        return str_replace(',','',$val);
    }

    public function strToFloat($val){
        if(!$val) return 0;
        return (float) $val;
    }

    public function convertToCurrencyDBFormat($val){
        if(!$val) return 0;
        $str =  str_replace('.','', $val);
        $str = str_replace(',','.', $str);
        return $str;
    }

    public function getNumChild(WorkItem $workItem, Request $request){
        $parent = $workItem?->parent;
        $children = $workItem?->children;

        if(!$parent) {// Data Ori
            $codeNew = $workItem->code . Setting::CODE_NEW_CHILD_WORK_ITEM;
            if($children){
                $codeNew = $codeNew . $children->count() + 1;
            }
            $code = $codeNew;

        } else { // Data Duplicate
            $codeNew = $workItem->parent?->code . Setting::CODE_NEW_CHILD_WORK_ITEM . $workItem->parent?->children?->count() + 1;
            $code = $codeNew;
        }

        return response()->json([
            'status' => 200,
            'data' => $code,
        ]);
    }

    public function getNumChildType(WorkItemType $workItemtype, Request $request){
        $workItem = WorkItem::where('type', $workItemtype->id)->count();

        return response()->json([
            'status' => 200,
            'data' => $workItemtype->code . $workItem
        ]);
    }

    public function getDetail(Request $request){
        $data = WorkItem::with(['equipmentTools:id,description,unit,quantity,local_rate',
            'materials:id,tool_equipment_description,unit,quantity,rate',
            'manPowers:id,title,basic_rate_month,overall_rate_hourly'])
            ->select('work_items.id')
            ->where('id', $request->id)
            ->first();


        if(isset($data)){
            if($request->type == 'man_power'){
                $manPower = $data->manPowers?->map(function($mp){
                    return [
                        'id' => $mp->id,
                        'title' => $mp->title,
                        'basic_rate_month' => $mp->basic_rate_month,
                        'overall_rate_hourly' => number_format($mp->overall_rate_hourly,2,',','.'),
                        'labor_unit' => $mp->pivot->labor_unit,
                        'labor_coefisient' => number_format((float) $mp->pivot->labor_coefisient,2),
                        'amount' => number_format($mp->getAmount(),2,',','.')
                    ];
                });

                return response()->json([
                    'data' => [
                        'isManPower' => true,
                        'manPower' => $manPower,
                    ],
                    'status' => 200
                ]);
            }


            if($request->type == 'equipment') {
                $equipment = $data->equipmentTools?->map(function ($mp) {
                    return [
                        'id' => $mp->id,
                        'description' => $mp->description,
                        'unit' => $mp->unit,
                        'quantity' => number_format($mp->pivot->quantity, 2),
                        'local_rate' => number_format($mp->local_rate, 2,',','.'),
                        'amount' => number_format($mp->getAmount(), 2, ',', '.')
                    ];
                });

                return response()->json([
                    'data' => [
                        'isEquipment' => true,
                        'equipment' => $equipment,
                    ],
                    'status' => 200
                ]);
            }

            if($request->type == 'material') {
                $material = $data->materials?->map(function ($mp) {
                    return [
                        'id' => $mp->id,
                        'description' => $mp->tool_equipment_description,
                        'unit' => $mp->unit,
                        'quantity' => number_format($mp->pivot->quantity, 2),
                        'rate' => number_format($mp->rate, 2,',','.'),
                        'amount' => number_format($mp->getAmount(), 2, ',', '.')
                    ];
                });

                return response()->json([
                    'data' => [
                        'isMaterial' => true,
                        'material' => $material
                    ],

                    'status' => 200
                ]);
            }

        } else {
            return response()->json([
                'status' => 500
            ]);
        }
    }

    public function updateStatusWorkItem(Request $request){
        DB::beginTransaction();
        try{
            $data = WorkItem::where('id',$request->id)->first();
            $data->status = $request->status;
            $data->save();
            DB::commit();
            return response()->json([
                'status' => 200,
                'message' => 'Data successfully update'
            ]);
        } catch (Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 500,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function updateList(Request $request){
        $ids = (string) $request->ids;
        DB::beginTransaction();
        $ids = explode(',',$ids);
        try {
            $items = WorkItem::whereIn('id',$ids)->get();

            $items->each(function ($item){
                $item->update(['status' => WorkItem::REVIEWED]);
            });
            DB::commit();
            return response()->json([
                'message' => 'Data successfully update',
                'status' => 200
            ]);
        } catch (Exception $e){
            DB::rollback();
            return response()->json([
                'message' => $e->getMessage(),
                'status' => 500
            ]);
        }
    }

    public function destroy(WorkItem $workItem){
        if(auth()->user()->cannot('delete',WorkItem::class)){
            return response()->json([
                'status' => 403,
                'message' => "You're not authorized"
            ]);
        }

        DB::beginTransaction();
        try {
            $workItem->manPowers()->detach();
            $workItem->equipmentTools()->detach();
            $workItem->materials()->detach();
            $workItem->delete();
            DB::commit();
            return response()->json([
                'status' => 200,
                'message' => 'Work Item Successfully deleted'
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 500,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function export(){
        try {
            Log::info('Starting Export Work Items');
            return Excel::download(new WorkItemExport(), 'Std_Work_Item.xlsx');
        } catch (Exception $e) {
            Log::info($e->getMessage());
            return response()->json('Import Failed : ' . $e->getMessage());
        }
    }
}
