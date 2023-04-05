<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DisciplineProjects extends Model
{
    use HasFactory;
    protected $fillable = [''];

    public function location(){
        return $this->belongsTo(LocationEquipments::class,'location_id');
    }
}
