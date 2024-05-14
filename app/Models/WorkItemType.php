<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WorkItemType extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function workItems(){
        return $this->hasMany(WorkItem::class,'work_item_type_id');
    }

    /**
     * Deprecated!!!
     * @return string
     */
    public function getMaxCode(){
        $code = $this->code;
        $sufix = sizeof($this->workItems) + 1;
        $sufix = str_pad($sufix, 2, '0', STR_PAD_LEFT);
        $newCode = $code . '.' . $sufix;
        return $newCode;
    }

    public function scopeFilter($query, array $filters){
        $query->when($filters['q'] ?? false, fn($query,$q) =>
        $query->where('code','like','%'.$q.'%')
            ->orWhere('title','like','%'.$q.'%')
        );
    }
}
