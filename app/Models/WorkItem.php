<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkItem extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
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

    /**
     * Deprecated
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tools(){
        return $this->hasMany(Tools::class,'tools_equipment_id');
    }

    public function workItemTypes(){
        return $this->belongsTo(WorkItemType::class,'work_item_type_id');
    }

    public function equipmentTools(){
        return $this->belongsToMany(EquipmentTools::class,'work_items_equipment_tools')->withPivot('quantity', 'amount','unit_price','unit');
    }

    public function scopeFilter($query, array $filters){
        $query->when($filters['q'] ?? false, fn($query,$q) =>
            $query->where('code','like','%'.$q.'%')
                ->orWhere('description','like','%'.$q.'%')
            );

        $query->when($filters['category'] ?? false, fn($query,$q) =>
            $query->where('work_item_type_id', $q)
        );
    }

    public function getTotalSum(){
       $total =  $this->materials->sum('pivot.amount') +
                $this->equipmentTools->sum('pivot.amount') +
                $this->manPowers->sum('pivot.amount');

       return $total;
    }
    public function parent(){
        return $this->belongsTo(Workitem::class,'parent_id');
    }

    public function children(){
        return $this->hasMany(WorkItem::class,'parent_id');
    }

    public function countChildren(){
        return $this->children->count();
    }
}
