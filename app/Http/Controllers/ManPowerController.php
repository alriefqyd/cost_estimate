<?php

namespace App\Http\Controllers;

use App\Class\ManPowerServices;
use App\Exports\ManPowerExport;
use App\Imports\ManPowerImport;
use App\Models\ManPower;
use App\Models\Setting;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;

class ManPowerController extends Controller
{
    public function index(Request $request){
        if(auth()->user()->cannot('viewAny',ManPower::class)){
            return view('not_authorized');
        }

        $manPowerService = new ManPowerServices();
        $man_power = $manPowerService->getManPower($request, false, null)->paginate(20)->withQueryString();
        $draftManPower = $manPowerService->getManPower($request, true, ManPower::DRAFT)->count();
        $reviewedManPower = $manPowerService->getManPower($request, true, ManPower::REVIEWED)->count();
        return view('man_power.index',[
            'man_power' => $man_power,
            'draftManPower' => $draftManPower,
            'reviewedManPower' => $reviewedManPower
        ]);
    }
    public function getAllManPower(Request $request){
        $data = ManPower::with('workItems')->when($request->q, function ($q) use ($request){
            return $q->where('title','like','%'.$request->q.'%');
        })->get();

        return $data;
    }

    public function detail(ManPower $manPower, Request $request){
        if(auth()->user()->cannot('viewAny',ManPower::class)){
            return view('not_authorized');
        }

        $man_power_safety_rate = Setting::where('setting_type',Setting::MAN_POWER)->where('setting_name',Setting::MAN_POWER_SAFETY_RATE)->first();

        return view('man_power.detail',[
            'man_power' => $manPower,
            'man_power_safety_rate' => $man_power_safety_rate->setting_value
        ]);
    }

    public function create(){
        if(auth()->user()->cannot('create',ManPower::class)){
           return view('not_authorized');
        }

        $man_power_safety_rate = Setting::where('setting_type',Setting::MAN_POWER)->where('setting_name',Setting::MAN_POWER_SAFETY_RATE)->first();
        return view('man_power.create',[
            'man_power_safety_rate' => $man_power_safety_rate->setting_value
        ]);
    }

    public function store(Request $request){
        if(auth()->user()->cannot('create',ManPower::class)){
            abort(403);
        }
        $this->validate($request,[
            'code' => 'required|unique:man_powers',
            'skill_level' => 'required',
            'title' => 'required',
            'basic_rate_month' => 'required',
            'basic_rate_hour' => 'required',
            'general_allowance' => 'required',
            'bpjs' => 'required',
            'bpjs_kesehatan' => 'required',
            'thr' => 'required',
            'public_holiday' => 'required',
            'leave' => 'required',
            'pesangon' => 'required',
            'asuransi' => 'required',
            'safety' => 'required',
            'total_benefit_hourly' => 'required',
            'overall_rate_hourly' => 'required',
            'monthly' => 'required',
        ]);
        try{
            DB::beginTransaction();

            $manPower = new ManPower([
                'code' => $request->code,
                'skill_level' => $request->skill_level,
                'title' => $request->title,
                'basic_rate_month' => $this->convertToDecimal($request->basic_rate_month),
                'basic_rate_hour' => $this->convertToDecimal($request->basic_rate_hour),
                'general_allowance' => $this->convertToDecimal($request->general_allowance),
                'bpjs' =>  $this->convertToDecimal($request->bpjs),
                'bpjs_kesehatan' => $this->convertToDecimal($request->bpjs_kesehatan),
                'thr' =>  $this->convertToDecimal($request->thr),
                'public_holiday' => $this->convertToDecimal($request->public_holiday),
                'leave' => $this->convertToDecimal($request->leave),
                'pesangon' => $this->convertToDecimal($request->pesangon),
                'asuransi' => $this->convertToDecimal($request->asuransi),
                'safety' => $this->convertToDecimal($request->safety),
                'total_benefit_hourly' => $this->convertToDecimal($request->total_benefit_hourly),
                'overall_rate_hourly' => $this->convertToDecimal($request->overall_rate_hourly),
                'monthly' => $this->convertToDecimal($request->monthly),
                'created_by' => auth()->user()->id,
                'status' => ManPower::DRAFT
            ]);
            $manPower->save();
            DB::commit();
            $this->message('Data was successfully saved','success','fa fa-check','Success');
            return redirect('man-power');
        } catch (Exception $e){
            DB::rollback();
            return redirect('man-power/create')->withErrors($e->getMessage());
        }
    }

    public function update(ManPower $manPower, Request $request){
        if(auth()->user()->cannot('update',ManPower::class)){
           abort(403);
        }

        $this->validate($request,[
            Rule::unique('man_powers')->ignore($manPower->id),
            'skill_level' => 'required',
            'title' => 'required',
            'basic_rate_month' => 'required',
            'code' => 'required'
        ]);

        try {
            DB::begintransaction();
            $manPower->code = $request->code;
            $manPower->skill_level = $request->skill_level;
            $manPower->title = $request->title;
            $manPower->basic_rate_month = $this->convertToDecimal($request->basic_rate_month);
            $manPower->basic_rate_hour = $this->convertToDecimal($request->basic_rate_hour);
            $manPower->general_allowance = $this->convertToDecimal($request->general_allowance);
            $manPower->bpjs = $this->convertToDecimal($request->bpjs);
            $manPower->bpjs_kesehatan = $this->convertToDecimal($request->bpjs_kesehatan);
            $manPower->thr = $this->convertToDecimal($request->thr);
            $manPower->public_holiday = $this->convertToDecimal($request->public_holiday);
            $manPower->leave = $this->convertToDecimal($request->leave);
            $manPower->pesangon = $this->convertToDecimal($request->pesangon);
            $manPower->asuransi = $this->convertToDecimal($request->asuransi);
            $manPower->safety = $this->convertToDecimal($request->safety);
            $manPower->total_benefit_hourly = $this->convertToDecimal($request->total_benefit_hourly);
            $manPower->overall_rate_hourly = $this->convertToDecimal($request->overall_rate_hourly);
            $manPower->monthly = $this->convertToDecimal($request->monthly);
//            $manPower->updatedBy = auth()->user()->id;

            $manPower->save();
            DB::commit();
            $this->message('Data was successfully saved','success','fa fa-check','Success');
            return redirect('man-power/'.$manPower->id);

        } catch (Exception $e){
            DB::rollback();
            return redirect('man-power/'.$manPower->id)->withErrors($e->getMessage());
        }
    }

    public function delete(ManPower $manPower){
        if(auth()->user()->cannot('delete',ManPower::class)){
            return response()->json([
                'status' => 403,
                'message' => "You're not authorized"
            ]);
        }

        try{
            $manPower->delete();
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

    public function convertToDecimal($val){
        if(!$val) return '';
        $value = str_replace('.','',$val);
        $value = str_replace(',','.',$value);
        return $value;
    }

    public function getManPower(Request $request){
        $response = array();
        $data = ManPower::select('id','title','code','overall_rate_hourly')
            ->where(function($query) use ($request) {
                return $query->where('title','like','%'.$request->q.'%')
                    ->orwhere('code','like','%'.$request->q.'%');
            })->when(!auth()->user()->isManPowerReviewer(), function($query){
                return $query->where(function($q){
                   return $q->where('status', ManPower::REVIEWED)
                       ->orwhere('created_by', auth()->user()->id);
                });
            })->get();
        foreach($data as $v){
            $response[] = array(
                "text" => "[".$v->code . "] - " . $v->title,
                "id" => $v->id,
                "rate" => $v->overall_rate_hourly,
                "unit" => "hrs"
            );
        }

        return response()->json($response);
    }

    public function updateList(Request $request){
        $ids = (string) $request->ids;
        DB::beginTransaction();
        $ids = explode(',',$ids);
        try {
            $items = ManPower::whereIn('id',$ids)->get();

            $items->each(function ($item){
                $item->update(['status' => ManPower::REVIEWED]);
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
        $data = ManPower::all();
        try {
            Log::info('Starting Export Man Power');
            return Excel::download(new ManPowerExport($data), 'man-power.xlsx');
        } catch (Exception $e) {
            Log::info($e->getMessage());
            return response()->json('Import Failed : ' . $e->getMessage());
        }
    }

    public function import(Request $request){
        $file = $request->file('file');
        if($request->hasFile('file')){
            Log::info('Starting import man power...');
            Excel::import(new ManPowerImport, $file);
            Log::info('Import man power successful');
            return response()->json(['message' => 'Import Successful']);
        }

        return response()->json(['message' => 'No file uploaded'], 400);
    }

    public function message($message, $type, $icon, $status){
        Session::flash('message', $message);
        Session::flash('type', $type);
        Session::flash('icon', $icon);
        Session::flash('status', $status);
    }
}
