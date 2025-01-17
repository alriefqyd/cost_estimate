<?php

namespace App\Http\Controllers;

use App\Exports\MaterialMasterExport;
use App\Imports\MaterialCategoryImport;
use App\Imports\MaterialImport;
use App\Models\Material;
use App\Models\MaterialCategory;
use App\Models\User;
use App\Services\MaterialServices;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;

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

        $materialServices = new MaterialServices();


        $materialCategory = MaterialCategory::select('id','description','code')->get();
        $material = $materialServices->getMaterial($request, false, null);
        $dataMaterial = $material->paginate(20)->withQueryString();
        $materialDraft = $materialServices->getMaterial($request,true,Material::DRAFT)->count();
        $materialReviewed = $materialServices->getMaterial($request,true,Material::REVIEWED)->count();

        $engineers = User::with('profiles') // Eager load the profile relationship
        ->whereHas('profiles', function ($query) {
            $query->where('position', 'design_civil_engineer')->orwhere('position', 'design_mechanical_engineer')
                ->orwhere('position', 'design_architect_engineer')->orwhere('position', 'design_electrical_engineer')
                ->orwhere('position', 'design_instrument_engineer')->orwhere('position', 'design_it_engineer');
        })->get()->map(function ($user) {
            return [
                'id' => $user->id,
                'full_name' => $user->profiles->full_name, // Access the related profile's full_name
            ];
        })->toArray();

        return view('material.index',[
            'material_category' => $materialCategory,
            'material' => $dataMaterial,
            'materialDraft' => $materialDraft,
            'materialReviewed' => $materialReviewed,
            'engineers' => $engineers
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
            $this->setStatusDraft($material);
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
        if(auth()->user()->cannot('delete',Material::class)){
            return response()->json([
                'status' => 403,
                'message' => "You're not authorized"
            ]);
        }

        try{
            $material->code = "D_" .$material->id ."_". $material->code;
            $material->save();
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
        $data = Material::select('id','tool_equipment_description','code','rate','unit')
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
                "rate" => $v->rate,
                "unit" => $v->unit
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

    public function export(){
        $data = Material::all();
        $materialCategory = MaterialCategory::all();
        try{
            Log::info('Starting Export Material');
            return Excel::download(new MaterialMasterExport($data, $materialCategory), 'Material List.xlsx');
        } catch (Exception $e) {
            Log::info($e->getMessage());
            return response()->json('Import Failed : ' . $e->getMessage());
        }
    }

    public function import(Request $request){
        $file = $request->file('file');
        $file_category = $request->file('file_category');
        if ($request->hasFile('file') && $request->hasFile('file_category')) {
            Log::info('Starting import Materials...');

            try {
                Excel::import(new MaterialCategoryImport, $file_category);
                Excel::import(new MaterialImport, $file);
                Log::info('Import material successful');
                return response()->json(['message' => 'Import Successful']);
            } catch (\Exception $e) {
                DB::rollback();
                Log::error('Import error: ' . $e->getMessage());
                return response()->json(['message' => $e->getMessage()], 500);
            }
        }

        Log::info('No file uploaded');
        return response()->json(['message' => 'No file uploaded'], 400);
    }

    public function message($message, $type, $icon, $status){
        Session::flash('message', $message);
        Session::flash('type', $type);
        Session::flash('icon', $icon);
        Session::flash('status', $status);
    }

    public function importMaterialList($spreadsheet){
        $sheetName = 'Material List';
        $worksheet = $spreadsheet->getSheetByName($sheetName);

        $data = [];
        foreach ($worksheet->getRowIterator() as $row) {
            $rowData = [];
            foreach ($row->getCellIterator() as $cell) {
                $rowData[] = $cell->getValue();
            }
            $data[] = $rowData;
        }

        $codeToSave = [];
        DB::beginTransaction();
        foreach ($data as $row) {
            $uniqueValue = $row[1];
            $user = auth()->user()->id;
            $category = MaterialCategory::where('description', $row[3])->first();
            if(isset($category))
            {
                $dataToUpsert = [
                    'code' => $row[1],
                    'tool_equipment_description' => $row[2] ?? '',
                    'category_id' => $category?->id,
                    'quantity' => $row[4] ?? '',
                    'unit' => $row[5] ?? '',
                    'rate' => $row[6] ?? '',
                    'ref_material_number' => $row[7] ?? '',
                    'remark' => $row[9] ?? '',
                    'status' => 'DRAFT',
                    'created_at' => now(),
                    'updated_at' => now(),
                    'created_by' => $user,
                    'updated_by' => $user,
                    'stock_code' => $row[10] ?? ''
                ];

                $codeToSave[] = $row[1];

                Material::updateOrCreate(
                    ['code' => $uniqueValue],
                    $dataToUpsert + ['created_at' => now(), 'created_by' => $user]
                );
            }
        }

        // Delete records that exist in the database but not in the imported data
        Material::whereNotIn('code', $codeToSave)->delete();

        DB::commit();
    }

    public function importMaterialCategory($spreadsheet){
        $sheetName = 'Materials Category';
        $worksheet = $spreadsheet->getSheetByName($sheetName);

        $data = [];
        foreach ($worksheet->getRowIterator() as $row) {
            $rowData = [];
            foreach ($row->getCellIterator() as $cell) {
                $rowData[] = $cell->getValue();
            }
            $data[] = $rowData;
        }

        $codeToSave = [];
        DB::beginTransaction();
        foreach ($data as $row) {
            $uniqueValue = $row[1];
            if (isset($uniqueValue)) {
                $dataToUpsert = [
                    'code' => $row[1],
                    'description' => $row[2] ?? '',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                $codeToSave[] = $row[1];

                MaterialCategory::updateOrInsert(
                    ['code' => $uniqueValue],
                    $dataToUpsert
                );
            }
        }


        // Delete records that exist in the database but not in the imported data
        $codesToDelete = Material::whereNotIn('code', $codeToSave);
        $codesToDelete->each(function ($record) {
            $record->delete();
        });

        DB::commit();
    }

    public function generateCodeMaterial(Request $request){
        $material = Material::select('code','category_id')->where('category_id', $request->id)
            ->orderBy('code')->get();

        if(count($material) < 1){
            $materialType = MaterialCategory::where('id', $request->id)->first();
            return response()->json([
                'status' => 200,
                'data' => $materialType->code . "." . "01"
            ]);
        }
        if(count($material) < 1){
            return response()->json([
                'status' => 500
            ]);
        }

        $data = $material->filter(function ($item){
            return (strpos($item->code, "A") === false);
        })->pluck('code');


        $suffix = "01";
        $code = $this->getSuffix($material[0]->code)['prefix'];
        foreach ($data as $key => $value){
            $suffixWorkItem = $this->getSuffix($value);
            if($suffix != $suffixWorkItem['suffix']){
                break;
            } else {
                $suffix+=1;
            }
        }

        return response()->json([
            'status' => 200,
            'data' => $code . '.' .str_pad($suffix, 3, '0', STR_PAD_LEFT),
        ]);
    }

    public function getSuffix($value){
        $parts = explode('.', $value);
        $afterDot = isset($parts[1]) ? $parts[1] : ''; // Check if the dot exists in the string
        $beforeDot = isset($parts[0]) ? $parts[0] : ''; // Check if the dot exists in the string
        return [
            'prefix' =>  $beforeDot,
            'suffix' => $afterDot
        ];
    }

    public function setStatusDraft(Material $material){
        if($material->status == Material::REVIEWED){
            $material->status = Material::DRAFT;
        }
    }
}
