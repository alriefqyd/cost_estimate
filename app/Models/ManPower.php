<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManPower extends Model
{
    use HasFactory;

    public function workItems(){
        return $this->belongsToMany(WorkItem::class,'man_powers_work_items')->withPivot('labor_unit', 'labor_coefisient','amount');
    }

}
