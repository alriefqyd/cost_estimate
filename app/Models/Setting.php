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
}
