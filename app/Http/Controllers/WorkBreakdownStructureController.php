<?php

namespace App\Http\Controllers;

use App\Models\EstimateAllDiscipline;
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
        if(!auth()->user()->can('create',WbsLevel3::class)){
            return view('not_authorized');
        }

        $disciplines = WorkBreakdownStructure::where('level',2)->get();
        $workElement = WorkElement::where('project_id',$project->id)->where('work_scope',$request->discipline)->get();
        $discipline = WorkBreakdownStructure::select('code')->with('wbsDiscipline')->where('level',2)->get();

        if(isset($request->discipline)
            && !array_key_exists($request->discipline,$discipline)){
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
        if(!auth()->user()->can('update',WbsLevel3::class)){
            return view('not_authorized');
        }
        $disciplines = WorkBreakdownStructure::where('level',2)->get();
        $workElement = WorkElement::where('project_id',$project->id)->where('work_scope',$request->discipline)->get();
        $existingWbs = $this->generateWorkBreakdown($project);
        return view('work_breakdown_structure.create',[
            'project' => $project,
            'workElement' => $workElement,
            'disciplineList' => $disciplines,
            'existingWbs' => $existingWbs
        ]);
    }

    public function store(Request $request, Project $project){
        if(!auth()->user()->can('create',WbsLevel3::class)){
            abort(403);
        }

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

    public function update(Project $project, Request $request){
        if(!auth()->user()->can('update',WbsLevel3::class)){
            return response()->json([
                'status' => 403,
                'message' => "You're not authorized"
            ]);
        }

        try {
              $data = $this->processData($request);
              $arrIdNotDelete = [];
              foreach($data as $item){
                  $existing = WbsLevel3::where('identifier',$item->identifier)->where('type',$item->type)->where('discipline',$item->discipline)
                      ->where('work_element',$item->work_element)->where('project_id',$project->id)->first();
                  if(!$existing){ // add new if there's a wbs to add
                      $wbsLevel3 = new WbsLevel3();
                      $wbsLevel3->type = $item->type;
                      $wbsLevel3->title = $item->title;
                      $wbsLevel3->discipline = $item->discipline;
                      $wbsLevel3->work_element = $item->work_element;
                      $wbsLevel3->project_id = $project->id;
                      $wbsLevel3->identifier = $item->identifier;
                      $wbsLevel3->save();
                      $arrIdNotDelete[] = $wbsLevel3->id;
                  } else {
                      $existing->type = $item->type;
                      $existing->title = $item->title;
                      $existing->save();
                      $arrIdNotDelete[] = $existing->id;
                  }
              }

              WbsLevel3::whereNotIn('id',$arrIdNotDelete)->where('project_id',$project->id)->delete(); //delete wbsLevel3 that not exist anymore
              EstimateAllDiscipline::whereNotIn('wbs_level3_id',$arrIdNotDelete)->where('project_id',$project->id)->delete();

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

    public function storeWbs(Project $project, Request $request){
       $data = $this->processData($request);
       foreach($data as $item){
           $wbsLevel3 = new WbsLevel3();
           $wbsLevel3->type = $item->type;
           $wbsLevel3->title = $item->title;
           $wbsLevel3->discipline = $item->discipline;
           $wbsLevel3->work_element = $item->work_element;
           $wbsLevel3->project_id = $project->id;
           $wbsLevel3->identifier = $item->identifier;
           $wbsLevel3->save();
       }
    }

    public function processData(Request $request){
        $arrData = array();
        foreach ($request->wbs as $item){
            $identifier = $item['identifier'];
            foreach ($item['discipline'] as $discipline){
                if(!$identifier) $identifier = uniqId();
                if(isset($discipline['work_element'])){
                    foreach ($discipline['work_element'] as $key => $value){
                        $workElement = $value;
                        $oldWbsId = '';
                        $jsonArray = json_decode($workElement);
                        if (isset($jsonArray?->value) && isset($jsonArray?->oldWbsId)
                            && (json_last_error() == JSON_ERROR_NONE)) {
                            $workElement = $jsonArray?->value;
                            $oldWbsId = $jsonArray?->oldWbsId;
                        }

                        $data = [
                            'type' => $item['type'],
                            'title' => $item['title'],
                            'discipline' => $discipline['id'],
                            'work_element' => $workElement,
                            'identifier' => $identifier,
                            'oldWbsId' => $oldWbsId
                        ];
                        $obj = json_decode(json_encode($data), false);
                        array_push($arrData,$obj);
                    }
                }
            }
        }
        return $arrData;
    }

    /**
     * Deprecated
     * @param Project $project
     * @param Request $request
     * @return void
     */
    public function updateEstimateWbs(Project $project, Request $request){
        $data = $this->processData($request);
        $arrayIdEstimate = array();
        foreach($data as $item){
            $newWbs = WbsLevel3::where('project_id',$project->id)->where('discipline',$item->discipline)
                ->where('work_element',$item->work_element)->where('identifier',$item->identifier)
                ->first();
            $estimateDiscipline = EstimateAllDiscipline::where('wbs_level3_id',$item?->oldWbsId)->where('project_id',$project->id)->first();
            if($newWbs && $estimateDiscipline){
                $estimateDiscipline->wbs_level3_id = $newWbs->id;
                array_push($arrayIdEstimate,  $estimateDiscipline->wbs_level3_id);
                $estimateDiscipline->save();
            }
        }

        $toDelete = EstimateAllDIscipline::where('project_id',$project->id)->whereNotIn('wbs_level3_id',$arrayIdEstimate)->get();
        foreach($toDelete as $v){
            $v->delete();
        }
    }

    public function generateWorkBreakdown(Project $project){
        $data = WbsLevel3::with(['workElements'])->where('project_id',$project->id)->get()->groupBy('title');
        return $data;
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
                ->where('identifier', $request->level1)
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
        $data = WbsLevel3::with('workElements')->where('project_id',$request->project_id)->where('discipline',$request->level2)
            ->where('identifier',$request->level1)->get();

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
