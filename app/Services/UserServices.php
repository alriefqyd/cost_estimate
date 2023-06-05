<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Http\Request;

class UserServices
{
    /**
     * Constructor
     */
    public function __construct()
    {

    }

    /**
     * Get User Employee Based on Subject
     * @param Request $request
     * @return \Illuminate\Database\Eloquent\Builder[]|\Illuminate\Database\Eloquent\Collection
     */
    public function getUserEmployee(Request $request){
        $subject = $request->subject;
        $key = $request->q;
        $data = User::with('profiles')->whereHas('profiles', function ($q) use ($subject, $key){
            return $q->when((isset($subject)), function($qq) use ($subject,$key){
                return $qq->where('position',$subject);
            })->when((isset($key)), function($qq) use ($subject,$key){
                return $qq->where('full_name','like','%'.$key.'%');
            });
        });

        $data = $data->get();
        $response = array();

        foreach ($data as $d){
            $response[] = array(
                "id"=>$d->id,
                "text" =>$d->profiles->full_name
            );
        }

        return $response;
    }
}
