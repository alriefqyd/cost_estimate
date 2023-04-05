<?php

namespace App\Http\Controllers;

use App\Models\DisciplineProjects;
use App\Models\EstimateAllDiscipline;
use App\Models\LocationEquipments;
use App\Models\ManPower;
use App\Models\Project;
use App\Models\Setting;
use App\Models\WorkElement;
use App\Models\WorkItem;
use App\Models\WorkItemType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class EstimateAllDisciplineController extends Controller
{
    public function getWorkItems(Request $request, Project $project){
        $data = EstimateAllDiscipline::with(['workItems.manPowers','workItems.equipmentTools','workItems.materials'])->where('project_id', $project->id)
            ->where('work_scope',$request->discipline)->get();
        return $data;
    }

    public function create(Request $request, Project $project){
        //select data by project, if estimate discipline with the user discipline exist, open it and update, else will be create new one
        /*$data = EstimateAllDiscipline::with(['projects','workElements'])->whereHas('projects',function ($q) use ($request) {
            return $q->where('id',$request->project_id);
        });*/

        if(isset($request->discipline) &&
            !array_key_exists($request->discipline,Setting::DISCIPLINE)){
            response(404);
        }
        $workItem = new WorkItemController();
        $disciplines = Setting::DISCIPLINE;
        $existingLocationEquipment = LocationEquipments::with(['disciplineProjects'])->where('project_id',$project->id)->get();
//        dd($existingLocationEquipment);
//        $existingDisciplineProject = DisciplineProjects::with(['disciplineProjects'])->where('project_id',$project->id)->get();
        $workElement = WorkElement::where('project_id',$project->id)->where('work_scope',$request->discipline)->get();
        if(isset($request->discipline)
            && !array_key_exists($request->discipline,Setting::DISCIPLINE)){
            abort(404);
        }

        return view('estimate_discipline.create',[
            'project' => $project,
            'isEmptyWorkElement' => sizeof($workElement) < 1,
            'workElement' => $workElement,
            'workItem' => $this->getWorkItems($request, $project),
            'disciplines' => $disciplines,
            'locationEquipments' => $existingLocationEquipment,
//            'disciplineProjects' => $existingDisciplineProject
        ]);
    }

    public function store(Request $request){
        $workItemController = new WorkItemController();
        try {
            foreach ($request->work_items as $item){
                $estimateAllDiscipline = new EstimateAllDiscipline();
                $estimateAllDiscipline->title = '';
                $estimateAllDiscipline->work_scope = $request->discipline;
                $estimateAllDiscipline->work_item_id = $item['workItem'];
                $estimateAllDiscipline->work_element_id = $item['workElement'];
                $estimateAllDiscipline->volume = $item['vol'];
                $estimateAllDiscipline->project_id = $request->project_id;
                $estimateAllDiscipline->save();
            }
            $response = [
                'status' => 200,
                'message' => 'Success, Your data was saved successfully'
            ];

            return response()->json($response);
        } catch (\Exception $e) {
            return response()->json($e->getMessage());
        }
    }

    public function update(Request $request){
        $workItemController = new WorkItemController();
        if(sizeof($request->work_items) > 0){
            $existingEstimateDiscipline = EstimateAllDiscipline::where('project_id',$request->project_id)
                ->where('work_scope',$request->discipline)->get();
            foreach ($existingEstimateDiscipline as $item){
                $item->delete();
            }
        }

        try {
            foreach ($request->work_items as $idx => $item){
                $estimateAllDiscipline = new EstimateAllDiscipline();
                $estimateAllDiscipline->title = '';
                $estimateAllDiscipline->work_scope = $request->discipline;
                $estimateAllDiscipline->work_item_id = $item['workItem'];
                $estimateAllDiscipline->work_element_id =$item['workElement'];
                $estimateAllDiscipline->volume = $item['vol'];
                $estimateAllDiscipline->project_id = $request->project_id;
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
}
