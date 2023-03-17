<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified'
])->group(function () {
    Route::get('/', [\App\Http\Controllers\ProjectController::class,'index'])->name('project.index')->middleware('auth');
});

Route::get('/project',[\App\Http\Controllers\ProjectController::class,'index'])->middleware('auth');
Route::post('/project',[\App\Http\Controllers\ProjectController::class,'store'])->middleware('auth');
Route::get('/project/create',[\App\Http\Controllers\ProjectController::class,'create'])->middleware('auth');
Route::get('/project/{project:id}',[\App\Http\Controllers\ProjectController::class,'detail'])->middleware('auth');
Route::get('/project/{project:id}/discipline/{discipline}',[\App\Http\Controllers\ProjectController::class,'detail'])->middleware('auth');
//Route::get('/cost-estimate/project/detail',[\App\Http\Controllers\CostEstimateController::class,'detail'])->middleware('auth');
Route::get('/project/{project:id}/estimate-discipline/create',[\App\Http\Controllers\EstimateAllDisciplineController::class,'create'])->middleware('auth');
Route::get('/project/{project:id}/estimate-discipline/create/{discipline}',[\App\Http\Controllers\EstimateAllDisciplineController::class,'create'])->middleware('auth');
Route::post('/project/{project:id}/estimate-discipline/store',[\App\Http\Controllers\EstimateAllDisciplineController::class,'store'])->middleware('auth');
Route::post('/project/{project:id}/estimate-discipline/update',[\App\Http\Controllers\EstimateAllDisciplineController::class,'update'])->middleware('auth');

Route::post('/workElement/{project:id}',[\App\Http\Controllers\WorkElementController::class,'store'])->middleware('auth');
Route::get('/getWorkItems',[\App\Http\Controllers\WorkItemController::class,'setWorkItems'])->name('getWorkItem')->middleware('auth');
Route::get('/getWorkElement',[\App\Http\Controllers\WorkElementController::class,'setWorkElements'])->name('getWorkElement');
//Route::get('/cost-estimate/detail',[\App\Http\Controllers\EstimateAllDisciplineController::class,'detail'])->middleware('auth');




Route::get('/getUserEmployee',[\App\Http\Controllers\UserController::class,'getUserEmployee'])->name('getUserEmployee')->middleware('auth');
Route::get('/checkProjectNo',[\App\Http\Controllers\ProjectController::class,'checkDuplicateProjectNo'])->name('checkDuplicateProjectNo')->middleware('auth');


