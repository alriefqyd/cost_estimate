<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;

class GuidelinesController extends Controller
{
    public function index(){
        $data = Setting::where('setting_type', Setting::GUIDELINES_PAGE_TYPE)->where('setting_name', 'Home')
            ->first();
        return view('guidelines.guidelines',[
            'page' => $data->setting_value
        ]);
    }
}
