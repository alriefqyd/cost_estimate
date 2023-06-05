<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;
    protected $fillable = ['action','feature'];

    public const ACTION = [
        '*' => 'All',
        'create' => 'Create',
        'read' => 'Read',
        'update' => 'Update',
        'delete' => 'Delete'
    ];

    public const FEATURE = [
        'cost_estimate' => 'Cost Estimate',
        'work_item' => 'Work Item',
        'estimate_discipline' => 'Estimate All Discipline',
        'man_power' => 'Man Power',
        'tool_equipment' => 'Tool Equipment',
        'material' => 'Material',
        'wbs' => 'Work Breakdown Structure',
        'wbss' => 'Work Breakdown Structure Setting',
        'user' => 'User'
    ];

    public function users(){
        return $this->belongsToMany(User::class,'user_role')->withPivot('created_by','updated_by');
    }

    public function getName($value){
        return $this::ACTION[$value];
    }
}
