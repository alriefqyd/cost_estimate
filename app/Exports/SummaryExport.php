<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;

class SummaryExport extends AfterSheet implements FromView, WithStyles
{
    public function __construct($estimateDisciplines, $project, $costProjects){
        $worksheet = new WorkSheet();
        $this->worksheet = $worksheet;
        $this->size = 100; //temporary
        $this->estimateDiscipline = $estimateDisciplines;
        $this->project = $project;
        $this->costProject = $costProjects;
    }

    public function view(): View
    {
        $estimateAllDisciplines = $this->estimateDiscipline;
        $costProject = $this->costProject;
        return view('project.excel_table',[
            'project' => $this->project,
            'estimateAllDisciplines' => $estimateAllDisciplines,
            'costProject' => $costProject
        ]);
    }

    public function styles(Worksheet $sheet)
    {
        /**
         * Title
         */
        $sheet->getStyle('A2:H5')->applyFromArray([
            'font' => [
                'bold' => true,
                'italic' => false,
            ],
            'alignment' => [
                'horizontal' =>Alignment::VERTICAL_CENTER,
                'vertical' => Alignment::HORIZONTAL_CENTER
            ],
        ]);

        /**
         * Table Header
         */
        $sheet->getStyle('A13:H14')->applyFromArray([
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

        $sheet->getStyle('A1:C'.$highestRow)->getAlignment()
            ->setHorizontal(Alignment::HORIZONTAL_CENTER)
            ->setVertical(Alignment::VERTICAL_CENTER);


        $cellRange = 'A13:' . $highestColumn . $highestRow;

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

        $sheet->getStyle('E15:I100')->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_CURRENCY_IDR);
        $sheet->getStyle('A2:D5')->applyFromArray([
            'font' => [
                'bold' => true,
                'italic' => false,
            ],
            'alignment' => [
                'horizontal' => Alignment::VERTICAL_JUSTIFY,
                'vertical' => Alignment::HORIZONTAL_LEFT
            ],
        ]);
        $sheet->getStyle('A1:H12')
            ->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()
            ->setRGB('FFFFFF');

        // Remove borders for each cell
        $sheet->getStyle('A1:H11')
            ->getBorders()
            ->getAllBorders()
            ->setBorderStyle(Border::BORDER_NONE);


        for($i=1; $i<13; $i++){
            $cell = 'A'.$i.':'.'E'.$i;
            $sheet->mergeCells($cell);

            // Enable text wrapping for the merged cells
            $sheet->getStyle($cell)->getAlignment()->setWrapText(true);

            // Set alignment for the merged cells
            $sheet->getStyle($cell)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_LEFT);
            $sheet->getStyle($cell)->getAlignment()->setVertical(Alignment::VERTICAL_TOP);
        }
        $sheet->getStyle('H15:H100')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

        $sheet->getColumnDimension('E')->setWidth(30);
        $sheet->getColumnDimension('D')->setWidth(40);
        $sheet->getColumnDimension('F')->setWidth(30);
        $sheet->getColumnDimension('G')->setWidth(30);
        $sheet->getColumnDimension('H')->setWidth(30);
        $sheet->getColumnDimension('I')->setWidth(30);

        // Enable text wrapping in column A
        $sheet->getStyle('E')->getAlignment()->setWrapText(true);

        $imagePath = public_path('assets/images/vale.jpg');

        $drawing = new Drawing();
        $drawing->setPath($imagePath);

        $drawing->setWidth(50);
        $drawing->setHeight(50);
        $drawing->setCoordinates('H2'); // Starting cell for the image
        $drawing->setOffsetX(100); // Adjust the X offset to position the image within the cell
        $drawing->setOffsetY(10); // Adjust the Y offset to position the image within the cell
        $drawing->setWorksheet($sheet);
        $pageSetup = new PageSetup();

        // Set the page setup options
        $pageSetup->setFitToPage(true); // Fit the content to a single page
        $pageSetup->setPrintArea('A1:K20');
        $pageSetup->setFitToHeight(0); // Disable fitting to page height
        $pageSetup->setFitToWidth(1); // Fit the content to the page width
        $pageSetup->setOrientation(PageSetup::ORIENTATION_LANDSCAPE); // Set the page orientation to landscape
        $pageSetup->setPaperSize(PageSetup::PAPERSIZE_A3); // Set the paper size to A4
        $pageSetup->setScale(100); // Set the scaling factor (percentage) for the print view

        // Apply the PageSetup instance to the worksheet
        $sheet->setPageSetup($pageSetup);

    }
}
