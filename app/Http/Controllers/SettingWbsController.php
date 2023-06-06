<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\WorkBreakdownStructure;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class SettingWbsController extends Controller
{
    public function index(){
        if(auth()->user()->cannot('viewAny',WorkBreakdownStructure::class)){
            return view('not_authorized');
        }
        $wbs = WorkBreakdownStructure::with(['parent','children'])->filter(request(['q']))->where('level',2)->paginate(20)->withQueryString();
        return view('setting_work_breakdown_structure.index',[
            'wbs' => $wbs,
            'isWorkElement' => false
        ]);
    }

    public function create(){
        if(auth()->user()->cannot('create',WorkBreakdownStructure::class)){
            return view('not_authorized');
        }
        return view('setting_work_breakdown_structure.create',[
            'isWorkElement' => false
        ]);
    }

    public function createWorkElement(Request $request){
        if(auth()->user()->cannot('create',WorkBreakdownStructure::class)){
            return view('not_authorized');
        }
        $wbs = WorkBreakdownStructure::with('parent')->where('id',$request->id)->first();
        return view('setting_work_breakdown_structure.create',[
            'isWorkElement' => true,
            'discipline' => $wbs,
        ]);
    }

    public function edit(Request $request){
        if(auth()->user()->cannot('viewAny',WorkBreakdownStructure::class)){
            return view('not_authorized');
        }
        $wbs = WorkBreakdownStructure::with('children')->where('id',$request->id)->first();
        $discipline = WorkBreakdownStructure::with('parent')->where('level',2)->get();
        return view('setting_work_breakdown_structure.edit',[
            'wbs' => $wbs,
            'discipline' => $discipline,
            'isWorkElement' => false
        ]);
    }
    public function editWorkElement(Request $request){
        if(auth()->user()->cannot('update',WorkBreakdownStructure::class)){
            return view('not_authorized');
        }
        $wbs = WorkBreakdownStructure::with('parent')->where('id',$request->id)->first();
        return view('setting_work_breakdown_structure.edit',[
            'discipline' => $wbs?->parent,
            'wbs' => $wbs,
            'isWorkElement' => true
        ]);
    }


    public function store(Request $request){
        if(auth()->user()->cannot('create',WorkBreakdownStructure::class)){
           abort(403);
        }
        DB::beginTransaction();
        try {
            $wbs = new WorkBreakdownStructure();
            $wbs->title = $request->title;
            $wbs->level = Setting::LEVEL_DISCIPLINE;
            $wbs->save();
            DB::commit();

            $this->message('Data was successfully saved','success','fa fa-check','Success');
            return redirect('/work-breakdown-structure');
        } catch (Exception $e){
            DB::rollback();
            return redirect('work-breakdown-structure/create/')->withErrors($e->getMessage());
        }
    }

    public function storeWorkElement(Request $request){
        if(auth()->user()->cannot('create',WorkBreakdownStructure::class)){
            abort(403);
        }
        DB::beginTransaction();
        try {
            $wbs = new WorkBreakdownStructure();
            $wbs->title = $request->title;
            $wbs->level = Setting::LEVEL_WORK_ELEMENT;
            $wbs->parent_id = $request->parent_id;
            $wbs->save();
            DB::commit();

            $this->message('Data was successfully saved','success','fa fa-check','Success');
            return redirect('/work-breakdown-structure/'.$request->parent_id);
        } catch (Exception $e){
            DB::rollback();
            return redirect('work-breakdown-structure/'.$request->parent_id.'/work-element/create')->withErrors($e->getMessage());
        }
    }

    public function update(Request $request, WorkBreakdownStructure $wbs){
        if(auth()->user()->cannot('update',WorkBreakdownStructure::class)){
            abort(403);
        }
        DB::beginTransaction();
        try{
            $wbs = WorkBreakdownStructure::where('id',$request->id)->lockForUpdate()->first();
            $wbs->title = $request->title;
            $wbs->save();

            DB::commit();$this->message('Data was successfully saved','success','fa fa-check','Success');
            return redirect('/work-breakdown-structure');
        } catch (Exception $e){
            DB::rollback();
            return redirect('work-breakdown-structure/edit/'.$request->id)->withErrors($e->getMessage());
        }
    }

    public function updateWorkElement(Request $request, WorkBreakdownStructure $wbs){
        if(auth()->user()->cannot('update',WorkBreakdownStructure::class)){
            abort(403);
        }
        DB::beginTransaction();
        try{
            $wbs = WorkBreakdownStructure::where('id',$request->id)->lockForUpdate()->first();
            $wbs->title = $request->title;
            $wbs->save();
            DB::commit();

            $this->message('Data was successfully saved','success','fa fa-check','Success');
            return redirect('/work-breakdown-structure/'. $wbs?->parent?->id);
        } catch (Exception $e){
            DB::rollback();
            return redirect('work-breakdown-structure/work-element/'.$request->id)->withErrors($e->getMessage());
        }
    }

    public function delete(Request $request){
        if(auth()->user()->cannot('delete',WorkBreakdownStructure::class)){
            return response()->json([
                'status' => 200,
                'message' => "You're not authorized"
            ]);
        }
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
        if(auth()->user()->cannot('delete',WorkBreakdownStructure::class)){
            return response()->json([
                'status' => 200,
                'message' => "You're not authorized"
            ]);
        }
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

    public function message($message, $type, $icon, $status){
        Session::flash('message', $message);
        Session::flash('type', $type);
        Session::flash('icon', $icon);
        Session::flash('status', $status);
    }
}
