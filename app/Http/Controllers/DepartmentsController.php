<?php

namespace App\Http\Controllers;

use App\Models\Departments;
use Illuminate\Http\Request;

class DepartmentsController extends Controller
{
    public function getAllSubDepartment(){
        $data = Departments::where('type',Departments::TYPE['sub_department'])->get();
        return $data;
    }
}
