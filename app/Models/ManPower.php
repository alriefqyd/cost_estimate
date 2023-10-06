<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManPower extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public const DRAFT = 'DRAFT';
    public const REVIEWED = 'REVIEWED';
    public function workItems(){
        return $this->belongsToMany(WorkItem::class,'man_powers_work_items')->withPivot('labor_unit', 'labor_coefisient','amount');
    }

    public function scopeFilter($query, array $filters){
        $query->when($filters['q'] ?? false, fn($query,$q) =>
            $query->where(function($qq) use ($q){
                return $qq->where('code','like','%'.$q.'%')
                    ->orWhere('title','like','%'.$q.'%');
            })
        );
        $query->when($filters['status'] ?? false, fn($query, $q) =>
            $query->where('status', $q)
        );
        $query->when($filters['skill_level'] ?? false, fn($query,$q) =>
            $query->where('skill_level',$q)
        );
    }

    public function getSkillLevel(){
        return Setting::SKILL_LEVEL[$this->skill_level] ?? "";
    }
}
