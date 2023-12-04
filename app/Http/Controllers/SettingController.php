<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SettingController extends Controller
{
    public function updateCurrencyUsd(){
        try {
            $setting = Setting::where('setting_type','USD_RATE')->first();
            $setting->setting_value = $this->getUsdRateApi();
            $setting->updated_at = now();
            $setting->save();
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }

    }

    public function getUsdRateApi(){
        $req_url = "https://api.fxratesapi.com/latest?base=USD&currencies=IDR&format=json";
        $response_json = file_get_contents($req_url);
        if(false !== $response_json) {
            try {

                // Decoding
                $response = json_decode($response_json);

                // Check for success
                if($response->success) {
                    // YOUR APPLICATION CODE HERE, e.g.
                    return $response->rates->IDR;

                }

            }
            catch(Exception $e) {
                return null;
                $e->getMessage();
            }

        }
    }

    public function getUsdRateFromDB(){
        $sett = Setting::where('setting_type','USD_RATE')->first();
        return $sett->setting_value;
    }
}
