<?php

namespace App\Models;

use App\Class\ProjectTotalCostClass;
use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class EstimateAllDiscipline extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'work_scope'
    ];

    protected $guarded = ['version'];

    public function projects(){
        return $this->belongsTo(Project::class,'project_id');
    }

    public function disciplineWorkTypes(){
        return $this->belongsTo(DisciplineWorkType::class,'work_type_id');
    }

    public function workItems(){
        return $this->belongsTo(WorkItem::class,'work_item_id');
    }

    public function workElements(){
        return $this->belongsTo(WorkElement::class,'work_element_id');
    }

    /** Deprecated */
    public function wbsLevels3(){
        return $this->belongsTo(WbsLevel3::class,'id','wbs_level3_id');
    }

    public function wbss(){
        return $this->belongsTo(WbsLevel3::class,'wbs_level3_id','id');
    }

    public function incrementVersion()
    {
        $this->version++;
        $this->save();
    }

}
