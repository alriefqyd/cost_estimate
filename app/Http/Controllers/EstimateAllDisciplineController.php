<?php

namespace App\Http\Controllers;

use App\Models\EstimateAllDiscipline;
use App\Models\Project;
use App\Models\WorkElement;
use App\Models\WorkItemType;
use Illuminate\Http\Request;

class EstimateAllDisciplineController extends Controller
{
    public function create(Project $project){
        //select data by project, if estimate discipline with the user discipline exist, open it and update, else will be create new one
        return view('estimate_discipline.create',[
            'project' => $project
        ]);
    }

    /**
     * Save Work Element
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function storeWorkElement(Request $request, Project $project){
        $workElements = $request->work_element;
        $workElementsSize = sizeof($workElements);
//        EstimateAllDiscipline::create([
//            'project_id' => $project->id
//        ]);

        for($i=1;$i<=$workElementsSize;$i++){
            $workElement = new WorkElement();
            $workElement->name = $request->work_element[$i];
            $workElement->save();
        }

        return $workElements;
    }
}
