<?php

namespace App\Services;

use App\Models\Project;
use App\Models\WorkElement;

class WorkElementServices
{
    public function storeWorkElement($request, Project $project){
        $workElements = $request->work_element;
        $workElementsSize = sizeof($workElements);
        $workElement = new WorkElement();

        try{
            for($i=0;$i<$workElementsSize;$i++){
                $workElement->name = $workElements[$i];
                $workElement->save();
            }
            return true;
        } catch (\Exception $e){
            return false;
        }
    }
}
