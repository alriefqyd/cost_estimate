<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;
    protected $guarded = ['id'];


    public function estimateAllDisciplines(){
        return $this->belongsToMany(EstimateAllDiscipline::class,'estimate_all_discipline_project');
    }

    public function designEngineerMechanical(){
        return $this->belongsTo(User::class,'design_engineer_mechanical');
    }

    public function designEngineerCivil(){
        return $this->belongsTo(User::class,'design_engineer_civil');
    }

    public function designEngineerElectrical(){
        return $this->belongsTo(User::class,'design_engineer_electrical');
    }

    public function designEngineerInstrument(){
        return $this->belongsTo(User::class,'design_engineer_instrument');
    }

}
