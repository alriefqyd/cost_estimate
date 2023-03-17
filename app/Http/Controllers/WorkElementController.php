<?php

namespace App\Http\Controllers;

use App\Models\Project;
use App\Models\WorkElement;
use Illuminate\Http\Request;

class WorkElementController extends Controller
{
    public function index(){

    }

    public function store(Request $request, Project $project){
        // check existing id
        // if id exist update
        // if id not exist add new

        $existingIdElement = $project->workElements->map(function ($element){
            return $element->id;
        })->all();

        $workElements = $request->work_element;
        $workElementsSize = sizeof($workElements);
        try {
            for($i=0;$i<$workElementsSize;$i++){
                if($request->element_id[$i] != null){
                    $existingWorkElement = WorkElement::where('id',$request->element_id[$i])->first();
                    $existingWorkElement->name = $request->work_element[$i];
                    $existingWorkElement->save();
                } else {
                    $workElement = new WorkElement();
                    $workElement->name = $request->work_element[$i];
                    $workElement->work_scope = $request->discipline;
                    $workElement->projects()->associate($project->id);
                    $workElement->save();
                }
            }
            $diff = array_diff($existingIdElement,$request->element_id);
            foreach ($diff as $idDiff){
                WorkElement::where('id',$idDiff)->delete();
            }

            return redirect('/project/'.$project->id.'/estimate-discipline/create/'. $request->discipline);
        } catch (\Exception $e){
            return redirect('/project/'.$project->id.'/estimate-discipline/create/'. $request->discipline)->withErrors($e->getMessage());
        }
    }

    /**
     * Get All Work Element Based on Estimate Discipline
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getDataWorkElements(Request $request){
        $data = WorkElement::where('project_id',$request->project_id)
            ->where('work_scope',$request->discipline)
            ->when(isset($request->q),function ($q) use ($request){
                return $q->where('name','like','%'.$request->q.'%');
            })->get();
        return $data;
    }
    /**
     * Set Work Element Based on Project to Select2
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function setWorkElements(Request $request){
        $data = $this->getDataWorkElements($request);
        $response = array();
        foreach ($data as $item){
            $response[] = array(
                "text" => $item->name,
                "id" => $item->id
            );
        }
        return response()->json($response);
    }

}
