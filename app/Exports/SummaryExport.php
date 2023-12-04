<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMultipleSheets;
class SummaryExport implements WithMultipleSheets
{
    public function __construct($estimateDisciplines, $project, $costProjects){
        $this->estimateDiscipline = $estimateDisciplines;
        $this->project = $project;
        $this->costProject = $costProjects;
    }

    public function sheets(): array
    {
        $sheets = [
            'Highlight' => new HighlightExport($this->estimateDiscipline,$this->project,$this->costProject, false),
            'Detail' => new HighlightExport($this->estimateDiscipline,$this->project,$this->costProject, true)
        ];

        return $sheets;
    }
}
