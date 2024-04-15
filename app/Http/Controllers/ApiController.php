<?php

namespace App\Http\Controllers;

use Cassandra\Date;
use Exception;
use Illuminate\Http\Request;

class ApiController extends Controller
{
    public function getApi($url){

        $response_json = file_get_contents($url);
        if(false !== $response_json) {
            try {
                $response = json_decode($response_json);
                if(isset($response)) {
                    return $response;
                }

            }
            catch(Exception $e) {
                return null;
            }

        }
        return null;
    }
    /** USD API */
    public function getUsdRateApi(){
        $req_url = "https://api.frankfurter.app/latest?from=USD&to=IDR";
        $response = $this->getApi($req_url);
        if($response) {
            return $response->rates->IDR;
        }
        return null;
    }

    public function getPublicHolidayApi(){
        $reqUrl = "https://api-harilibur.vercel.app/api?year=".date('Y');
        $data = $this->getApi($reqUrl);
        $collection = collect($data)->map(function ($item) {
            return (object) $item;
        });

        return $collection;
    }

}
