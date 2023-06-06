<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\WbsLevel3;
use App\Models\WorkBreakdownStructure;

/**
 * Deprecated
 */
class WbsLevel3Controller extends Controller
{
    public function getWorkElementJson($value){
        $jsonObj = json_decode($value);
        return $jsonObj;
    }

    public function getWorkElementByProjectAndLocation($id,$title){
        $arrWorkElement = [];
        $data = WbsLevel3::where('project_id',$id)->where('title',$title)->get();
        foreach($data as $element){
            $jsonObj = json_decode($element->work_element);
            foreach($jsonObj as $k => $v){
                array_push($arrWorkElement,$v->value);
            }
        }
        return $arrWorkElement;
    }

    public function countWorkElement($id,$title){
        $countWorkElement = count($this->getWorkElementByProjectAndLocation($id,$title));
        return $countWorkElement;
    }

    public function countWorkElementById($id){
        $arrWorkElement = [];
        $data = WbsLevel3::where('id',$id)->first();
        $jsonObj = json_decode($data->work_element);
        foreach($jsonObj as $k => $v){
            array_push($arrWorkElement,$v->value);
        }

        return count($arrWorkElement);
    }

    public function getDetailWorkElementById($id){
        if($id == 'null') return '';
        $data = WorkBreakdownStructure::where('id',$id)->first();
        return $data?->title;
    }
}
