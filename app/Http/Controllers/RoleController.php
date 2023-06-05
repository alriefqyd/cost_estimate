<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RoleController extends Controller
{
    public function dumpingData(){
        DB::beginTransaction();
        try{
            $i=0;
            foreach(ROLE::ACTION as $key => $value){
                foreach(ROLE::FEATURE as $k => $v){
                    $role = new Role();
                    $role->id = $i++;
                    $role->action = $key;
                    $role->feature = $k;
                    $role->name = $value .' '. $v;
                    $role->save();
                    DB::commit();
                }
            }
            return response()->json('success');
        } catch (Exception $e){
            DB::rollback();
            return response()->json($e->getMessage());
        }
    }

    public function isUserHaveAccess(){

    }
}
