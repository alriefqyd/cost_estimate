<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Departments extends Model
{
    use HasFactory;

    public const TYPE = [
        'department' => 'DEPARTMENT',
        'sub_department' => 'SUB-DEPARTMENT'
    ];

    public function projects(){
        return $this->hasMany(Project::class,'project_area_id');
    }
}
