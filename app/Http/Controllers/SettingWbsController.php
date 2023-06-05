<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\WorkBreakdownStructure;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SettingWbsController extends Controller
{
    public function index(){
        $wbs = WorkBreakdownStructure::with(['parent','children'])->filter(request(['q']))->where('level',2)->paginate(20)->withQueryString();
        return view('setting_work_breakdown_structure.index',[
            'wbs' => $wbs,
            'isWorkElement' => false
        ]);
    }

    public function create(){
        return view('setting_work_breakdown_structure.create',[
            'isWorkElement' => false
        ]);
    }

    public function createWorkElement(Request $request){
        $wbs = WorkBreakdownStructure::with('parent')->where('id',$request->id)->first();
        return view('setting_work_breakdown_structure.create',[
            'isWorkElement' => true,
            'discipline' => $wbs,
        ]);
    }

    public function edit(Request $request){
        $wbs = WorkBreakdownStructure::with('children')->where('id',$request->id)->first();
        $discipline = WorkBreakdownStructure::with('parent')->where('level',2)->get();
        return view('setting_work_breakdown_structure.edit',[
            'wbs' => $wbs,
            'discipline' => $discipline,
            'isWorkElement' => false
        ]);
    }
    public function editWorkElement(Request $request){
        $wbs = WorkBreakdownStructure::with('parent')->where('id',$request->id)->first();
        return view('setting_work_breakdown_structure.edit',[
            'discipline' => $wbs?->parent,
            'wbs' => $wbs,
            'isWorkElement' => true
        ]);
    }


    public function store(Request $request){
        DB::beginTransaction();
        try {
            $wbs = new WorkBreakdownStructure();
            $wbs->title = $request->title;
            $wbs->level = Setting::LEVEL_DISCIPLINE;
            $wbs->save();
            DB::commit();
            return redirect('/work-breakdown-structure');
        } catch (Exception $e){
            DB::rollback();
            return redirect('work-breakdown-structure/create/')->withErrors($e->getMessage());
        }
    }

    public function storeWorkElement(Request $request){
        DB::beginTransaction();
        try {
            $wbs = new WorkBreakdownStructure();
            $wbs->title = $request->title;
            $wbs->level = Setting::LEVEL_WORK_ELEMENT;
            $wbs->parent_id = $request->parent_id;
            $wbs->save();
            DB::commit();
            return redirect('/work-breakdown-structure/'.$request->parent_id);
        } catch (Exception $e){
            DB::rollback();
            return redirect('work-breakdown-structure/'.$request->parent_id.'/work-element/create')->withErrors($e->getMessage());
        }
    }

    public function update(Request $request, WorkBreakdownStructure $wbs){
        DB::beginTransaction();
        try{
            $wbs = WorkBreakdownStructure::where('id',$request->id)->lockForUpdate()->first();
            $wbs->title = $request->title;
            $wbs->save();
            DB::commit();
            return redirect('/work-breakdown-structure');
        } catch (Exception $e){
            DB::rollback();
            return redirect('work-breakdown-structure/edit/'.$request->id)->withErrors($e->getMessage());
        }
    }

    public function updateWorkElement(Request $request, WorkBreakdownStructure $wbs){
        DB::beginTransaction();
        try{
            $wbs = WorkBreakdownStructure::where('id',$request->id)->lockForUpdate()->first();
            $wbs->title = $request->title;
            $wbs->save();
            DB::commit();
            return redirect('/work-breakdown-structure/'. $wbs?->parent?->id);
        } catch (Exception $e){
            DB::rollback();
            return redirect('work-breakdown-structure/work-element/'.$request->id)->withErrors($e->getMessage());
        }
    }

    public function delete(Request $request){
        DB::beginTransaction();
        try{
            $wbs = WorkBreakdownStructure::with('children')->where('id',$request->id)->lockForUpdate()->first();
            $wbs->children()->delete();
            $wbs->delete();
            DB::commit();
            return response()->json([
                'status' => 200,
                'message' => 'Data Deleted Successfully'
            ]);
        } catch (Exception $e){
            DB::rollback();
            return response()->json([
                'status' => 200,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function deleteWorkElement(Request $request){
        DB::beginTransaction();
        try{
            $wbs = WorkBreakdownStructure::where('id',$request->id)->lockForUpdate()->first();
            $wbs->delete();
            DB::commit();
            return response()->json([
                'status' => 200,
                'message' => 'Data Deleted Successfully'
            ]);
        } catch (Exception $e){
            DB::rollback();
            return response()->json([
                'status' => 200,
                'message' => $e->getMessage()
            ]);
        }
    }
}
