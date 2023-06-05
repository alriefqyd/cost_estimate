<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;
    protected $guarded = ['id'];


    public function estimateAllDisciplines(){
        return $this->hasMany(EstimateAllDiscipline::class,'project_id');
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

    public function workElements(){
        return $this->hasMany(WorkElement::class,'project_id');
    }

    public function wbsLevel3s(){
        return $this->hasMany(WbsLevel3::class,'project_id');
    }

    public function scopeFilter($query, array $filters){
        $query->when($filters['q'] ?? false, fn($query, $q) =>
        $query->where('project_title','like','%'.$q.'%')
            ->orWhere('project_no','like','%'.$q.'%')
        );
    }

    public function projectWbsLevel3(){
        return $this->hasMany(WbsLevel3::class,'project_id');
    }

    public function scopeAccess($query){
        $position = auth()->user()->profiles?->position;
        return $query->when($position == 'design_civil_engineer', function($q){
            return $q->whereHas('designEngineerCivil.profiles',function($qq){
                return $qq->where('design_engineer_civil',auth()->user()->id);
            });
        })->when($position == 'design_mechanical_engineer', function($q) {
            return $q->whereHas('designEngineerMechanical.profiles', function ($qq) {
                return $qq->where('design_engineer_mechanical', auth()->user()->id);
            });
        })->when($position == 'design_electrical_engineer', function($q) {
            return $q->whereHas('designEngineerElectrical.profiles', function ($qq) {
                return $qq->where('design_engineer_electrical', auth()->user()->id);
            });
        })->when($position == 'design_instrument_engineer', function($q) {
            return $q->whereHas('designEngineerInstrument.profiles', function ($qq) {
                return $qq->where('design_engineer_instrument', auth()->user()->id);
            });
        });
    }

}
