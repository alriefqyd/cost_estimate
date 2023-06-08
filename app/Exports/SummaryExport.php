<?php

namespace App\Exports;

use App\Http\Controllers\EstimateAllDisciplineController;
use App\Models\EstimateAllDiscipline;
use App\Models\Project;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SummaryExport extends AfterSheet implements FromView, ShouldAutoSize, WithStyles
{
    public function __construct($estimateDisciplines){
        $worksheet = new WorkSheet();
        $this->worksheet = $worksheet;
        $this->size = 100; //temporary
        $this->estimateDiscipline = $estimateDisciplines;
    }

    public function view(): View
    {
        $estimateAllDisciplines = $this->estimateDiscipline;
        return view('project.excel_table',[
            'estimateAllDisciplines' => $estimateAllDisciplines
        ]);
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('A1:I2')->applyFromArray([
            'font' => [
                'bold' => true,
                'italic' => false,
            ],
            'alignment' => [
                'horizontal' =>Alignment::VERTICAL_CENTER,
                'vertical' => Alignment::HORIZONTAL_CENTER
            ],
        ]);

        $sheet->getStyle('A3:A100')->applyFromArray([
            'font' => [
                'bold' => true,
                'italic' => false,
            ],
            'alignment' => [
                'horizontal' =>Alignment::VERTICAL_CENTER,
                'vertical' => Alignment::HORIZONTAL_CENTER
            ],
        ]);

        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();
        $cellRange = 'A1:' . $highestColumn . $highestRow;

        $sheet->getStyle($cellRange)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' => '000000'],
                ],
            ],
            'background' => [
                'color'=> '#2978ff'
            ],
        ]);

        $sheet->getStyle('F3:I100')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_CURRENCY_USD);

    }
}
