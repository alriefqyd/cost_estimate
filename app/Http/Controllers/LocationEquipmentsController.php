<?php

namespace App\Http\Controllers;

use App\Models\LocationEquipments;
use Illuminate\Http\Request;

class LocationEquipmentsController extends Controller
{
    public function saveLocation(Request $request){
        $locations = $request->arrayLocation;
        $locationIdArr = array();
        try {
            $this->deleteLocation($request);
            foreach ($locations as $location){
//                $existingLocation = LocationEquipments::where('title',$location)->first();
                $locationEquipments = new LocationEquipments();
//                if($existingLocation){
//                    $locationEquipments = $existingLocation;
//                }
                $locationEquipments->project_id = $request->project_id;
                $locationEquipments->title = $location;
                $locationEquipments->save();
                array_push($locationIdArr,$locationEquipments);
            }

            return response()->json([
                'status' => 200,
                'message' => 'Success',
                'arrId' => $locationIdArr
            ]);
        } catch (\Exception $e){
            return response()->json([
               'status' => 500,
               'message' => $e->getMessage()
            ]);
        }
    }

    public function deleteLocation(Request $request){
        $data = LocationEquipments::where('project_id',$request->project_id)->get();
        foreach ($data as $i){
            $i->delete();
        }
    }
}
