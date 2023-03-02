<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EstimateAllDiscipline extends Model
{
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
}
