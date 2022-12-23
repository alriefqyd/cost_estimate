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
        $data = User::with('profiles')->whereHas('profiles', function ($q) use ($subject){
            $query = $q;
            if(isset($subject)){
                $query->where('subject',$subject);
            }
            return $query;
        });

        if($key){
            $data = $data->where('name','like','%'.$key.'%');
        }

        $data = $data->get();
        $response = array();

        foreach ($data as $d){
            $response[] = array(
                "id"=>$d->id,
                "text" =>$d->name
            );
        }

        return $response;
    }
}
