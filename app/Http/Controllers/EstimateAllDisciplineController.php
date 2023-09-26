<?php

namespace App\Http\Controllers;

use App\Class\EstimateDisciplineClass;
use App\Class\ProjectClass;
use App\Models\EstimateAllDiscipline;
use App\Models\Project;
use App\Models\WbsLevel3;
use App\Services\ProjectServices;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class EstimateAllDisciplineController extends Controller
{
    public function getWorkItems(Request $request, Project $project){
        $data = EstimateAllDiscipline::with(['workItems.manPowers','workItems.equipmentTools','workItems.materials'])->where('project_id', $project->id)
            ->where('work_scope',$request->discipline)->get();
        return $data;
    }

    public function getExistingWbsLevel3Id(Request $request){
        $wbsLevel3 = WbsLevel3::where('identifier',$request->level1)->where('discipline',$request->level2)
            ->where('work_element',$request->level3)->first('id');

        return $wbsLevel3->id;
    }

    public function create(Project $project, Request $request){
        $wbs = WbsLevel3::with(['workElements','estimateDisciplines.wbss.workElements','estimateDisciplines.workitems'])->where('project_id', $project->id)->get();
        $wbs = $wbs->mapToGroups(function ($loc){
            return [$loc->title => $loc];
        });

        $wbs = $wbs->map(function ($discipline){
            return $discipline->mapToGroups(function($disc){
               return [$disc->disciplines->title => $disc];
            });
        });

        $wbs = $wbs->map(function ($workElement) {
            return $workElement->map(function ($we) {
                return $we->flatMap(function ($e) {
                    $map = collect([]); // Create an empty collection
                    $projectServices = new ProjectServices();
                    foreach ($e->estimateDisciplines as $ed) {
                        $projectClass = new ProjectClass();
                        $projectClass->estimateVolume = $ed->volume;
                        $projectClass->disciplineTitle = $ed->disciplines?->title;
                        $projectClass->workItemIdentifier = $ed?->wbss?->identifier;
                        $projectClass->workElementTitle = $ed?->wbss?->workElements?->title;
                        $projectClass->workItemDescription = $ed?->workItems?->description;
                        $projectClass->workItemId = $ed?->workItems?->id;
                        $projectClass->workItemUnit = $ed?->workItems?->unit;
                        $projectClass->workItemUnitRateTotalLaborCost = $projectServices->getResultCount($ed?->labor_unit_rate, $ed?->labour_factorial);
                        $projectClass->workItemUnitRateLaborCost = (float) $ed?->labor_unit_rate;
                        $projectClass->workItemTotalLaborCost = (float) $ed?->labor_cost_total_rate;
                        $projectClass->workItemUnitRateTotalToolCost = $projectServices->getResultCount($ed?->tool_unit_rate, $ed?->equipment_factorial);
                        $projectClass->workItemUnitRateToolCost = (float) $ed?->tool_unit_rate;
                        $projectClass->workItemTotalToolCost = (float) $ed?->tool_unit_rate_total;
                        $projectClass->workItemUnitRateTotalMaterialCost = $projectServices->getResultCount($ed?->material_unit_rate, $ed?->material_factorial);
                        $projectClass->workItemUnitRateMaterialCost = (float) $ed?->material_unit_rate;
                        $projectClass->workItemTotalMaterialCost = (float) $ed?->material_unit_rate_total;
                        $projectClass->workItemLaborFactorial = $ed?->labour_factorial;
                        $projectClass->workItemEquipmentFactorial = $ed?->equipment_factorial;
                        $projectClass->workItemMaterialFactorial = $ed?->material_factorial;
                        $projectClass->workItemTotalCostStr = number_format($projectServices->getTotalCostWorkItem($ed), 2);
                        $projectClass->workItemTotalCost = $projectServices->getTotalCostWorkItem($ed);
                        $projectClass->wbs_level3_id = $ed->wbs_level3_id;
                        $projectClass->work_element_id = $ed->wbss?->work_element;
                        $projectClass->unique_identifier = $ed->unique_identifier;
                        $map->push($projectClass); // Push the $projectClass object directly
                    }

                    $returnData = $e;
                    if(sizeof($map) > 0) $returnData = $map;
                    return [$e->work_element => $returnData];
                });
            });
        });

        $version = EstimateAllDiscipline::where('project_id',$project->id)->first('version');


        return view('estimate_all_discipline.create',[
            'project' => $project,
            'estimateAllDiscipline' => $wbs,
            'version' => $version?->version ?? 0
        ]);
    }

    // save version of data
    // if version is not same than return error
    // merge the data of two user using button generate
    //

    public function update(Request $request){
        $workItemController = new WorkItemController();
        DB::beginTransaction();
        try {
            $newArrEstimateAllDiscipline = [];
            $record = EstimateAllDiscipline::where('project_id',$request->project_id)->first();
            if(sizeof($request->work_items) > 0){

                $existingEstimateDiscipline = $this->getExistingWorkItemByWbs($request);
                if($existingEstimateDiscipline){
                    if(!auth()->user()->canAny(['update','create'],EstimateAllDiscipline::class)){
                        return response()->json([
                            'status' => 403,
                            'message' => "You're not authorized"
                        ]);
                    }
                    foreach ($existingEstimateDiscipline as $item){
                        $item->delete();
                    }
                }
            }

            if ((isset($record->version)
                    && $request->version == $record?->version)
                || $request->version == 0 && !isset($record->version)
            ){
                $newVersion = $record?->version + 1;
                foreach ($request->work_items as $idx => $item){
                    $estimateAllDiscipline = new EstimateAllDiscipline();
                    $estimateAllDiscipline->title = '';
                    $estimateAllDiscipline->work_item_id = $item['workItem'];
                    $estimateAllDiscipline->volume = $item['vol'] > 0 ? $item['vol'] : 1;
                    $estimateAllDiscipline->project_id = $request->project_id;
                    $estimateAllDiscipline->labour_factorial = $item['labourFactorial'];
                    $estimateAllDiscipline->equipment_factorial = $item['equipmentFactorial'];
                    $estimateAllDiscipline->material_factorial = $item['materialFactorial'];
                    $estimateAllDiscipline->labor_unit_rate =  $workItemController->removeCommaCurrencyFormat($item['labourUnitRate']);
                    $estimateAllDiscipline->labor_cost_total_rate = $workItemController->removeCommaCurrencyFormat($item['totalRateManPowers']) * $item['vol'];
                    $estimateAllDiscipline->tool_unit_rate =  $workItemController->removeCommaCurrencyFormat($item['equipmentUnitRate']);
                    $estimateAllDiscipline->tool_unit_rate_total =  $workItemController->removeCommaCurrencyFormat($item['totalRateEquipments']) * $item['vol'];
                    $estimateAllDiscipline->material_unit_rate =  $workItemController->removeCommaCurrencyFormat($item['materialUnitRate']);
                    $estimateAllDiscipline->material_unit_rate_total =  $workItemController->removeCommaCurrencyFormat($item['totalRateMaterials']) * $item['vol'];
                    $estimateAllDiscipline->wbs_level3_id = $item['wbs_level3'];
                    $estimateAllDiscipline->equipment_location_id = $item['work_element'];
                    $estimateAllDiscipline->unique_identifier = $item['idx'];
                    $estimateAllDiscipline->version = $newVersion;
                    $estimateAllDiscipline->save();
                }
                $response = [
                    'status' => 200,
                    'message' => 'Success, Your data was saved successfully',
                    'version' => $newVersion
                ];

                DB::commit();
                return response()->json($response);
            } else {
                $response = [
                    'status' => 500,
                    'message' => 'Conflict: Someone else has updated this record.',
                    'sync' => true
                ];

                DB::rollback();
                return response()->json($response);
            }


        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 500,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function getItemAdditional(Request $request){
        $arrayResult = array();
        $manPowerController = new ManPowerController();
        $materialController = new MaterialController();

        switch ($request->type) {
            case('manPower') :
                $data = $manPowerController->getAllManPower($request);
                foreach ($data as $item){
                    $arrayResult[] = array(
                        'text' => $item?->title,
                        'id' => $item?->id,
                        'rate' => $item?->overall_rate_hourly,
                    );
                }
                break;
            case('material') :
                $data = $materialController->getAllMaterial($request);
                break;
        }

        return $arrayResult;
    }

    public function getExistingWorkItemByWbs($request){
        $data = EstimateAllDiscipline::with(['wbss.workElements.wbsDiscipline','workItems.materials',
            'workItems.manPowers','workItems.equipmentTools'])
            ->where('project_id',$request->project_id)->get();

        return $data;
    }

    public function getEstimateToSync(Request $request){
        try{
            $data = EstimateAllDiscipline::with(['workItems'])->where('project_id', $request->project_id)
                ->orderBy('id','DESC')
                ->get();

            $estimateConflict = [];

            foreach($request->estimate_sync as $idx => $cv){
                $estimateToSync = new EstimateDisciplineClass();
                $estimateToSync->workItemId = $cv['workItem'];
                $estimateToSync->workItemDescription = $cv['workItemText'];
                $estimateToSync->workItemVolume = $cv['vol'] > 0 ? $cv['vol'] : 1;;
                $estimateToSync->workItemManPowerCost = $cv['totalRateManPowers'];
                $estimateToSync->workItemEquipmentCost = $cv['totalRateEquipments'];
                $estimateToSync->workItemMaterialCost = $cv['totalRateMaterials'];
                $estimateToSync->laborFactorial = $cv['labourFactorial'];
                $estimateToSync->equipmentFactorial = $cv['equipmentFactorial'];
                $estimateToSync->materialFactorial = $cv['materialFactorial'];
                $estimateToSync->wbsLevel3Id = $cv['wbs_level3'];
                $estimateToSync->uniqueIdentifier = $cv['idx'];
                $estimateToSync->version = $request->current_version;

                array_push($estimateConflict, $estimateToSync);
            }
//
            $estimateAlreadySave = $data->map(function($item) use ($estimateConflict){
                $estimateToSync = new EstimateDisciplineClass();
                $estimateToSync->workItemId = $item->work_item_id;
                $estimateToSync->workItemDescription = $item->workItems?->description;
                $estimateToSync->workItemVolume = $item->volume;
                $estimateToSync->workItemManPowerCost = $item->labor_cost_total_rate;
                $estimateToSync->workItemEquipmentCost = $item->tool_unit_rate_total;
                $estimateToSync->workItemMaterialCost = $item->material_unit_rate_total;
                $estimateToSync->laborFactorial = $item->labour_factorial;
                $estimateToSync->equipmentFactorial = $item->equipment_factorial;
                $estimateToSync->materialFactorial = $item->material_factorial;
                $estimateToSync->wbsLevel3Id = $item->wbs_level3_id;
                $estimateToSync->version = $item->version;
                $estimateToSync->uniqueIdentifier = $item->unique_identifier;
                return $estimateToSync;
            });

            //            $inEstimateConflict = $estimateConflictCollection->pluck('uniqueIdentifier')->toArray();
//            $itemToAdd = $estimateAlreadySave->filter(function ($item) use ($inEstimateConflict){
//                if(!in_array($item->uniqueIdentifier, $inEstimateConflict)){
//                    return true;
//                }
//                return false;
//            });
            $newWorkItemToAdd = [];
            $itemToDelete = [];
            $itemToMerge = [];
            $version = $estimateAlreadySave[0]->version;

            $estimateConflictCollection = new Collection($estimateConflict);

             $estimateAlreadySave->each(function ($item) use (&$newWorkItemToAdd, $estimateConflictCollection) {
                // Use the push method to add items to the collection
                $matchingItem = $estimateConflictCollection->firstWhere('uniqueIdentifier', $item->uniqueIdentifier);
                if(!$matchingItem){
                    $newWorkItemToAdd[] = $item;
                }
             });

            $cek = [];
            $estimateConflictCollection->each(function ($item) use (&$itemToDelete, $estimateAlreadySave, &$newWorkItemToAdd) {
                // Use the push method to add items to the collection
                $matchingItem = $estimateAlreadySave->firstWhere('uniqueIdentifier', $item->uniqueIdentifier);
                if (!$matchingItem && !in_array($item, $newWorkItemToAdd)) {
                    $itemToMerge[] = $item;
                }
            });

             $data = [
                 'existingEstimate' => $estimateAlreadySave,
                 'itemToMerge' => $newWorkItemToAdd,
                 'version' => $version,
             ];

            return response()->json([
                'status' => 200,
                'data' => $data
            ]);



            // cari dalam estimateAlreadySave

            // draft simpan di variable temp terus load data yang sudah di save
            // setelah save terload masukkan dan gabungkan dari temp yang di load dari save
                //kalau identifier match dengan array dari data save sama maka skip untuk append
                // kalau identifier tidak match dari array maka append all data based on wbs_level3_id


            // intinya semua save work item masukkan ke draft work item
                //kalau identifiernya sama draft dan save maka ambil save
                //kalau ada identifier dari save nda terdeteksi di identifier draft maka tambahkan item save ke item draft
                //kalau ada
//
//            $estimateConflictCollection = new Collection($estimateConflict);
//            $inEstimateConflict = $estimateConflictCollection->pluck('uniqueIdentifier')->toArray();
//            $inEstimateConflictVersion = $estimateConflictCollection->pluck('version')->toArray();
//
//// Filter $estimateAlreadySave based on workItemIds that are not in $inEstimateConflict
//            $itemsToRemove = $estimateAlreadySave->filter(function ($item) use ($inEstimateConflict) {
//                if(!in_array($item->uniqueIdentifier, $inEstimateConflict)){
//                    return true;
//                }
//                return false;
////                return !in_array($item->workItemId, $inEstimateConflict) && !in_array($item->wbsLevel3Id, $inEstimateConflictWbs);
//            })->all();


            return response()->json([
                'status' => 200,
                'data' => $itemToAdd,
                'message' => 'Success Synchronize Data'
            ]);

        } catch (Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => $e->getMessage()
            ]);
        }
    }
}
