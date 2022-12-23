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
        return $this->belongsToMany(Project::class,'estimate_all_discipline_project');
    }

    public function disciplineWorkTypes(){
        return $this->belongsTo(DisciplineWorkType::class,'work_type_id');
    }

    public function workItems(){
        return $this->hasMany(WorkItem::class,'work_item_id');
    }

    public function workElements(){
        return $this->hasMany(WorkElement::class,'id');
    }

}
