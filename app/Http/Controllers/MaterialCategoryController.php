<?php

namespace App\Http\Controllers;

use App\Models\MaterialCategory;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class MaterialCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\View\View
     */
    public function index()
    {
        $materialCategory = MaterialCategory::filter(request(['q']))->orderBy('created_at','DESC')->paginate(20)->withQueryString();
        return view('material_category.index',[
                'material_category' => $materialCategory
            ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('material_category.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request,[
            'code' => 'required|unique:materials_categorys',
            'description' => 'required',
        ]);

        try {
            DB::beginTransaction();
            $equipmentTool = new MaterialCategory([
                'code' => $request->code,
                'description'=> $request->description,
            ]);
            $equipmentTool->save();
            DB::commit();
            return redirect('material-category');

        } catch (Exception $e) {
            DB::rollback();
            return redirect('material-category/create')->withErrors($e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(MaterialCategory $materialCategory)
    {
        return view('material_category.edit',[
            'material_category' => $materialCategory
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
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, MaterialCategory $materialCategory)
    {
        $this->validate($request,[
            Rule::unique('materials_categorys')->ignore($materialCategory->id),
            'description' => 'required',
        ]);

        try {
            DB::begintransaction();
            $materialCategory->code = $request->code;
            $materialCategory->description = $request->description;
            $materialCategory->save();
            DB::commit();
            return redirect('material-category/'.$materialCategory->id);

        } catch (Exception $e){
            DB::rollback();
            return redirect('material-category/'.$materialCategory->id)->withErrors($e->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(MaterialCategory $materialCategory)
    {
        try{
            $materialCategory->delete();
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
}
