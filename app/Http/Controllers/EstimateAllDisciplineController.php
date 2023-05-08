<?php

namespace App\Http\Controllers;

use App\Models\DisciplineProjects;
use App\Models\EstimateAllDiscipline;
use App\Models\LocationEquipments;
use App\Models\Project;
use App\Models\WbsLevel3;
use Illuminate\Http\Request;

class EstimateAllDisciplineController extends Controller
{
    public function getWorkItems(Request $request, Project $project){
        $data = EstimateAllDiscipline::with(['workItems.manPowers','workItems.equipmentTools','workItems.materials'])->where('project_id', $project->id)
            ->where('work_scope',$request->discipline)->get();
        return $data;
    }

    public function update(Request $request){
        if(sizeof($request->work_items) > 0){
            $existingEstimateDiscipline = $this->getExistingWorkItemByWbs($request);
            if($existingEstimateDiscipline){
                foreach ($existingEstimateDiscipline as $item){
                    $item->delete();
                }
            }
        }

        $workItemController = new WorkItemController();

        try {
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
                $estimateAllDiscipline->labor_cost_total_rate =  $workItemController->removeCommaCurrencyFormat($item['totalRateManPowers']);
                $estimateAllDiscipline->tool_unit_rate =  $workItemController->removeCommaCurrencyFormat($item['equipmentUnitRate']);
                $estimateAllDiscipline->tool_unit_rate_total =  $workItemController->removeCommaCurrencyFormat($item['totalRateEquipments']);
                $estimateAllDiscipline->material_unit_rate =  $workItemController->removeCommaCurrencyFormat($item['materialUnitRate']);
                $estimateAllDiscipline->material_unit_rate_total =  $workItemController->removeCommaCurrencyFormat($item['totalRateMaterials']);

                $estimateAllDiscipline->equipment_location_id = $request->work_element;
                $estimateAllDiscipline->save();
            }
            $response = [
                'status' => 200,
                'message' => 'Success, Your data was saved successfully'
            ];

            return response()->json($response);
        } catch (\Exception $e) {
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

    public function create(Project $project){
        $wbsLevel3 = WbsLevel3::where('project_id',$project->id)->get()->groupby('title');
        return view('work_item.create',[
            'project' => $project,
            'wbsLevel3' => $wbsLevel3,
        ]);
    }

    public function getExistingWorkItemByWbs($request){
        $data = EstimateAllDiscipline::with(['wbsLevels3.workElements.wbsDiscipline','workItems.materials',
            'workItems.manPowers','workItems.equipmentTools'])
            ->whereHas('wbsLevels3',function($q) use ($request){
                return $q->where('work_element',$request->level3);
            })->where('project_id',$request->project_id)->get();

        return $data;
    }

    public function setExistingWorkItemByWbs(Request $request){
        $data = $this->getExistingWorkItemByWbs($request);
        $dataEstimateDisciplineSummary = array();

        return response()->json([
            'status' => 200,
            'data' => $data
        ]);
    }

    public function removeEstimatedDisciplineByEquipmentLocationId($ids,$project_id){
        $data = EstimateAllDiscipline::where('project_id',$project_id)->whereNotIn('equipment_location_id',$ids)->get();
        foreach($data as $item){
            $item->delete();
        }
    }
}
