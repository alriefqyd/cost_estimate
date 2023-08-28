<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EquipmentTools extends Model
{
    use HasFactory;
    protected $guarded;

    public const DRAFT = 'DRAFT';
    public const REVIEWED = 'REVIEWED';
    public function workItems(){
        return $this->belongsToMany(WorkItem::class,'work_items_equipment_tools')->withPivot('unit', 'amount','unit_price','unit');;
    }

    public function equipmentToolsCategory(){
        return $this->belongsTo(EquipmentToolsCategory::class,'category_id');
    }

    public function scopeFilter($query, array $filters){
        $query->when($filters['q'] ?? false, fn($query,$q) =>
            $query->where(function($qq) use ($q){
                return $qq->where('code','like','%'.$q.'%')
                    ->orWhere('description','like','%'.$q.'%');
            })
        );
        $query->when($filters['category'] ?? false, fn($query,$q) =>
            $query->where('category_id',$q)
        );
        $query->when($filters['status'] ?? false, fn($query,$q) =>
            $query->where('status',$q)
        );
    }
}
