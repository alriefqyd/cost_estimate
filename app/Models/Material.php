<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    use HasFactory;

    public function workItems(){
        return $this->belongsToMany(WorkItem::class,'work_items_materials','work_item_id','materials_id')->withPivot('unit', 'quantity','amount','unit_price');
    }
}
