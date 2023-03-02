<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EquipmentTools extends Model
{
    use HasFactory;

    public function workItems(){
        return $this->belongsToMany(WorkItem::class,'work_items_equipment_tools')->withPivot('unit', 'amount','unit_price','unit');;
    }
}
