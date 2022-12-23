<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManPower extends Model
{
    use HasFactory;

    public function workItems(){
        return $this->belongsToMany(WorkItem::class,'labor_id');
    }

}
