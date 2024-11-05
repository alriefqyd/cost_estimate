<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;
    protected $fillable = ['action','feature'];

    public const ACTION = [
        'create' => 'Create',
        'read' => 'Read',
        'update' => 'Update',
        'delete' => 'Delete',
        'reviewer' => 'Reviewer',
        'civil_reviewer' => 'Civil Reviewer', //deprecated
        'mechanical_reviewer' => 'Mechanical Reviewer', //deprecated
        'electrical_reviewer' => 'Electrical Reviewer', //deprecated
        'instrument_reviewer' => 'Instrument Reviewer', //deprecated
    ];

    public const ACTION_COST_ESTIMATE = [
        'read_all' => 'View All Cost Estimate',
        'read_civil' => 'View All Civil Cost Estimate',
        'read_mechanical' => 'View All Mechanical Cost Estimate',
        'read_electrical' => 'View All Electrical Cost Estimate',
        'read_instrument' => 'View All Instrument Cost Estimate',
        'read_assignee' => 'View Cost Estimate Assignee',
        'review_civil_cost_estimate' => 'Review Civil Discipline Cost Estimates',
        'review_mechanical_cost_estimate' => 'Review Mechanical Discipline Cost Estimates',
        'review_electrical_cost_estimate' => 'Review Electrical Discipline Cost Estimates',
        'review_instrument_cost_estimate' => 'Review Instrument Discipline Cost Estimates',
        'review_it_cost_estimate' => 'Review IT Discipline Cost Estimates',
        'review_architect_cost_estimate' => 'Review Architect Discipline Cost Estimates',
        'review_all_discipline_cost_estimate' => 'Review All Discipline Cost Estimates',
        'review_cost_estimate' => 'Review Cost Estimates',
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
