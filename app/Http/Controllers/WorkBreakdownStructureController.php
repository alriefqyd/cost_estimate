<?php

namespace App\Http\Controllers;

use App\Models\WorkBreakdownStructure;
use Illuminate\Http\Request;
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
}
