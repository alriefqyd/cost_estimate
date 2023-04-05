<?php

namespace App\Http\Controllers;

use App\Models\ManPower;
use App\Models\Material;
use Illuminate\Http\Request;

class MaterialController extends Controller
{
    public function getAllMaterial(Request $request){
        $material = Material::with('workItems')->when($request->q, function ($q) use ($request){
            return $q->where('tool_equipment_description',$request->q);
        })->get();

        return $material;
    }
}
