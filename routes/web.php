<?php

use App\Models\WorkBreakdownStructure;
use Illuminate\Support\Facades\Route;
use App\Models\Project;
use App\Models\User;

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
Route::put('/project/{project:id}',[\App\Http\Controllers\ProjectController::class,'update'])->middleware('auth');
Route::get('/project/create',[\App\Http\Controllers\ProjectController::class,'create'])->middleware('auth');
Route::get('/project/{project:id}',[\App\Http\Controllers\ProjectController::class,'detail'])->middleware('auth');
Route::get('/project/edit/{project:id}',[\App\Http\Controllers\ProjectController::class,'edit'])->middleware('auth');
Route::get('/project/{project:id}/discipline/{discipline}',[\App\Http\Controllers\ProjectController::class,'detail'])->middleware('auth');
//Route::get('/cost-estimate/project/detail',[\App\Http\Controllers\CostEstimateController::class,'detail'])->middleware('auth');
/**
 * Deprecated
 */
//Route::get('/getExistingWorkItemByWbs',[\App\Http\Controllers\EstimateAllDisciplineController::class,'setExistingWorkItemByWbs'])->middleware('auth');

Route::get('/project/{project:id}/wbs/create',[\App\Http\Controllers\WorkBreakdownStructureController::class,'create'])->middleware('auth');
Route::get('/project/{project:id}/wbs/edit',[\App\Http\Controllers\WorkBreakdownStructureController::class,'edit'])->middleware('auth');
Route::post('/project/{project:id}/wbs/store',[\App\Http\Controllers\WorkBreakdownStructureController::class,'store'])->middleware('auth');
Route::post('/project/{project:id}/wbs/update',[\App\Http\Controllers\WorkBreakdownStructureController::class,'update'])->middleware('auth');

Route::get('/project/{project:id}/estimate-discipline/create',[\App\Http\Controllers\EstimateAllDisciplineController::class,'create'])->middleware('auth')->can('create',App\Models\EstimateAllDiscipline::class);
Route::get('/project/{project:id}/estimate-discipline/create/{discipline}',[\App\Http\Controllers\EstimateAllDisciplineController::class,'create'])->middleware('auth')->can('create',App\Models\EstimateAllDiscipline::class);
Route::post('/project/{project:id}/estimate-discipline/store',[\App\Http\Controllers\EstimateAllDisciplineController::class,'update'])->middleware('auth');
Route::post('/project/{project:id}/estimate-discipline/update',[\App\Http\Controllers\EstimateAllDisciplineController::class,'update'])->middleware('auth');
Route::post('/project/update-status/{project:id}/',[\App\Http\Controllers\ProjectController::class,'updateStatus'])->middleware('auth');

Route::post('/workElement/{project:id}',[\App\Http\Controllers\WorkElementController::class,'store'])->middleware('auth');
Route::get('/getWorkItems',[\App\Http\Controllers\WorkItemController::class,'setWorkItems'])->name('getWorkItem')->middleware('auth');
Route::get('/getWorkElement',[\App\Http\Controllers\WorkElementController::class,'setWorkElements'])->name('getWorkElement');
Route::get('/getItemAdditional/{type}',[\App\Http\Controllers\EstimateAllDisciplineController::class,'getItemAdditional'])->name('getItemAdditional');
Route::get('/getWbsLevel3',[\App\Http\Controllers\WorkBreakdownStructureController::class,'getWbsLevel3'])->middleware('auth');

Route::get('/work-item/',[\App\Http\Controllers\WorkItemController::class,'index'])->middleware('auth');
Route::get('/work-item/create',[\App\Http\Controllers\WorkItemController::class,'create'])->middleware('auth');
Route::get('/work-item/edit/{workItem:id}',[\App\Http\Controllers\WorkItemController::class,'edit'])->middleware('auth');
Route::post('/work-item/',[\App\Http\Controllers\WorkItemController::class,'store'])->middleware('auth');
Route::put('/work-item/{workItem:id}',[\App\Http\Controllers\WorkItemController::class,'update'])->middleware('auth');
Route::get('/work-item/{workItem:id}/man-power',[\App\Http\Controllers\WorkItemController::class,'createManPower'])->middleware('auth');
Route::get('/work-item/{workItem:id}/man-power/edit',[\App\Http\Controllers\WorkItemController::class,'editManPower'])->middleware('auth');
Route::post('/work-item/{workItem:id}/man-power',[\App\Http\Controllers\WorkItemController::class,'storeManPower'])->middleware('auth');
Route::post('/work-item/{workItem:id}/man-power/update',[\App\Http\Controllers\WorkItemController::class,'updateManPower'])->middleware('auth');
Route::get('/work-item/{workItem:id}',[\App\Http\Controllers\WorkItemController::class,'show'])->middleware('auth');
Route::get('/work-item/{workItem:id}/tools-equipment',[\App\Http\Controllers\WorkItemController::class,'createToolsEquipment'])->middleware('auth');
Route::get('/work-item/{workItem:id}/tools-equipment/edit',[\App\Http\Controllers\WorkItemController::class,'editToolsEquipment'])->middleware('auth');
Route::post('/work-item/{workItem:id}/tools-equipment',[\App\Http\Controllers\WorkItemController::class,'storeToolsEquipment'])->middleware('auth');
Route::post('/work-item/{workItem:id}/tools-equipment/update',[\App\Http\Controllers\WorkItemController::class,'updateToolsEquipment'])->middleware('auth');
Route::get('/work-item/{workItem:id}/material',[\App\Http\Controllers\WorkItemController::class,'createMaterial'])->middleware('auth');
Route::get('/work-item/{workItem:id}/material/edit',[\App\Http\Controllers\WorkItemController::class,'editMaterial'])->middleware('auth');
Route::post('/work-item/{workItem:id}/material',[\App\Http\Controllers\WorkItemController::class,'storeMaterial'])->middleware('auth');
Route::post('/work-item/{workItem:id}/material/update',[\App\Http\Controllers\WorkItemController::class,'updateMaterial'])->middleware('auth');

Route::get('/man-power/',[\App\Http\Controllers\ManPowerController::class,'index'])->middleware('auth');
Route::get('/man-power/create',[\App\Http\Controllers\ManPowerController::class,'create'])->middleware('auth');
Route::post('/man-power/',[\App\Http\Controllers\ManPowerController::class,'store'])->middleware('auth');
Route::put('/man-power/{manPower:id}',[\App\Http\Controllers\ManPowerController::class,'update'])->middleware('auth');
Route::get('/man-power/{manPower:id}',[\App\Http\Controllers\ManPowerController::class,'detail'])->middleware('auth');
Route::delete('/man-power/{manPower:id}',[\App\Http\Controllers\ManPowerController::class,'delete'])->middleware('auth');

Route::get('/tool-equipment',[\App\Http\Controllers\EquipmentToolsController::class,'index'])->middleware('auth');
Route::get('/tool-equipment/create',[\App\Http\Controllers\EquipmentToolsController::class,'create'])->middleware('auth');
Route::post('/tool-equipment/',[\App\Http\Controllers\EquipmentToolsController::class,'store'])->middleware('auth');
Route::put('/tool-equipment/{equipmentTools:id}',[\App\Http\Controllers\EquipmentToolsController::class,'update'])->middleware('auth');
Route::get('/tool-equipment/{equipmentTools:id}',[\App\Http\Controllers\EquipmentToolsController::class,'show'])->middleware('auth');
Route::delete('/tool-equipment/{equipmentTools:id}',[\App\Http\Controllers\EquipmentToolsController::class,'destroy'])->middleware('auth');

Route::get('/tool-equipment-category',[\App\Http\Controllers\EquipmentToolsCategoryController::class,'index'])->middleware('auth');
Route::get('/tool-equipment-category/create',[\App\Http\Controllers\EquipmentToolsCategoryController::class,'create'])->middleware('auth');
Route::post('/tool-equipment-category/',[\App\Http\Controllers\EquipmentToolsCategoryController::class,'store'])->middleware('auth');
Route::put('/tool-equipment-category/{equipmentToolsCategory:id}',[\App\Http\Controllers\EquipmentToolsCategoryController::class,'update'])->middleware('auth');
Route::get('/tool-equipment-category/{equipmentToolsCategory:id}',[\App\Http\Controllers\EquipmentToolsCategoryController::class,'show'])->middleware('auth');
Route::delete('/tool-equipment-category/{equipmentToolsCategory:id}',[\App\Http\Controllers\EquipmentToolsCategoryController::class,'destroy'])->middleware('auth');

Route::get('/material',[\App\Http\Controllers\MaterialController::class,'index'])->middleware('auth');
Route::get('/material/create',[\App\Http\Controllers\MaterialController::class,'create'])->middleware('auth');
Route::post('/material/',[\App\Http\Controllers\MaterialController::class,'store'])->middleware('auth');
Route::put('/material/{material:id}',[\App\Http\Controllers\MaterialController::class,'update'])->middleware('auth');
Route::get('/material/{material:id}',[\App\Http\Controllers\MaterialController::class,'show'])->middleware('auth');
Route::delete('/material/{material:id}',[\App\Http\Controllers\MaterialController::class,'destroy'])->middleware('auth');

Route::get('/material-category',[\App\Http\Controllers\MaterialCategoryController::class,'index'])->middleware('auth');
Route::get('/material-category/create',[\App\Http\Controllers\MaterialCategoryController::class,'create'])->middleware('auth');
Route::post('/material-category/',[\App\Http\Controllers\MaterialCategoryController::class,'store'])->middleware('auth');
Route::put('/material-category/{materialCategory:id}',[\App\Http\Controllers\MaterialCategoryController::class,'update'])->middleware('auth');
Route::get('/material-category/{materialCategory:id}',[\App\Http\Controllers\MaterialCategoryController::class,'show'])->middleware('auth');
Route::delete('/material-category/{materialCategory:id}',[\App\Http\Controllers\MaterialCategoryController::class,'destroy'])->middleware('auth');

Route::get('/work-breakdown-structure',[\App\Http\Controllers\settingWbsController::class,'index'])->middleware('auth');
Route::get('/work-breakdown-structure/create',[\App\Http\Controllers\settingWbsController::class,'create'])->middleware('auth');
Route::get('/work-breakdown-structure/{id}/work-element/create',[\App\Http\Controllers\settingWbsController::class,'createWorkElement'])->middleware('auth');
Route::get('/work-breakdown-structure/{id}',[\App\Http\Controllers\settingWbsController::class,'edit'])->middleware('auth');
Route::get('/work-breakdown-structure/work-element/{id}',[\App\Http\Controllers\settingWbsController::class,'editWorkElement'])->middleware('auth');
Route::put('/work-breakdown-structure/{id}',[\App\Http\Controllers\settingWbsController::class,'update'])->middleware('auth');
Route::put('/work-breakdown-structure/work-element/{id}',[\App\Http\Controllers\settingWbsController::class,'updateWorkElement'])->middleware('auth');
Route::post('/work-breakdown-structure/work-element',[\App\Http\Controllers\settingWbsController::class,'storeWorkElement'])->middleware('auth');
Route::post('/work-breakdown-structure/',[\App\Http\Controllers\settingWbsController::class,'store'])->middleware('auth');
Route::delete('/work-breakdown-structure/{id}',[\App\Http\Controllers\settingWbsController::class,'delete'])->middleware('auth');
Route::delete('/work-breakdown-structure/work-element/{id}',[\App\Http\Controllers\settingWbsController::class,'deleteWorkElement'])->middleware('auth');

Route::get('/user',[\App\Http\Controllers\UserController::class,'index'])->middleware('auth');
Route::get('/user/create',[\App\Http\Controllers\UserController::class,'create'])->middleware('auth')->can('create',User::class);
Route::post('/user',[\App\Http\Controllers\UserController::class,'store'])->middleware('auth')->can('create',User::class);
Route::get('/user/{user:id}',[\App\Http\Controllers\UserController::class,'edit'])->middleware('auth')->can('viewAny',User::class);
Route::put('/user/{user:id}',[\App\Http\Controllers\UserController::class,'update'])->middleware('auth');

/**
 * Request by AJAX
 */
Route::get('/getManPower',[\App\Http\Controllers\ManPowerController::class,'getManPower'])->name('getManPower')->middleware('auth');
Route::get('/getNumChild/{workItem:id}',[\App\Http\Controllers\WorkItemController::class,'getNumChild'])->name('getNumChild')->middleware('auth');
Route::get('/getMaterial',[\App\Http\Controllers\MaterialController::class,'getMaterial'])->name('getMaterial')->middleware('auth');
Route::get('/getToolsEquipment',[\App\Http\Controllers\EquipmentToolsController::class,'getToolsEquipment'])->name('getToolsEquipment')->middleware('auth');
Route::get('/getUserEmployee',[\App\Http\Controllers\UserController::class,'getUserEmployee'])->name('getUserEmployee')->middleware('auth');
Route::get('/checkProjectNo',[\App\Http\Controllers\ProjectController::class,'checkDuplicateProjectNo'])->name('checkDuplicateProjectNo')->middleware('auth');
Route::get('/dumpingRole',[\App\Http\Controllers\RoleController::class,'dumpingData'])->name('dumpingData')->middleware('auth');
Route::get('/getDetailWorkItem',[\App\Http\Controllers\WorkItemController::class,'getDetail'])->name('dumpingData')->middleware('auth');
Route::get('/cost-estimate-summary/export/{project:id}',[\App\Http\Controllers\ProjectController::class,'export'])->middleware('auth');
Route::get('/getDisciplineList',[\App\Http\Controllers\WorkBreakdownStructureController::class,'getDisciplineList'])->middleware('auth');
Route::get('/getWorkElementList',[\App\Http\Controllers\WorkBreakdownStructureController::class,'getWorkElementList'])->middleware('auth');


