<?php

namespace App\Http\Controllers;

use App\Class\ProjectClass;
use App\Models\EstimateAllDiscipline;
use App\Models\Project;
use App\Models\WbsLevel3;
use App\Services\ProjectServices;
use Illuminate\Http\Request;
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
                        $map->push($projectClass); // Push the $projectClass object directly
                    }

                    $returnData = $e;
                    if(sizeof($map) > 0) $returnData = $map;
                    return [$e->workElements?->title => $returnData];
                });
            });
        });

        return view('estimate_all_discipline.create',[
            'project' => $project,
            'estimateAllDiscipline' => $wbs
        ]);
    }


    public function update(Request $request){
        $workItemController = new WorkItemController();
        DB::beginTransaction();
        try {
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


            foreach ($request->work_items as $idx => $item){
                $estimateAllDiscipline = new EstimateAllDiscipline();
                $estimateAllDiscipline->title = '';
                $estimateAllDiscipline->work_item_id = $item['workItem'];
                $estimateAllDiscipline->volume = $item['vol'];
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
                $estimateAllDiscipline->save();
            }
            $response = [
                'status' => 200,
                'message' => 'Success, Your data was saved successfully'
            ];

            DB::commit();
            return response()->json($response);
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

    /**
     * Hapus Estimate Discipline Yang Worlk Element nya tidak dari id terdaftar
     * @param $ids
     * @param $project_id
     * @return void
     */
    public function removeEstimatedDisciplineByEquipmentLocationId($ids,$project_id){
        if($ids) {
            $data = EstimateAllDiscipline::where('project_id',$project_id)->whereNotIn('wbs_level3_id',$ids)->get();
            foreach($data as $item){
                $item->delete();
            }
        }
    }
}
