<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkItem extends Model
{
    use HasFactory;

    public function estimateAllDisciplines(){
        return $this->belongsTo(EstimateAllDiscipline::class,'work_item_id');
    }

    public function manPowers(){
        return $this->belongsToMany(ManPower::class,'labor_id');
    }

    public function materials(){
        return $this->hasMany(Material::class,'material_id');
    }

    public function tools(){
        return $this->hasMany(Tools::class,'tools_equipment_id');
    }

    public function workItemTypes(){
        return $this->belongsTo(WorkItemType::class,'work_item_type_id');
    }

    public function equipmentTools(){
        return $this->belongsToMany(EquipmentTools::class,'equipment_tool_id');
    }
}
