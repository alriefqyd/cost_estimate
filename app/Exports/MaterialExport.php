<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Cell\DataValidation;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;


class MaterialExport implements FromView, WithColumnFormatting, WithStyles,
    WithEvents, WithTitle,WithColumnWidths
{
    public function __construct($materials, $materialCategory)
    {
        $this->material = $materials;
        $this->size = sizeof($materials);
        $this->materialCategory = $materialCategory;
    }

    public function columnFormats(): array
    {
        return [
            'G' => NumberFormat::FORMAT_CURRENCY_USD
        ];

    }

    public function view(): View
    {
        $material = $this->material;
        return view('material.excel_list', [
            'materials' => $material
        ]);
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('C')->getAlignment()->setWrapText(true); // Enable text wrapping for cells A1 to B1
        $sheet->getStyle('H')->getAlignment()->setWrapText(true); // Enable text wrapping for cells A1 to B1
        $sheet->getStyle('A1:E4')->applyFromArray([
            'font' => [
                'bold' => true,
                'italic' => false,
            ],
        ]);

        $sheet->getStyle('A4')->applyFromArray([
            'alignment' => [
                'horizontal' =>Alignment::VERTICAL_CENTER,
                'vertical' => Alignment::HORIZONTAL_CENTER
            ],
        ]);

        $sheet->getStyle('A5:K6')->applyFromArray([
            'font' => [
                'bold' => true,
                'italic' => false,
            ],
            'alignment' => [
                'horizontal' =>Alignment::VERTICAL_CENTER,
                'vertical' => Alignment::HORIZONTAL_CENTER
            ],
        ]);

        $column = $this->size + 6;
        $column = 'A5:K'.$column;
        $sheet->getStyle($column)->applyFromArray([
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
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $equipmentToolsCategory = $this->materialCategory;
                $data = [];
                foreach ($equipmentToolsCategory as $ec) {
                    $data[] = $ec->description;
                }

                $drop_column = 'D';
                $start_row = 7; // Start row for data validation

                $row_count = count($this->material) + 100;

                // Set data validation
                $validation = $event->sheet->getCell("{$drop_column}{$start_row}")->getDataValidation();
                $validation->setType(DataValidation::TYPE_LIST);
                $validation->setErrorStyle(DataValidation::STYLE_INFORMATION);
                $validation->setAllowBlank(false);
                $validation->setShowInputMessage(true);
                $validation->setShowErrorMessage(true);
                $validation->setShowDropDown(true);
                $validation->setErrorTitle('Input error');
                $validation->setError('Value is not in list.');
                $validation->setPromptTitle('Pick from list');
                $validation->setPrompt('Please pick a value from the drop-down list.');

                $categoryCount = count($this->materialCategory);
                $lowerBound = $categoryCount + 6; // 6 for column upper hat use for title and header
                $range = "C$7:C$".$lowerBound;

                $validation->setFormula1("='Equipment Tools Category'!$range"); // Change "Sheet1" to your actual sheet name

                // Apply validation to the entire column
                for ($i = $start_row; $i <= $row_count; $i++) {
                    $event->sheet->getCell("{$drop_column}{$i}")->setDataValidation(clone $validation);
                }
            },
        ];
    }

    public function title(): string
    {
        return 'Material List';
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,
            'B' => 15,
            'C' => 70,
            'D' => 50,
            'E' => 10,
            'F' => 10,
            'G' => 25,
            'H' => 60,
            'I' => 10,
            'J' => 50,
            'K' => 30,
        ];
    }
}
