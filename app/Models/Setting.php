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
    public const USD_KURS = 15000;

    const FORMAT_CURRENCY = '#,##0.00_-';
}
