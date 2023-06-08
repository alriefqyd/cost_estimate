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

    public const POSITION = [
        'project_engineer' => 'Project Engineer',
        'project_manager' => 'Project Manager',
        'design_civil_engineer' => 'Design Civil Engineer',
        'design_mechanical_engineer' => 'Design Mechanical Engineer',
        'design_electrical_engineer' => 'Design Electrical Engineer',
        'design_instrument_engineer' => 'Design Instrument Engineer',
        'super_administrator' => 'Super Administrator',
        'administrator' => 'Administrator'
    ];

    public function users(){
        return $this->belongsTo(User::class);
    }

    public function getPosition(){
        return Profile::POSITION[$this->position];
    }

    public function getCivilEngineer(){
        return User::with(['profiles','role'])->where('profiles.position',$this->position)->get();
    }
}
