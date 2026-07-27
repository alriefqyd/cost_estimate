<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Twilio\Rest\Client;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * Running all schedule queue here
 */
class SettingController extends Controller
{
    public function updateCurrencyUsd(){
        try {
            $apiController = new ApiController();
            $rate = $apiController->getUsdRateApi();
            if ($rate) {
                $setting = Setting::where('setting_type','USD_RATE')->first();
                $setting->setting_value = $rate;
                $setting->updated_at = now();
                $setting->save();
                Log::info('Running cron job update currency usd');
            } else {
                Log::warning('Skipped cron job update currency usd: API returned no rate');
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
        }

    }

    public function getUsdRateFromDB(){
        $sett = Setting::where('setting_type','USD_RATE')->first();
        return $sett->setting_value;
    }
}
