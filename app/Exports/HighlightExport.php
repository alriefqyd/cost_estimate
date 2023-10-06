<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\PageSetup;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Events\AfterSheet;
class HighlightExport extends AfterSheet implements FromView, WithStyles, WithTitle
{
    public function __construct($estimateDisciplines, $project, $costProjects, $isDetail){
        $worksheet = new WorkSheet();
        $this->worksheet = $worksheet;
        $this->size = 100; //temporary
        $this->estimateDiscipline = $estimateDisciplines;
        $this->project = $project;
        $this->costProject = $costProjects;
        $this->isDetail = $isDetail;
    }

    public function title(): string
    {
       if($this->isDetail) return 'Estimate All Discipline';
       return 'Summary';
    }

    public function view(): View
    {
        $estimateAllDisciplines = $this->estimateDiscipline;
        $costProject = $this->costProject;
        return view('project.excel_format.excel_table',[
            'project' => $this->project,
            'estimateAllDisciplines' => $estimateAllDisciplines,
            'costProject' => $costProject,
            'isDetail' => $this->isDetail
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
        $styleHeader = 'A13:H14';
        if($this->isDetail) $styleHeader = 'A13:M14';
        $sheet->getStyle($styleHeader)->applyFromArray([
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

        $formatNumber = 'E15:I100';
        if($this->isDetail) $formatNumber = 'G15:M300';
        $sheet->getStyle($formatNumber)->getNumberFormat()->setFormatCode(NumberFormat::FORMAT_CURRENCY_IDR);
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
        $styleBackground = 'A1:H12';
        if($this->isDetail) $styleBackground = 'A1:M12';
        $sheet->getStyle($styleBackground)
            ->getFill()
            ->setFillType(Fill::FILL_SOLID)
            ->getStartColor()
            ->setRGB('FFFFFF');

        // Remove borders for each cell
        $styleBorder = 'A1:H11';
        if($this->isDetail) $styleBackground = 'A1:M11';
        $sheet->getStyle($styleBorder)
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
        $styleAlignData = 'H15:H100';
        if($this->isDetail) $styleAlignData = 'G15:M300';
        $sheet->getStyle($styleAlignData)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_RIGHT);

        for ($col = 'A'; $col <= $highestColumn; $col++) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // Enable text wrapping for all cells
        $sheet->getStyle('A1:' . $highestColumn . $sheet->getHighestRow())
            ->getAlignment()->setWrapText(true);
        $sheet->getColumnDimension('E')->setWidth(30);
        $sheet->getColumnDimension('D')->setWidth(40);
        $sheet->getColumnDimension('F')->setWidth(30);
        $sheet->getColumnDimension('G')->setWidth(30);
        $sheet->getColumnDimension('H')->setWidth(30);
        $sheet->getColumnDimension('I')->setWidth(30);
        if($this->isDetail){
            $sheet->getColumnDimension('J')->setWidth(30);
            $sheet->getColumnDimension('K')->setWidth(40);
            $sheet->getColumnDimension('L')->setWidth(30);
            $sheet->getColumnDimension('M')->setWidth(30);
        }

        // Enable text wrapping in column A
        $sheet->getStyle('E')->getAlignment()->setWrapText(true);

        $imagePath = public_path('assets/images/vale.jpg');

        $drawing = new Drawing();
        $drawing->setPath($imagePath);

        $drawing->setWidth(50);
        $drawing->setHeight(50);
        $styleImagePosition = 'H2';
        if($this->isDetail) $styleImagePosition = 'M2';
        $drawing->setCoordinates($styleImagePosition); // Starting cell for the image
        $drawing->setOffsetX(100); // Adjust the X offset to position the image within the cell
        $drawing->setOffsetY(10); // Adjust the Y offset to position the image within the cell
        $drawing->setWorksheet($sheet);
        $pageSetup = new PageSetup();

        // Set the page setup options
        $pageSetup->setFitToPage(true); // Fit the content to a single page
        $pageSetup->setFitToHeight(0); // Disable fitting to page height
        $pageSetup->setFitToWidth(1); // Fit the content to the page width
        $pageSetup->setOrientation(PageSetup::ORIENTATION_LANDSCAPE); // Set the page orientation to landscape
        $pageSetup->setPaperSize(PageSetup::PAPERSIZE_A3); // Set the paper size to A4
        $pageSetup->setScale(100); // Set the scaling factor (percentage) for the print view

        // Apply the PageSetup instance to the worksheet
        $sheet->setPageSetup($pageSetup);

    }
}