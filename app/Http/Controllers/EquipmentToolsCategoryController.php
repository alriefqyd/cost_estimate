<?php

namespace App\Http\Controllers;

use App\Models\EquipmentTools;
use App\Models\EquipmentToolsCategory;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;

class EquipmentToolsCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        if(auth()->user()->cannot('viewAny',EquipmentToolsCategory::class)){
            return view('not_authorized');
        }

        $equipmentToolsCategory = EquipmentToolsCategory::filter(request(['q']))->orderBy('created_at','DESC')->paginate(20)->withQueryString();

        return view('equipment_tool_category.index',[
            'equipment_tools_category' => $equipmentToolsCategory
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(auth()->user()->cannot('create',EquipmentTools::class)){
            return view('not_authorized');
        }

        return view('equipment_tool_category.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(auth()->user()->cannot('create',EquipmentTools::class)){
            return view('not_authorized');
        }

        $this->validate($request,[
            'code' => 'required|unique:equipment_tools_categorys',
            'description' => 'required',
        ]);

        try {
            DB::beginTransaction();
            $equipmentTool = new EquipmentToolsCategory([
                'code' => $request->code,
                'description'=> $request->description,
            ]);
            $equipmentTool->save();
            DB::commit();
            $this->message('Data was successfully saved','success','fa fa-check','Success');
            return redirect('tool-equipment-category');

        } catch (Exception $e) {
            DB::rollback();
            return redirect('tool-equipment-category/create')->withErrors($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function show(EquipmentToolsCategory $equipmentToolsCategory)
    {
        if(auth()->user()->cannot('viewAny',EquipmentTools::class)){
            return view('not_authorized');
        }
        return view('equipment_tool_category.edit',[
            'equipment_tools_category' => $equipmentToolsCategory
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, EquipmentToolsCategory $equipmentToolsCategory)
    {
        if(auth()->user()->cannot('update',EquipmentTools::class)){
            return view('not_authorized');
        }
        $this->validate($request,[
            Rule::unique('equipment_tools_categorys')->ignore($equipmentToolsCategory->id),
            'description' => 'required',
        ]);

        try {
            DB::begintransaction();
            $equipmentToolsCategory->code = $request->code;
            $equipmentToolsCategory->description = $request->description;
            $equipmentToolsCategory->save();
            DB::commit();
            $this->message('Data was successfully saved','success','fa fa-check','Success');
            return redirect('tool-equipment-category/'.$equipmentToolsCategory->id);

        } catch (Exception $e){
            DB::rollback();
            return redirect('tool-equipment-category/'.$equipmentToolsCategory->id)->withErrors($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(EquipmentToolsCategory $equipmentToolsCategory)
    {
        try{
            $equipmentToolsCategory->delete();
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

    public function message($message, $type, $icon, $status){
        Session::flash('message', $message);
        Session::flash('type', $type);
        Session::flash('icon', $icon);
        Session::flash('status', $status);
    }
}
