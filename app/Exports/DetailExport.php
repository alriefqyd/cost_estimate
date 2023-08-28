<?php

namespace App\Exports;

use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class DetailExport
{
    public function __construct($estimateDisciplines, $project, $costProjects){
        $worksheet = new WorkSheet();
        $this->worksheet = $worksheet;
        $this->size = 100; //temporary
        $this->estimateDiscipline = $estimateDisciplines;
        $this->project = $project;
        $this->costProject = $costProjects;
    }
}
