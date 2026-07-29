<?php

namespace App\Http\Controllers;

use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class ApiController extends Controller
{
    public function getApi($url)
    {
        try {
            $response = Http::timeout(10)->get($url);
            if ($response->successful()) {
                return $response->json();
            }
        } catch (Exception $e) {
            report($e);
        }

        return [];
    }
    /** USD API */
    public function getUsdRateApi(){
        $req_url = "https://api.frankfurter.app/latest?from=USD&to=IDR";
        $response = $this->getApi($req_url);
        if($response) {
            return $response['rates']['IDR'] ?? null;
        }
        return null;
    }

    public function getPublicHolidayApi(){
        $data = $this->getApi('https://api-harilibur.netlify.app/api');

        if (!$data) return collect([]);

        return collect($data)
            ->filter(fn($item) => ($item['is_national_holiday'] ?? false) === true)
            ->map(fn($item) => [
                'holiday_date'       => \Carbon\Carbon::parse($item['holiday_date'])->format('Y-m-d'),
                'holiday_name'       => $item['holiday_name'],
                'is_national_holiday'=> true,
            ])
            ->values();
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
