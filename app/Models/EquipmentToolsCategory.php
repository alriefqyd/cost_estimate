<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EquipmentToolsCategory extends Model
{
    use HasFactory;
    protected $table = 'equipment_tools_categorys';
    protected $guarded;

    public function equipmentTools(){
        return $this->hasMany(EquipmentTools::class,'category_id');
    }

    public function scopeFilter($query, array $filters){
        $query->when($filters['q'] ?? false, fn($query,$q) =>
        $query->where('code','like','%'.$q.'%')
            ->orWhere('description','like','%'.$q.'%')
        );
    }
}
