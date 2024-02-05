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
                        $projectClass->workItemTotalCostStr = number_format($projectServices->getTotalCostWorkItem($ed), 2,',','.');
                        $projectClass->workItemTotalCost = $projectServices->getTotalCostWorkItem($ed);
                        $projectClass->wbs_level3_id = $ed->wbs_level3_id;
                        $projectClass->work_element_id = $ed->wbss?->work_element;
                        $projectClass->unique_identifier = $ed->unique_identifier;
                        $projectClass->version = $ed->version;
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
                    $estimateAllDiscipline->labor_unit_rate =  $workItemController->strToFloat($item['labourUnitRate']);
                    $estimateAllDiscipline->labor_cost_total_rate = $workItemController->strToFloat($item['totalRateManPowers']) * $item['vol'];
                    $estimateAllDiscipline->tool_unit_rate =  $workItemController->strToFloat($item['equipmentUnitRate']);
                    $estimateAllDiscipline->tool_unit_rate_total =  $workItemController->strToFloat($item['totalRateEquipments'])* $item['vol'];
                    $estimateAllDiscipline->material_unit_rate =  $workItemController->strToFloat($item['materialUnitRate']);
                    $estimateAllDiscipline->material_unit_rate_total =  $workItemController->strToFloat($item['totalRateMaterials']) * $item['vol'];
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
                    'message' => 'Conflict: This record has been modified by another user.
                                  Please synchronize with the latest changes before saving your data',
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

    public function getExistingWorkItemByWbs(Request $request){
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

            $projectServices = new ProjectServices();

            $estimateConflict = [];
            foreach($request->estimate_sync as $idx => $cv){
                $version = $cv['version'] ?? null;
                $estimateToSync = new EstimateDisciplineClass();
                $estimateToSync->workItemId = $cv['workItem'];
                $estimateToSync->workItemDescription = $cv['workItemText'];
                $estimateToSync->workItemVolume = $cv['vol'] > 0 ? $cv['vol'] : 1;
                $estimateToSync->workItemManPowerCost = $cv['totalRateManPowers'];
                $estimateToSync->workItemEquipmentCost = $cv['totalRateEquipments'];
                $estimateToSync->workItemMaterialCost = $cv['totalRateMaterials'];
                $estimateToSync->workItemManPowerCostRate = $cv['labourUnitRate'];
                $estimateToSync->workItemEquipmentCostRate = $cv['equipmentUnitRate'];
                $estimateToSync->workItemMaterialCostRate = $cv['materialUnitRate'];
                $estimateToSync->laborFactorial = $cv['labourFactorial'];
                $estimateToSync->equipmentFactorial = $cv['equipmentFactorial'];
                $estimateToSync->materialFactorial = $cv['materialFactorial'];
                $estimateToSync->wbsLevel3Id = $cv['wbs_level3'];
                $estimateToSync->uniqueIdentifier = $cv['idx'];
                $estimateToSync->version = $version;

                $total = number_format($this->countTotalCostWorkItem($estimateToSync));
                $estimateToSync->total = $total;


                array_push($estimateConflict, $estimateToSync);
            }

            $uniqueIdentifierArr = [];
            $estimateAlreadySave = $data->map(function($item) use ($estimateConflict, &$uniqueIdentifierArr, $projectServices){
                $estimateToSync = new EstimateDisciplineClass();
                $estimateToSync->workItemId = $item->work_item_id;
                $estimateToSync->workItemDescription = $item->workItems?->description;
                $estimateToSync->workItemVolume = $item->volume;
                $estimateToSync->workItemManPowerCost = $item->labor_cost_total_rate;
                $estimateToSync->workItemEquipmentCost = $item->tool_unit_rate_total;
                $estimateToSync->workItemMaterialCost = $item->material_unit_rate_total;
                $estimateToSync->workItemManPowerCostRate = $item->labor_unit_rate;
                $estimateToSync->workItemEquipmentCostRate = $item->tool_unit_rate;
                $estimateToSync->workItemMaterialCostRate = $item->material_unit_rate;
                $estimateToSync->laborFactorial = $item->labour_factorial;
                $estimateToSync->equipmentFactorial = $item->equipment_factorial;
                $estimateToSync->materialFactorial = $item->material_factorial;
                $estimateToSync->wbsLevel3Id = $item->wbs_level3_id;
                $estimateToSync->version = $item->version;
                $estimateToSync->uniqueIdentifier = $item->unique_identifier;
                $estimateToSync->total = number_format($projectServices->getTotalCostWorkItem($item));
                $uniqueIdentifierArr[] = $item->unique_identifier;
                $estimateToSync->unit = $item->workItems?->unit;
                return $estimateToSync;
            });

            $arrConflict = [];
            foreach($estimateConflict as $ec){
                if(!in_array($ec->uniqueIdentifier, $uniqueIdentifierArr)){
                    if(!isset($ec->version)){
                        $arrConflict[] = $ec;
                    }
                };
            }

            $version = $estimateAlreadySave[0]->version;

             $data = [
                 'existingEstimate' => $estimateAlreadySave,
                 'itemToMerge' => $arrConflict,
                 'version' => $version,
                 'current_version' => $request->current_version
             ];

            return response()->json([
                'status' => 200,
                'data' => $data,
                'message' => 'Success Synchronize Data'
            ]);

        } catch (Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => $e->getMessage()
            ]);
        }
    }

    /*
     * This function is redundant with getTotalCostWorkItem
     */
    public function countTotalCostWorkItem($location){
        $labor_factorial = $location?->labourFactorial ?? 1;
        $tool_factorial = $location?->equipmentFactorial ?? 1;
        $material_factorial = $location?->materialFactorial ?? 1;
        $man_power_cost = (float) $location?->workItemManPowerCostRate * $labor_factorial;
        $tool_cost = (float) $location?->workItemEquipmentCostRate * $tool_factorial;
        $material_cost = (float) $location?->workItemMaterialCostRate * $material_factorial;
        $totalWorkItemCost = $man_power_cost +  $tool_cost + $material_cost;
        $totalWorkItemCost = $totalWorkItemCost * $location->workItemVolume;

        return $totalWorkItemCost;
    }
}
