<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProjectSettings extends Model
{
    use HasFactory;

    public $guarded = ['id'];

    public function projects(){
        return $this->belongsTo(Project::class,'project_id');
    }
}
