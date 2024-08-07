<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

/**
 * Running all schedule queue here
 */
class SettingController extends Controller
{
    public function updateCurrencyUsd(){
        try {
            $apiController = new ApiController();
            $setting = Setting::where('setting_type','USD_RATE')->first();
            $setting->setting_value = $apiController->getUsdRateApi();
            $setting->updated_at = now();
            $setting->save();
            Log::info('Running cron job update currency usd');
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }

    }

    public function getUsdRateFromDB(){
        $sett = Setting::where('setting_type','USD_RATE')->first();
        return $sett->setting_value;
    }

    public function viewPreviewPage(){
        return view('page.preview');
    }
    public function getGuidelinesPage(Request $request){
        $data = Setting::where('setting_type', Setting::GUIDELINES_PAGE_TYPE)->where('setting_name',$request->page)->first();
        if(isset($data)){
            return response()->json([
                'status' => 200,
                'content' => $data->setting_value
            ]);
        }
        return response()->json([
            'status' => 500,
        ]);
    }

    public function getGuidelinesPageList(){
        $arr = [];
        $a = Setting::GUIDELINES_PAGE;
        $arr = [
            [
                'text' => 'Home',
                'id' => '1',
                'children' => [
                    [
                        'text' => 'Child 1',
                        'id' => '1-1'
                    ]
                ]
            ],
            [
                'text' => 'Guid',
                'id' => '2'
            ]
        ];

        return response()->json([
            'status' => 200,
            'data' => $arr
        ]);
    }

    public function savePreview(Request $request){
        DB::beginTransaction();
        try {
            $setting = Setting::where('setting_type', Setting::GUIDELINES_PAGE_TYPE)->
                where('setting_name',$request->setting_name)
                ->first();
            if(!isset($setting)){
                $setting = new Setting();
            }
            $setting->setting_type = Setting::GUIDELINES_PAGE_TYPE;
            $setting->setting_name = Setting::HOME_PAGE;
            $setting->setting_value = $request->content;
            $setting->setting_code = Setting::HOME_PAGE_CODE;
            $setting->save();
            DB::commit();
            Session::flash('message', 'Content Saved Successfully');
            Session::flash('type', 'success');
            Session::flash('icon', 'fa fa-check');
            Session::flash('status', 'Success');
        } catch (\Exception $e){
            DB::rollBack();
            Session::flash('message', $e->getMessage());
            Session::flash('type', 'danger');
            Session::flash('icon', 'fa fa-check');
            Session::flash('status', 'Error');
        }
        return redirect('/preview');
    }

    public function getContentPreview(Request $request){
        $data = Setting::where('setting_type', Setting::GUIDELINES_PAGE_TYPE)->where('setting_name', $request->page)
            ->first();

        if(isset($data)){
            return response()->json([
                'status' => 200,
                'content' => $data->setting_value
            ]);
        }

        return response()->json([
            'status' => 500,
        ]);

    }
}
