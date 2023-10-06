<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class EquipmentToolsMasterExport implements WithMultipleSheets
{
    public function __construct($equipmentTools, $category){
        $this->equipment = $equipmentTools;
        $this->category = $category;
    }

    public function sheets(): array
    {
        $sheets = [
            'Equipment Tools List' => new EquipmentToolsExport($this->equipment),
            'Equipment Tools Category' => new EquipmentToolsCategoryExport($this->category)
        ];

        return $sheets;
    }
}
