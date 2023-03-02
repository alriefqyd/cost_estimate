<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkElement extends Model
{
    use HasFactory;
    protected $guarded;

    //work item type can have many project, so there will be autotype in form work element
    //estimate_discipline_id not used

    public function estimateAllDisciplines(){
        return $this->hasMany(EstimateAllDiscipline::class,'work_element_id');
    }

    public function projects(){
        return $this->belongsTo(Project::class,'project_id');
    }
}
