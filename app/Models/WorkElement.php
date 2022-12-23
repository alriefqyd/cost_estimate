<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkElement extends Model
{
    use HasFactory;
    protected $guarded;

    //work item type can have many project, so there will be autotype in form work element
    public function estimateAllDisciplines(){
        return $this->belongsTo(EstimateAllDiscipline::class,'work_element_id');
    }
}
