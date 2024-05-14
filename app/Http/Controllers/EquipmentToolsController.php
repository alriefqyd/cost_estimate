<?php

namespace App\Http\Controllers;

use App\Exports\EquipmentToolsMasterExport;
use App\Imports\EquipmentToolsImport;
use App\Models\EquipmentTools;
use App\Models\EquipmentToolsCategory;
use App\Models\MaterialCategory;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;

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
        $data = EquipmentTools::select('id','description','code','local_rate','unit')
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
                "rate" => $v->local_rate,
                "unit" => $v->unit
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

    public function export(){
        $data = EquipmentTools::all();
        $dataCategory = EquipmentToolsCategory::all();
        try {
            Log::info('Starting Export Tools Equipment');
            return Excel::download(new EquipmentToolsMasterExport($data, $dataCategory), 'tools-equipment.xlsx');
        } catch (Exception $e) {
            Log::info($e->getMessage());
            return response()->json('Import Failed : ' . $e->getMessage());
        }
    }


    public function import(Request $request){
        if ($request->hasFile('file')) {
            Log::info('Starting import equipment tools...');

            try {
                $file = $request->file('file');
                $spreadsheet = IOFactory::load($file);
                $sheetName = 'Equipment Tools List';
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
                    $uniqueValue = $row['1'];
                    $user = auth()->user()->id;
                    $category = EquipmentToolsCategory::where('description', $row[3])->first();
                    if(isset($category))
                    {
                        $dataToUpsert = [
                            'code' => $row['1'],
                            'description' => $row['2'],
                            'category_id' => $category->id,
                            'quantity' => $row['4'],
                            'unit' => $row['5'],
                            'local_rate' => $row['6'],
                            'national_rate' => $row['7'],
                            'remark' => $row['9'],
                            'status' => 'DRAFT',
                            'created_at' => now(),
                            'updated_at' => now(),
                            'created_by' => $user,
                            'updated_by' => $user
                            // Add more columns as needed
                        ];

                        EquipmentTools::updateOrInsert(
                            ['code' => $uniqueValue],
                            $dataToUpsert
                        );

                        $codeToSave[] = $row[1];
                    }

                }

                // Delete records that exist in the database but not in the imported data
                $codesToDelete = EquipmentTools::whereNotIn('code', $codeToSave);
                $codesToDelete->each(function ($record) {
                    $record->delete();
                });

                DB::commit();

                Log::info('Import equipment tools successful');
                return response()->json(['message' => 'Import Successful']);
            } catch (\Exception $e) {
                DB::rollback();
                Log::error('Import error: ' . $e->getMessage());
                return response()->json(['message' => 'Import failed'], 500);
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
}
