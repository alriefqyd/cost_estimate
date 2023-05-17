<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MaterialCategory extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $table = 'materials_categorys';

    public function materials(){
        $this->hasMany(Material::class,'category_id');
    }

    public function scopeFilter($query, array $filters){
        $query->when($filters['q'] ?? false, fn($query,$q) =>
        $query->where('code','like','%'.$q.'%')
            ->orWhere('description','like','%'.$q.'%')
        );
    }
}
