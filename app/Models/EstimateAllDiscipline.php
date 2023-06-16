<?php

namespace App\Models;

use App\Class\ProjectTotalCostClass;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstimateAllDiscipline extends Model
{
    /**
     * equipment_location_id tidak bisa jadi patokan karena kalau dia dobel di discipline berbeda tidak bisa di tahu
     */
    use HasFactory;

    protected $fillable = [
        'work_scope'
    ];

    public function projects(){
        return $this->belongsTo(Project::class,'project_id');
    }

    public function disciplineWorkTypes(){
        return $this->belongsTo(DisciplineWorkType::class,'work_type_id');
    }

    public function workItems(){
        return $this->belongsTo(WorkItem::class,'work_item_id');
    }

    public function workElements(){
        return $this->belongsTo(WorkElement::class,'work_element_id');
    }

    /** Deprecated */
    public function wbsLevels3(){
        return $this->belongsTo(WbsLevel3::class,'equipment_location_id','work_element');
    }

    public function wbss(){
        return $this->belongsTo(WbsLevel3::class,'wbs_level3_id','id');
    }

}
