<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DisciplineWorkType extends Model
{
    use HasFactory;

    public function estimateAllDisciplines(){
        return $this->hasOne(EstimateAllDiscipline::class,'work_type_id');
    }
}
