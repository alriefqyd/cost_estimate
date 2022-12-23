<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tools extends Model
{
    use HasFactory;

    public function workItems(){
        return $this->belongsTo(WorkItem::class,'tools_equipment_id');
    }
}
