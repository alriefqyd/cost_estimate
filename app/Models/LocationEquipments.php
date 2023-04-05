<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LocationEquipments extends Model
{
    use HasFactory;
    protected $fillable = ['project_id','title'];

    public function projects(){
        return $this->belongsTo(Project::class);
    }

    public function workBreakdownStructureLocation(){
        return $this->hasMany(DisciplineProjects::class);
    }

    public function disciplineProjects(){
        return $this->hasMany(DisciplineProjects::class,'location_id');
    }

}
