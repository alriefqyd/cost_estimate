<?php

namespace App\Http\Controllers;

use App\Models\Material;
use App\Models\MaterialCategory;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
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
        if(auth()->user()->cannot('viewAny',Material::class)){
            return view('not_authorized');
        }
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
        if(auth()->user()->cannot('create',Material::class)){
            return view('not_authorized');
        }
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
        if(auth()->user()->cannot('create',Material::class)){
            abort(403);
        }
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
            $this->message('Data was successfully saved','success','fa fa-check','Success');
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
        if(auth()->user()->cannot('viewAny',Material::class)){
            return view('not_authorized');
        }
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
        if(auth()->user()->cannot('update',Material::class)){
            abort(403);
        }
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
            $this->message('Data was successfully saved','success','fa fa-check','Success');
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
        if(auth()->user()->cannot('delete',Material::class)){
            return response()->json([
                'status' => 403,
                'message' => "You're not authorized"
            ]);
        }
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

    public function message($message, $type, $icon, $status){
        Session::flash('message', $message);
        Session::flash('type', $type);
        Session::flash('icon', $icon);
        Session::flash('status', $status);
    }
}
