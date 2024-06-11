<?php

namespace App\Http\Controllers;

use App\Models\EstimateAllDiscipline;
use App\Models\Project;
use App\Models\WbsLevel3;
use App\Models\WorkBreakdownStructure;
use App\Models\WorkElement;
use App\Services\ProjectServices;
use Carbon\Carbon;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Date;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
        $discipline = $this->getDisciplineList($request);
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

        $projectServices = new ProjectServices();

        try {
              DB::beginTransaction();
              $arrIdNotDelete = [];
                foreach($request->wbs as $loc){
                    foreach($loc['children'] as $disc){
                        $oldIdentifier = $disc['identifier'] ?? null;
                        foreach($disc['children'] as $el){
                            $uniqId = uniqid();
                            if($loc['id']) $uniqId = $loc['id'];
                            $existing = WbsLevel3::where('identifier',$oldIdentifier)->where('discipline',$disc['id'])
                                ->where('work_element',$el['oldElement'])->where('project_id',$project->id)->first();
                            if(!$existing){ // add new if there's a wbs to add
                                $wbsLevel3 = new WbsLevel3();
                                $wbsLevel3->title = $loc['id'];
                                $wbsLevel3->discipline = $disc['id'];
                                $wbsLevel3->work_element = $el['id'];
                                $wbsLevel3->project_id = $project['id'];
                                $wbsLevel3->identifier = $uniqId;
                                $wbsLevel3->save();
                                $arrIdNotDelete[] = $wbsLevel3->id;
                            } else {

                                $ed = EstimateAllDiscipline::where('wbs_level3_id', $existing->id)->first();
                                if(isset($ed)){
                                    $ed->wbs_level3_id = $existing->id;
                                    $ed->save();
                                }

                                $existing->title = $loc['id'];
                                $existing->identifier = $uniqId;
                                $existing->work_element = $el['id'];
                                $existing->save();
                                $arrIdNotDelete[] = $existing->id;
                            }
                        }
                    }
                }

            // Delete WbsLevel3 that no longer exist
            WbsLevel3::whereNotIn('id', $arrIdNotDelete)->where('project_id', $project->id)->delete();

            // Delete EstimateAllDiscipline that don't have WbsLevel3
            EstimateAllDiscipline::whereNotIn('wbs_level3_id', $arrIdNotDelete)->where('project_id', $project->id)->delete();
            $projectServices->setStatusDraft($project);
            $project->save();
            DB::commit();

            $response = [
                'status' => 200,
                'message' => 'Success, Your data was updated successfully'
            ];
            return response()->json($response);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([
                'status' => 500,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function storeWbs(Project $project, Request $request){
        foreach($request->wbs as $loc){
            $uniqId = uniqid();
            foreach($loc['children'] as $disc){
                foreach($disc['children'] as $el){
                    $wbs = new WbsLevel3();
                    $wbs->type = 'location';
                    $wbs->title = $loc['id'];
                    $wbs->discipline = $disc['id'];
                    $wbs->work_element = $el['id'];
                    $wbs->project_id = $project->id;
                    $wbs->identifier = $loc['id'];
                    $wbs->save();
                }
            }
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

    public function generateWorkBreakdown(Project $project){
        $data = WbsLevel3::with(['wbsDiscipline','workElements.parent'])->where('project_id',$project->id)->get()->groupBy('identifier');
        $data = $data->map(function($discipline){
            return $discipline->mapToGroups(function($d) use ($discipline) {
                return [
                    $d->wbsDiscipline?->title => [
                        'id' => $d->id,
                        'title' => $d->work_element,
                        'identifier' => $discipline->first()->identifier,
                        'disciplineId' => $d->discipline,
                        'elementId' => $d->workElements?->id
                    ]
                ];
            });
        });
        return $data;
    }

    public function deleteWbs(Project $project, Request $request){
        $data = WbsLevel3::where('project_id', $project->id)->get();
        foreach($data as $item){
            $item->delete();
        }
    }

    public function getDisciplineList(Request $request){
        $data = WorkBreakdownStructure::select('code','title','id')->with('wbsDiscipline')->where('level', 2)->get();
        if($request->isMustache) {
            try{
                return response()->json([
                    'status' => 200,
                    'data' => $data
                ]);
            } catch (Exception $e){
                return response()->json([
                    'status' => 500,
                    'message' => $e->getMessage()
                ]);
            }
        }
        return $data;
    }

    public function getWorkElementList(Request $request){
        $workElementController = new WorkElementController();
        $data = $workElementController->getDataWorkElements($request);

        return response()->json([
            'status' => 200,
            'data' => $data
        ]);
    }

    public function deleteWbsLevel3MoreOneMonth(){
        try {
            DB::beginTransaction();
            $date = Carbon::now()->subMonth();
            WbsLevel3::whereNotNull('deleted_at')
                ->where('deleted_at', '<', $date)
                ->forceDelete();
            DB::commit();
            Log::info("Data WBS Level 3 deleted more one month successfully hard delete");
        } catch (Exception $e) {
            Log::error("Error Delete WBS Level 3 : ". $e->getMessage());
            DB::rollBack();
        }
    }
}
