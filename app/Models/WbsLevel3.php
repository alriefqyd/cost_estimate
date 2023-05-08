<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Used for Work Breakdown Structure for each project
 * Estimate Discipline created based WBS Level 3
 */
class WbsLevel3 extends Model
{
    use HasFactory;

    protected $fillable = ['type','discipline','work_element'];
    public function projects(){
        return $this->belongsTo(Project::class,'project_id');
    }

    public function workElements(){
        return $this->belongsTo(WorkBreakdownStructure::class,'work_element');
    }

    public function estimateDisciplines(){
        return $this->hasMany(EstimateAllDiscipline::class,'equipment_location_id','work_element');
    }

    public function wbsDiscipline(){
        return $this->belongsTo(WorkBreakdownStructure::class,'discipline');
    }

}
