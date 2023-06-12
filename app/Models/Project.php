<?php

namespace App\Models;

use Exception;
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

    public function getTotalCost(){
        try{
            $labor = $this->estimateAllDisciplines->sum('labor_cost_total_rate');
            $tool = $this->estimateAllDisciplines->sum('tool_unit_rate_total');
            $material = $this->estimateAllDisciplines->sum('material_unit_rate_total');

            $total =  $labor + $tool + $material;
            return $total;
        } catch(Exception $e){
            return 0;
        }

    }

    public function getContingencyCost(){
        try{
            $total = $this->getTotalCost() * (15/100);
            return $total;
        } catch(Exception $e){
            return 0;
        }
    }

    public function getTotalCostWithContingency(){
        $total = $this->getTotalCost();
        $contingency = $this->getContingencyCost();
        return $total + $contingency;
    }

    public function getMechanicalEngineer(){
        if(!$this->designEngineerMechanical) return '-';
        return $this->designEngineerMechanical?->profiles?->full_name;
    }

    public function getCivilEngineer(){
        if(!$this->designEngineerCivil) return '-';
        return $this->designEngineerCivil?->profiles?->full_name;
    }

    public function getElectricalEngineer(){
        if(!$this->designEngineerElectrical) return '-';
        return $this->designEngineerElectrical?->profiles?->full_name;
    }

    public function getInstrumentEngineer(){
        if(!$this->designEngineerInstrument) return '-';
        return $this->designEngineerInstrument?->profiles?->full_name;
    }

}
