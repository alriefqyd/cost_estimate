<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkItem extends Model
{
    use HasFactory;

    public function estimateAllDisciplines(){
        return $this->hasMany(EstimateAllDiscipline::class,'work_item_id');
    }

    /**
     * Relation to labor/Man Powers
     * Once Amount total work hourly update in manpower amount labor is updated to
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function manPowers(){
        return $this->belongsToMany(ManPower::class,'man_powers_work_items')->withPivot('labor_unit', 'labor_coefisient','amount');
    }

    public function materials(){
        return $this->belongsToMany(Material::class,'work_items_materials','work_item_id','materials_id')->withPivot('unit', 'quantity','amount','unit_price');
    }

    public function tools(){
        return $this->hasMany(Tools::class,'tools_equipment_id');
    }

    public function workItemTypes(){
        return $this->belongsTo(WorkItemType::class,'work_item_type_id');
    }

    public function equipmentTools(){
        return $this->belongsToMany(EquipmentTools::class,'work_items_equipment_tools')->withPivot('quantity', 'amount','unit_price','unit');
    }
}
