<?php

namespace App\Exports;

use App\Models\WorkItem;
use App\Models\WorkItemType;
use App\Services\WorkItemServices;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

/**
 *
 */
class WorkItemListExport implements FromView, WithStyles, WithColumnWidths,
    WithColumnFormatting
{
    public function __construct(){
        $this->workItemCategory = WorkItemType::all();
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function view(): View
    {
        $workItemService = new WorkItemServices();
        $workItemList = $workItemService->getWorkItemList();

        return view ('work_item.excel_list', [
            'workItem' => $workItemList,
            'category' => $this->workItemCategory
        ]);
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->getStyle('B')->getAlignment()->setWrapText(true); // Enable text wrapping for cells A1 to B1
        $sheet->getStyle('C')->getAlignment()->setWrapText(true); // Enable text wrapping for cells A1 to B1
        $sheet->getStyle('L')->getAlignment()->setWrapText(true); // Enable text wrapping for cells A1 to B1
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

        $sheet->getStyle('A5:U6')->applyFromArray([
            'font' => [
                'bold' => true,
                'italic' => false,
            ],
            'alignment' => [
                'horizontal' =>Alignment::VERTICAL_CENTER,
                'vertical' => Alignment::HORIZONTAL_CENTER
            ],
        ]);

        $column = 'A5:U6';
        $sheet->getStyle($column)->applyFromArray([
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['argb' =>  '000000'],
                ],
            ],
            'background' => [
                'color'=> '#2978ff'
            ],
        ]);
    }

    public function columnWidths(): array
    {
        return [
            'A' => 15,
            'B' => 70,
            'C' => 70,
            'D' => 50,
            'E' => 10,
            'F' => 25,
            'G' => 25,
            'H' => 10,
            'I' => 35,
            'J' => 35,
            'K' => 15,
            'L' => 50,
            'M' => 10,
            'N' => 5,
            'O' => 25,
            'P' => 25,
            'Q' => 50,
            'R' => 10,
            'S' => 5,
            'T' => 25,
            'U' => 25,
            'V' => 25,
        ];
    }

    public function columnFormats(): array
    {
        return [
            'I' => NumberFormat::FORMAT_CURRENCY_USD,
            'O' => NumberFormat::FORMAT_CURRENCY_USD,
            'P' => NumberFormat::FORMAT_CURRENCY_USD,
            'J' => NumberFormat::FORMAT_CURRENCY_USD,
            'T' => NumberFormat::FORMAT_CURRENCY_USD,
            'U' => NumberFormat::FORMAT_CURRENCY_USD,
        ];
    }
}
