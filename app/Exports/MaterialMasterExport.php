<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class MaterialMasterExport implements WithMultipleSheets
{
    public function __construct($material, $category){
        $this->material = $material;
        $this->category = $category;
    }

    public function sheets(): array
    {
        $sheets = [
            'Material List' => new MaterialExport($this->material, $this->category),
            'Materials Category' => new MaterialCategoryExport($this->category)
        ];

        return $sheets;
    }
}
