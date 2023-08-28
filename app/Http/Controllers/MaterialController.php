<?php

namespace App\Http\Controllers;

use App\Models\ManPower;
use App\Models\Material;
use App\Models\MaterialCategory;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;

class MaterialController extends Controller
{
    public function getAllMaterial(Request $request){
        $material = Material::with('workItems')->when($request->q, function ($q) use ($request){
            return $q->where('tool_equipment_description',$request->q);
        })->get();

        return $material;
    }

    public function index(Request $request){
        if(auth()->user()->cannot('viewAny',Material::class)){
            return view('not_authorized');
        }

        $order = $request->order;
        $sort =  $request->sort;
        $materialCategory = MaterialCategory::select('id','description','code')->get();

        $material = Material::with('materialsCategory')->filter(request(['q','category','status']))
            ->when(isset($request->sort), function($query) use ($request,$order,$sort) {
                return $query->when($request->order == 'category', function ($qq) use ($request, $order, $sort) {
                    return $qq->whereHas('materialsCategory', function ($relation) use ($sort) {
                        $relation->orderBy('description', $sort);
                    });
                })->when($request->order != 'category', function ($qq) use ($request, $order, $sort) {
                    return $qq->orderBy($order, $sort);
                });
        })->when(!auth()->user()->isMaterialReviewerRole(), function($query){
            return $query->where(function($subQuery){
                return $subQuery->where('status',Material::REVIEWED)
                    ->orWhere('created_by', auth()->user()->id);
            });
        })->when(!isset($request->sort), function($query) use ($request,$order) {
            return $query->orderBy('code', 'ASC');
        })->paginate(20)->withQueryString();
        return view('material.index',[
            'material_category' => $materialCategory,
            'material' => $material
        ]);
    }

    public function create(){
        if(auth()->user()->cannot('create',Material::class)){
            return view('not_authorized');
        }

        $materialCategory = MaterialCategory::select('id','description','code')->get();
        return view('material.create',[
            'material_category' => $materialCategory
        ]);
    }

    public function store(Request $request){
        if(auth()->user()->cannot('create',Material::class)){
            abort(403);
        }

        $this->validate($request,[
            'code' => 'required|unique:materials',
            'tool_equipment_description' => 'required',
            'category_id' => 'required',
            'quantity' => 'required',
            'unit' => 'required',
            'rate' => 'required',
        ]);

        try {
            DB::beginTransaction();
            $material = new Material([
                'code' => $request->code,
                'tool_equipment_description'=> $request->tool_equipment_description,
                'category_id' => $request->category_id,
                'quantity' => $request->quantity,
                'unit' => $request->unit,
                'rate' => $this->convertToDecimal($request->rate),
                'stock_code' => $request->stock_code,
                'remark' => $request->remark,
                'ref_material_number' => $request->ref_material_number,
                'status' => Material::DRAFT,
                'created_by' => auth()->user()->id
            ]);
            $material->save();
            DB::commit();
            $this->message('Data was successfully saved','success','fa fa-check','Success');
            return redirect('material');
        } catch (Exception $e) {
            DB::rollback();
            return redirect('material/create')->withErrors($e->getMessage());
        }
    }

    public function show(Material $material){
        if(auth()->user()->cannot('viewAny',Material::class)){
            return view('not_authorized');
        }
        $materialCategory = MaterialCategory::select('id','description','code')->get();
        return view('material.detail',[
            'material' => $material,
            'material_category' => $materialCategory
        ]);
    }

    public function update(Request $request, Material $material){
        if(auth()->user()->cannot('update',Material::class)){
            abort(403);
        }

        $this->validate($request,[
            Rule::unique('materials')->ignore($material->id),
            'tool_equipment_description' => 'required',
            'category_id' => 'required',
            'quantity' => 'required',
            'unit' => 'required',
            'rate' => 'required',
        ]);

        try {
            DB::begintransaction();
            $material->code = $request->code;
            $material->tool_equipment_description = $request->tool_equipment_description;
            $material->category_id = $request->category_id;
            $material->quantity = $request->quantity;
            $material->unit = $request->unit;
            $material->rate = $this->convertToDecimal($request->rate);
            $material->stock_code = $request->stock_code;
            $material->remark = $request->remark;
            $material->ref_material_number = $request->ref_material_number;
            $material->save();
            DB::commit();
            $this->message('Data was successfully saved','success','fa fa-check','Success');
            return redirect('material/'.$material->id);

        } catch (Exception $e){
            DB::rollback();
            return redirect('material/'.$material->id)->withErrors($e->getMessage());
        }
    }

    public function destroy(Material $material)
    {
        if(auth()->user()->cannot('create',Material::class)){
            return response()->json([
                'status' => 403,
                'message' => "You're not authorized"
            ]);
        }

        try{
            $material->delete();
            return response()->json([
                'status' => 200,
                'message' => 'Item Successfully Deleted'
            ]);
        } catch (Exception $e){
            return response()->json([
                'status' => 500,
                'message' => $e->getMessage()
            ]);
        }
    }

    public function getMaterial(Request $request){
        $response = array();
        $data = Material::select('id','tool_equipment_description','code','rate')
            ->when(!auth()->user()->isMaterialReviewerRole(), function($query){
                return $query->where(function($subQuery){
                    return $subQuery->where('status',Material::REVIEWED)
                        ->orWhere('created_by',auth()->user()->id);
                });
            })->where('tool_equipment_description','like','%'.$request->q.'%')
            ->orwhere('code','like','%'.$request->q.'%')->get();
        foreach($data as $v){
            $response[] = array(
                "text" => "[".$v->code . "] - " . $v->tool_equipment_description,
                "id" => $v->id,
                "rate" => $v->rate
            );
        }

        return response()->json($response);
    }

    public function convertToDecimal($val){
        if(!$val) return '';
        $value = str_replace('.','',$val);
        $value = str_replace(',','.',$value);
        return $value;
    }

    public function updateList(Request $request){
        $ids = (string) $request->ids;
        DB::beginTransaction();
        $ids = explode(',',$ids);
        try {
            $items = Material::whereIn('id',$ids)->get();

            $items->each(function ($item){
                $item->update(['status' => Material::REVIEWED]);
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
