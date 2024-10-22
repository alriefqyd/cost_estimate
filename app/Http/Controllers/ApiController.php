<?php

namespace App\Http\Controllers;

use App\Models\User;
use Cassandra\Date;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use function PHPUnit\TestFixture\func;

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

    public function getReviewer(Request $request){
        $reviewer = DB::table('user_role as ur')->select('ur.user_id','p.full_name','r.name')->join('roles as r','ur.role_id','r.id')
            ->join('profiles as p','ur.user_id','p.user_id')->where('feature','cost_estimate')
            ->where(function($q) use ($request){
                return $q->where('r.name','like','%review '.$request->discipline.'%')->orwhere('r.action','review_all_discipline_cost_estimate')
                    ->orwhere(function($qq) use ($request){
                        return $qq->where('r.action','review_cost_estimate')->where('p.position','design_'.$request->discipline.'_engineer');
                    });
            })->groupBy('p.full_name')->get();

        $reviewer = $reviewer->map(function ($item){
           return [
               'id' => $item->user_id,
               'text' => $item->full_name
           ];
        });

        return $reviewer;
    }

}
