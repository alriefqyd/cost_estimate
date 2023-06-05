<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkBreakdownStructure extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    public function wbsLevel3s(){
        return $this->hasOne(WbsLevel3::class,'discipline');
    }

    public function wbsDiscipline(){
        return $this->hasMany(WorkBreakdownStructure::class,'parent_id');
    }

    public function wbsLevel3WorkElements(){
        return $this->hasOne(WbsLevel3::class,'work_element');
    }

    public function parent(){
        return $this->belongsTo(WorkBreakdownStructure::class,'parent_id');
    }

    public function children(){
        return $this->hasMany(WorkBreakdownStructure::class,'parent_id');
    }

    public function scopeFilter($query, array $filters){
        $query->when($filters['q'] ?? false, fn($query,$q) =>
            $query->where('title','like','%'.$q.'%')
        );
    }
}
