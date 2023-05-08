<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\Setting;
use App\Models\WbsLevel3;
use App\Models\WorkBreakdownStructure;
use App\Models\WorkElement;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class WorkBreakdownStructureController extends Controller
{
    public function getWorkElement(Request $request){
        $discipline = WorkBreakdownStructure::where('title',$request->discipline)->first();
        $data = WorkBreakdownStructure::when(isset($discipline),function ($q) use ($discipline){
           return $q->where('parent_id',$discipline->id);
        })->get();

        return $data;
    }

    public function create(Request $request, Project $project){
        $disciplines = WorkBreakdownStructure::where('level',2)->get();
        $workElement = WorkElement::where('project_id',$project->id)->where('work_scope',$request->discipline)->get();
        if(isset($request->discipline)
            && !array_key_exists($request->discipline,Setting::DISCIPLINE)){
            abort(404);
        }

        return view('work_breakdown_structure.create',[
            'project' => $project,
            'isEmptyWorkElement' => sizeof($workElement) < 1,
            'workElement' => $workElement,
            'disciplineList' => $disciplines,
        ]);
    }

    public function edit(Request $request, Project $project){
        $disciplines = WorkBreakdownStructure::where('level',2)->get();
        $workElement = WorkElement::where('project_id',$project->id)->where('work_scope',$request->discipline)->get();
//         WbsLevel3::with(['workBreakdownStructures'])->where('project_id',$project->id)->get()->groupBy('title');
        $existingWbs = $this->generateWorkBreakdown($project);
        return view('work_breakdown_structure.create',[
            'project' => $project,
            'workElement' => $workElement,
            'disciplineList' => $disciplines,
            'existingWbs' => $existingWbs
        ]);
    }

    public function store(Request $request, Project $project){
        try {
            $this->storeWbs($project,$request);
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

    public function storeWbs(Project $project, Request $request){
       $this->processStoreWbs($project,$request,true);
    }

    public function getIdWbsRemoved(Project $project, Request $request){
        $idTobeRemove = $this->processStoreWbs($project, $request,false);
        return $idTobeRemove;
    }

    public function processStoreWbs(Project $project, Request $request, $isSave){
        $arrIdRemoved = array();
        foreach ($request->wbs as $item){
            foreach ($item['discipline'] as $discipline){
                $arrWorkElement = array();
                if(isset($discipline['work_element'])){
                    foreach ($discipline['work_element'] as $key => $value){
                        $wbsLevel3 = new WbsLevel3();
                        $wbsLevel3->type = $item['type'];
                        $wbsLevel3->title = $item['title'];
                        $wbsLevel3->discipline = $discipline['id'];
                        $wbsLevel3->work_element = $value;
                        $wbsLevel3->project_id = $project->id;
                        $wbsLevel3->save();
                        if(!$isSave) {
                            if($wbsLevel3->work_element != 'null') array_push($arrIdRemoved,$wbsLevel3->work_element);
                        }
                    }
                }
            }
        }
        if(!$isSave) return $arrIdRemoved;
    }

    public function generateWorkBreakdown(Project $project){
        $data2 = DB::table('wbs_level3s')->leftjoin('work_breakdown_structures','wbs_level3s.discipline','=','work_breakdown_structures.id')
            ->select('type','wbs_level3s.*')->where('project_id',$project->id)->get()->groupBy('title');
        $data = WbsLevel3::with(['workElements'])->where('project_id',$project->id)->get()->groupBy('title');
        $arrData = array();
//        foreach($data2 as $key => $value){
//            $arrList = array();
//            dd($value->groupBy('discipline'));
//            foreach($value as $list){
//                $arrWorkElement = array();
//                $obj = json_decode($list->work_element);
//                foreach($obj as $work_element){
//                    $titleWorkElement = WorkBreakdownStructure::where('id', $work_element->value)->first();
//                    $title = '';
//                    if($titleWorkElement) $title = $titleWorkElement->title;
//                    $dataWorkElement = [
//                        'id' => $work_element->value,
//                        'desc' => $title
//                    ];
//                    array_push($arrWorkElement, $dataWorkElement);
//                }
//                $dataList = [
//                    'discipline' => $list->discipline,
//                    'work_element' => $arrWorkElement
//                ];
//                array_push($arrList,$dataList);
//            }
//            $formatData = [
//                'location' => $key,
//                'type' => $value[0]->type,
//                'discipline_work_element' => $arrList
//            ];
//
//            array_push($arrData,$formatData);
//        }

        return $data;
    }

    public function update(Project $project, Request $request){
        try {
            $estimateAllDisciplineController = new EstimateAllDisciplineController();
            $equipmentLocationIdToRemove = $this->getIdWbsRemoved($project,$request);
            $this->deleteWbs($project,$request);
            $this->storeWbs($project,$request);
            $estimateAllDisciplineController->removeEstimatedDisciplineByEquipmentLocationId($equipmentLocationIdToRemove,$project->id);
            $response = [
                'status' => 200,
                'message' => 'Success, Your data was update successfully'
            ];
            return response()->json($response);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 500,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function deleteWbs(Project $project, Request $request){
        $data = WbsLevel3::where('project_id', $project->id)->get();
        foreach($data as $item){
            $item->delete();
        }

    }

    public function getWbsLevel2(Request $request){
        try{
            $data = WbsLevel3::with(['wbsDiscipline','workElements'])->where('project_id',$request->project_id)
                ->where('title', $request->level1)
                ->get()->groupBy('wbsDiscipline.title');
            $arrayObj = array();

            foreach($data as $key => $value){
                $dataCollect = new Collection([
                    "text" => $key,
                    "id" => $value->first()->wbsDiscipline->id
                ]);

                array_push($arrayObj,$dataCollect);
            }

            return response()->json([
                'status' => 200,
                'data' => $arrayObj,
            ]);
        }catch(Exception $e){
            return response()->json([
                'status' => 500,
                'data' => $e->getMessage()
            ]);
        }

    }

    public function getWbsLevel3(Request $request){
        $data = WbsLevel3::with('workElements')->where('project_id',$request->project_id)->where('discipline',$request->level2)->get();

        $arrayObj = array();
        foreach($data as $key => $value){
            $data = new Collection([
                "id" => $value->workElements?->id,
                "text" => $value->workElements?->title
            ]);

            array_push($arrayObj,$data);
        }

        return response()->json([
            'status' => 200,
            'data' => $arrayObj
        ]);
    }
}
