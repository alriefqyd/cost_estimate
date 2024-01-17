<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * Class Used for Work Breakdown Structure for each project
 * Estimate Discipline created based WBS Level 3
 */
class WbsLevel3 extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = ['type','discipline','work_element'];
    public function projects(){
        return $this->belongsTo(Project::class,'project_id');
    }

    public function workElements(){
        return $this->belongsTo(WorkBreakdownStructure::class,'work_element');
    }

    public function disciplines(){
        return $this->belongsTo(WorkBreakdownStructure::class,'discipline');
    }

    public function estimateDisciplines(){
        return $this->hasMany(EstimateAllDiscipline::class,'wbs_level3_id','id');
    }

    public function wbsDiscipline(){
        return $this->belongsTo(WorkBreakdownStructure::class,'discipline');
    }

}
