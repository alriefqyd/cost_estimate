<?php

namespace App\Http\Controllers;

use App\Models\ManPower;
use Illuminate\Http\Request;

class ManPowerController extends Controller
{
    public function index(){
        $man_power = ManPower::paginate(20)->withQueryString();
        return view('man_power.index',[
            'man_power' => $man_power
        ]);
    }
    public function getAllManPower(Request $request){
        $data = ManPower::with('workItems')->when($request->q, function ($q) use ($request){
            return $q->where('title','like','%'.$request->q.'%');
        })->get();

        return $data;
    }
}
