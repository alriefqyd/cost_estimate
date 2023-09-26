<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class WorkItemExport implements WithMultipleSheets
{
    public function sheets(): array
    {
        $sheets = [
            'Work Item List' => new WorkItemListExport(),
//            'Work Item Type' => new WorkItemTypeExport()
        ];

        return $sheets;
    }
}
