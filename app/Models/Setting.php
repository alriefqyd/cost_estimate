<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    public const DESIGN_ENGINEER_LIST = [
        'civil' => 'Civil',
        'mechanical' => 'Mechanical',
        'electrical' => 'Electrical',
        'instrument' => 'Instrument'
    ];

    public const DESIGN_ENGINEER_LIST_KEY = [
        'civil' => 'civil',
        'mechanical' => 'mechanical',
        'electrical' => 'electrical',
        'instrument' => 'instrument'
    ];

    public const SKILL_LEVEL = [
      'skilled' => 'Skilled',
      'semi_skilled' => 'Semi Skilled',
      'unskilled' => 'Un Skilled'
    ];

    public const MAN_POWER_SAFETY_RATE = 'MAN_POWER_SAFETY_RATE';
    public const MAN_POWER = 'MAN_POWER';
    public const MPSAFETY = 'MPSAFETY';

    public const CODE_NEW_CHILD_WORK_ITEM = 'A';
    public const LEVEL_DISCIPLINE = 2;
    public const LEVEL_WORK_ELEMENT = 3;

    const FORMAT_CURRENCY = '#,##0.00_-';
    public const GUIDELINES_PAGE = [
        'Home' => 'Home',
        'Cost Estimate List' => 'Cost Estimate List',
        'Create New Cost Estimate' => 'Create New Cost Estimate',
        'Cost Estimate Detail Page' => 'Cost Estimate Detail Page',
        'Work Breakdown Structure' => 'Work Breakdown Structure',
        'Estimate Discipline' => 'Estimate Discipline',
        'Export Cost Estimate' => 'Export Cost Estimate',
        'Work Item' => 'Work Item',
        'Man Power' => 'Man Power',
        'Tools and Equipment' => 'Tools and Equipment',
        'Material' => 'Material',
        'Wbs Setting' => 'Wbs Setting',
        'User Setting' => 'User Setting'
    ];

    public const GUIDELINES_PAGE_TYPE = 'GUIDELINES_PAGE';
    public const HOME_PAGE = 'Home';
    public const HOME_PAGE_CODE = 'HOME_PAGE';
}
