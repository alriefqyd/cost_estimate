<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var string[]
     */
    protected $fillable = [
        'position',
        'email',
        'full_name',
    ];

    public const REVIEWER = ['project_manager','project_engineer'];

    public const POSITION = [
        'project_engineer' => 'Project Engineer',
        'project_manager' => 'Project Manager',
        'design_civil_engineer' => 'Design Civil Engineer',
        'design_mechanical_engineer' => 'Design Mechanical Engineer',
        'design_electrical_engineer' => 'Design Electrical Engineer',
        'design_instrument_engineer' => 'Design Instrument Engineer',
        'administrator' => 'Administrator',
        'reviewer_cost_estimate' => 'Reviewer Cost Estimate',
        'reviewer_all_discipline_cost_estimate' => 'Reviewer All Discipline Cost Estimate',
        'reviewer_electrical_discipline_cost_estimate' => 'Reviewer Electrical Discipline Cost Estimate',
        'reviewer_instrument_discipline_cost_estimate' => 'Reviewer Instrument Discipline Cost Estimate',
        'reviewer_civil_discipline_cost_estimate' => 'Reviewer Civil Discipline Cost Estimate',
        'reviewer_mechanical_discipline_cost_estimate' => 'Reviewer Mechanical Discipline Cost Estimate',
        'others' => 'Others'
    ];

    public function users(){
        return $this->belongsTo(User::class);
    }

    public function getPosition(): string
    {
        if($this->position == 'others'){
            return $this->other_position ?? '';
        }
        return Profile::POSITION[$this?->position];
    }

    public function getCivilEngineer(){
        return User::with(['profiles','role'])->where('profiles.position',$this->position)->get();
    }
}
