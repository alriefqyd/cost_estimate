<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Material extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    public function workItems(){
        return $this->belongsToMany(WorkItem::class,'work_items_materials','work_item_id','materials_id')->withPivot('unit', 'quantity','amount','unit_price');
    }

    public function materialsCategory(){
        return $this->belongsTo(MaterialCategory::class,'category_id');
    }

    public function scopeFilter($query, array $filters){
        $query->when($filters['q'] ?? false, fn($query,$q) =>
        $query->where('code','like','%'.$q.'%')
            ->orWhere('tool_equipment_description','like','%'.$q.'%')
            ->orWhere('stock_code','like','%'.$q.'%')
            ->orWhere('ref_material_number','like','%'.$q.'%')
        );

        $query->when($filters['category'] ?? false, fn($query,$q) =>
            $query->where('category_id', $q)
        );
    }
}
