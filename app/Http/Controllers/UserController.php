<?php

namespace App\Http\Controllers;

use App\Services\UserServices;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function __construct()
    {
        $this->userService = new UserServices();
    }

    public function getUserEmployee(Request $request){
        return response()->json($this->userService->getUserEmployee($request));
    }
}
