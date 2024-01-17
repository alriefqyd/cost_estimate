<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ManPowersWorkItems extends Model
{
    protected $table = ['man_powers_work_items'];
    use SoftDeletes;
    use HasFactory;
}
