<?php

namespace App\Http\Controllers;

use App\Models\DisciplineProjects;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DisciplineProjectsController extends Controller
{
    public function saveDiscipline(Request $request){
        $disciplines = $request->arrayDiscipline;
        try {
            foreach($disciplines as $item){
                foreach($item['discipline'] as $subItem){
                    $disciplineProject = new DisciplineProjects();
                    $disciplineId = DB::table('work_breakdown_structures')->where('code',$subItem)->first();
                    $disciplineProject->discipline_id = $disciplineId->id;
                    $disciplineProject->location_id = $item['location'];
                    $disciplineProject->save();
                }
            }

            return response()->json([
                'status' => 200,
                'message' => 'success'
            ]);

        }catch(\Exception $e){
            return response()->json([
                'status' => 500,
                'message' => $e->getMessage()
            ]);
        }

    }
}
