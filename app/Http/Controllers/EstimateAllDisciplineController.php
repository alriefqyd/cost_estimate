<?php

namespace App\Http\Controllers;

use App\Class\EstimateDisciplineClass;
use App\Class\ProjectClass;
use App\Models\EstimateAllDiscipline;
use App\Models\Material;
use App\Models\Project;
use App\Models\WbsLevel3;
use App\Services\ProjectServices;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
        if(!$project->isDesignEngineer())  {
            abort(403);
        }
        $discipline = auth()->user()->profiles?->position;
        $discipline = explode('_',$discipline)[1];
        $statusEstimate = collect(json_decode($project->estimate_discipline_status));
        $statusEstimate = $statusEstimate->filter(function ($item) use ($discipline){
            $position = 'design_engineer_'.$discipline;
            return $item->position == $position;
        })->pluck('status')->first();

        if($statusEstimate == "PUBLISH") abort(403);
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

    public function update(Project $project, Request $request){
        $workItemController = new WorkItemController();
        $projectServices = new ProjectServices();
        DB::beginTransaction();
        try {
            $newArrEstimateAllDiscipline = [];
            $record = EstimateAllDiscipline::where('project_id',$request->project_id)->first();
            $workItems = json_decode($request->work_items, true); // Decode JSON string into array
            if(sizeof($workItems) > 0){

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
                foreach ($workItems as $idx => $item){
                    $estimateAllDiscipline = new EstimateAllDiscipline();
                    $estimateAllDiscipline->title = $item['workItemText'];
                    $estimateAllDiscipline->work_item_id = $item['workItem'];
                    $estimateAllDiscipline->volume = $item['vol'] > 0 ? $item['vol'] : 1;
                    $estimateAllDiscipline->project_id = isset($request->project_id) ? $request->project_id : $item['project_id'];
                    $estimateAllDiscipline->labour_factorial = $item['labourFactorial'] > 0 ? $item['labourFactorial'] : NULL;
                    $estimateAllDiscipline->equipment_factorial = $item['equipmentFactorial'] > 0 ? $item['equipmentFactorial'] : NULL;
                    $estimateAllDiscipline->material_factorial = $item['materialFactorial'] > 0 ? $item['materialFactorial'] : NULL;
                    $estimateAllDiscipline->labor_unit_rate =  $workItemController->strToFloat($item['labourUnitRate']);
                    $estimateAllDiscipline->labor_cost_total_rate = $workItemController->strToFloat($item['totalRateManPowers']) * $item['vol'];
                    $estimateAllDiscipline->tool_unit_rate =  $workItemController->strToFloat($item['equipmentUnitRate']);
                    $estimateAllDiscipline->tool_unit_rate_total =  $workItemController->strToFloat($item['totalRateEquipments']) * $item['vol'];
                    $estimateAllDiscipline->material_unit_rate =  $workItemController->strToFloat($item['materialUnitRate']);
                    $estimateAllDiscipline->material_unit_rate_total =  $workItemController->strToFloat($item['totalRateMaterials']) * $item['vol'];
                    $estimateAllDiscipline->wbs_level3_id = $item['wbs_level3'];
                    $estimateAllDiscipline->equipment_location_id = $item['work_element'];
                    $estimateAllDiscipline->unique_identifier = $item['idx'];
                    $estimateAllDiscipline->version = $newVersion;
                    $estimateAllDiscipline->save();
                }

                // Save the contingency in project_settings
                $projectSetting = $project->projectSettings()->updateOrCreate(
                    ['project_id' => $project->id],
                    ['contingency' => $request->contingency]
                );

                $statusEstimate = collect(json_decode($project->estimate_discipline_status));
                $user = auth()->user();
                $position = explode('_', $user->profiles?->position)[1];
                $positionDesign = 'design_engineer_'.$position;

                $statusEstimate->map(function ($item) use ($request, $positionDesign){
                    //find by user the position status tu update
                   if($item->position == $positionDesign){
                       $item->status = $request->estimateStatus;
                   };

                   return $item;
                });

                $projectServices->setStatusDraft($project);

                $project->estimate_discipline_status = $statusEstimate;

                if($request->estimateStatus == 'PUBLISH'){
                    $projectServices->sendEmailToReviewer($project, $position);
                    $projectServices->setRejectedDisciplineToWaiting($project);
                }
                $project->save();
                DB::commit();

                $response = [
                    'status' => 200,
                    'message' => 'Success, Your data was saved successfully',
                    'version' => $newVersion
                ];

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
            $data = EstimateAllDiscipline::with(['workItems.materials'])->where('project_id', $request->project_id)
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
                $estimateToSync->workItemEquipmentCostRate = $cv['equipmentUnitRate'] ?? null;
                $estimateToSync->workItemMaterialCostRate = $cv['materialUnitRate'] ?? null;
                $estimateToSync->laborFactorial = $cv['labourFactorial'];
                $estimateToSync->equipmentFactorial = $cv['equipmentFactorial'];
                $estimateToSync->materialFactorial = $cv['materialFactorial'];
                $estimateToSync->wbsLevel3Id = $cv['wbs_level3'];
                $estimateToSync->uniqueIdentifier = $cv['idx'];
                $estimateToSync->version = $version;

                $total = number_format($this->countTotalCostWorkItem($estimateToSync),'2',',','.');
                $estimateToSync->total = $total;


                array_push($estimateConflict, $estimateToSync);
            }

            $uniqueIdentifierArr = [];
            $estimateAlreadySave = $data->map(function($item) use ($estimateConflict, &$uniqueIdentifierArr, $projectServices){
                $material = $item->workItems?->materials;
                $totalMaterial = $material->reduce(function($accumulator, $value){
                    $total = $value->rate * $value->pivot?->quantity;
                    return $accumulator + $total;
                }, 0);

                $equipmentTools = $item->workItems?->equipmentTools;
                $totalEquipmentTools = $equipmentTools->reduce(function ($accumulator, $value){
                    $total = $value->local_rate * $value->pivot?->quantity;
                    return $accumulator + $total;
                },0);

                $manPowers = $item->workItems?->manPowers;
                $totalManPowers = $manPowers->reduce(function ($accumulator, $value){
                   $total = $value->overall_rate_hourly * $value->pivot?->labor_coefisient;
                   return $accumulator + $total;
                },0);


                $estimateToSync = new EstimateDisciplineClass();
                $estimateToSync->workItemId = $item->work_item_id;
                $estimateToSync->workItemDescription = $item->workItems?->description;
                $estimateToSync->workItemVolume = $item->volume;
                $estimateToSync->workItemManPowerCost = $totalManPowers;
                $estimateToSync->workItemEquipmentCost = $totalEquipmentTools;
                $estimateToSync->workItemMaterialCost = $totalMaterial;
                $estimateToSync->workItemManPowerCostRate = $totalManPowers;
                $estimateToSync->workItemEquipmentCostRate = $totalEquipmentTools;
                $estimateToSync->workItemMaterialCostRate = $totalMaterial;
                $estimateToSync->laborFactorial = $item->labour_factorial;
                $estimateToSync->equipmentFactorial = $item->equipment_factorial;
                $estimateToSync->materialFactorial = $item->material_factorial;
                $estimateToSync->wbsLevel3Id = $item->wbs_level3_id;
                $estimateToSync->version = $item->version;
                $estimateToSync->uniqueIdentifier = $item->unique_identifier;
                $estimateToSync->total = number_format($this->countTotalCostWorkItem($estimateToSync),'2',',','.');;
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
        $man_power_cost = (float) $location?->workItemManPowerCost * $labor_factorial;
        $tool_cost = (float) $location?->workItemEquipmentCost * $tool_factorial;
        $material_cost = (float) $location?->workItemMaterialCost * $material_factorial;
        $totalWorkItemCost = $man_power_cost +  $tool_cost + $material_cost;
        $totalWorkItemCost = $totalWorkItemCost * (float) $location->workItemVolume;

        return $totalWorkItemCost;
    }

    public function deleteEstimateDisciplineMoreOneMonth(){
        try{
            DB::beginTransaction();
            $date = Carbon::now()->subMonth();
            $estimateDiscipline = EstimateAllDiscipline::whereNotNull('deleted_at')
                ->where('deleted_at','<', $date)->forceDelete();
            DB::commit();
            Log::info('Data Estimate Discipline deleted more one month successfully hard delete');
        } catch (Exception $e){
            DB::rollBack();
            Log::error($e->getMessage());
        }
    }
}
