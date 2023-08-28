<?php

namespace App\Http\Controllers;

use App\Models\EquipmentTools;
use App\Models\EquipmentToolsCategory;
use App\Models\Tools;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;

class EquipmentToolsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index(Request $request){
        if(auth()->user()->cannot('viewAny',EquipmentTools::class)){
            return view('not_authorized');
        }

        $order = $request->order;
        $sort =  $request->sort;
        $equipmentTools = EquipmentTools::with('equipmentToolsCategory')->filter(request(['q','category','status']))
            ->when(isset($request->sort), function($query) use ($request,$order,$sort){
                return $query->when($request->order == 'category', function($qq) use ($request,$order,$sort){
                    return $qq->whereHas('equipmentToolsCategory',function($relation) use ($sort){
                        $relation->orderBy('description',$sort);
                    });
                })->when($request->order != 'category', function($qq) use ($request,$order, $sort){
                    return $qq->orderBy($order,$sort);
                });
            })->when(!auth()->user()->isToolsEquipmentReviewerRole(), function($query){
                return $query->where(function($q){
                   return $q->where('status',EquipmentTools::REVIEWED)->orWhere('created_by', auth()->user()->id);
                });
            })->when(!isset($request->sort), function($query) use ($request,$order) {
                return $query->orderBy('equipment_tools.code', 'ASC');
            })->paginate(20)->withQueryString();
        $equipmentToolsCategory = EquipmentToolsCategory::select('id','description')->get();

        return view('equipment_tool.index',[
            'equipment_tools' => $equipmentTools,
            'equipment_tools_category' => $equipmentToolsCategory
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function create()
    {
        if(auth()->user()->cannot('create',EquipmentTools::class)){
            return view('not_authorized');
        }
        $equipmentToolsCategory = EquipmentToolsCategory::select('id','description','code')->get();

        return view('equipment_tool.create',[
            'equipment_tools_category' => $equipmentToolsCategory
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(Request $request)
    {
        if(auth()->user()->cannot('create',EquipmentTools::class)){
            abort(403);
        }

        $this->validate($request,[
            'code' => 'required|unique:equipment_tools',
            'description' => 'required',
            'category' => 'required',
            'quantity' => 'required',
            'unit' => 'required',
            'local_rate' => 'required',
            'national_rate' => 'required',
        ]);

        try {
            DB::beginTransaction();
            $equipmentTool = new EquipmentTools([
                'code' => $request->code,
                'description'=> $request->description,
                'category_id' => $request->category,
                'quantity' => $request->quantity,
                'unit' => $request->unit,
                'local_rate' => $this->convertToDecimal($request->local_rate),
                'national_rate' => $this->convertToDecimal($request->national_rate),
                'remark' => $request->remark,
                'status' => EquipmentTools::DRAFT,
                'created_by' => auth()->user()->id,
            ]);
            $equipmentTool->save();
            DB::commit();
            $this->message('Data was successfully saved','success','fa fa-check','Success');
            return redirect('tool-equipment');

        } catch (Exception $e) {
            DB::rollback();
            return redirect('tool-equipment/create')->withErrors($e->getMessage());
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\EquipmentTools  $equipmentTools
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function show(EquipmentTools $equipmentTools)
    {
        if(auth()->user()->cannot('viewAny',EquipmentTools::class)){
            return view('not_authorized');
        }

        $equipmentToolsCategory = EquipmentToolsCategory::select('id','description','code')->get();

        return view('equipment_tool.edit',[
            'equipment_tools' => $equipmentTools,
            'equipment_tools_category' => $equipmentToolsCategory
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\EquipmentTools  $equipmentTools
     * @return Response
     */
    public function edit(EquipmentTools $equipmentTools)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\EquipmentTools  $equipmentTools
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function update(Request $request, EquipmentTools $equipmentTools)
    {
        if(auth()->user()->cannot('update',EquipmentTools::class)){
            abort(403);
        }

        $this->validate($request,[
            Rule::unique('equipment_tools')->ignore($equipmentTools->id),
            'description' => 'required',
            'category' => 'required',
            'quantity' => 'required',
            'unit' => 'required',
            'local_rate' => 'required',
            'national_rate' => 'required',
        ]);

        try {
            DB::begintransaction();
            $equipmentTools->code = $request->code;
            $equipmentTools->description = $request->description;
            $equipmentTools->category_id = $request->category;
            $equipmentTools->quantity = $request->quantity;
            $equipmentTools->unit = $request->unit;
            $equipmentTools->local_rate = $this->convertToDecimal($request->local_rate);
            $equipmentTools->national_rate = $this->convertToDecimal($request->national_rate);
            $equipmentTools->remark = $request->remark;
            $equipmentTools->save();
            DB::commit();
            $this->message('Data was successfully saved','success','fa fa-check','Success');
            return redirect('tool-equipment/'.$equipmentTools->id);
        } catch (Exception $e){
            DB::rollback();
            return redirect('tool-equipment/'.$equipmentTools->id)->withErrors($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\EquipmentTools  $equipmentTools
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(EquipmentTools $equipmentTools)
    {
        if(auth()->user()->cannot('delete',EquipmentTools::class)){
            return response()->json([
                'status' => 403,
                'message' => "You're not authorized"
            ]);
        }

        try{
            DB::beginTransaction();
            $equipmentTools->delete();
            DB::commit();
            return response()->json([
                'status' => 200,
                'message' => 'Item Successfully Deleted'
            ]);
        } catch (Exception $e){
            DB::rollback();
            return response()->json([
                'status' => 500,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function convertToDecimal($val){
        if(!$val) return '';
        $value = str_replace('.','',$val);
        $value = str_replace(',','.',$value);
        return $value;
    }

    public function getToolsEquipment(Request $request){
        $response = array();
        $data = EquipmentTools::select('id','description','code','local_rate')
            ->when(!auth()->user()->isToolsEquipmentReviewerRole(), function($query){
                return $query->where(function($q){
                    return $q->where('status', EquipmentTools::REVIEWED)
                        ->orWhere('created_by', auth()->user()->id);
                });
            })->where('description','like','%'.$request->q.'%')
            ->orwhere('code','like','%'.$request->q.'%')->get();
        foreach($data as $v){
            $response[] = array(
                "text" => "[".$v->code . "] - " . $v->description,
                "id" => $v->id,
                "rate" => $v->local_rate
            );
        }

        return response()->json($response);
    }

    public function updateList(Request $request){
        $ids = (string) $request->ids;
        DB::beginTransaction();
        $ids = explode(',',$ids);
        try {
            $items = EquipmentTools::whereIn('id',$ids)->get();

            $items->each(function ($item){
                $item->update(['status' => EquipmentTools::REVIEWED]);
            });
            DB::commit();
            return response()->json([
                'message' => 'Data successfully update',
                'status' => 200
            ]);
        } catch (Exception $e){
            DB::rollback();
            return response()->json([
                'message' => $e->getMessage(),
                'status' => 500
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
