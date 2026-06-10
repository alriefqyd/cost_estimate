<?php

namespace App\Exports;

use App\Models\WorkItem;
use Generator;
use Maatwebsite\Excel\Concerns\FromGenerator;
use Maatwebsite\Excel\Concerns\WithColumnFormatting;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\NumberFormat;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

/**
 * Column layout (A–V, 22 columns):
 *  A–E  : Work item info (Code, Description, Type, Volume, Unit)
 *  F–J  : Manpower      (Description, Unit, Coef, Rate, Amount)
 *  K–O  : Tools & Equip (Description, Unit, Qty, Unit Price, Amount)
 *  P–T  : Material      (Description, Unit, Qty, Unit Price, Amount)
 *  U–V  : Status, Created By
 */
class WorkItemListExport implements FromGenerator, WithStyles, WithColumnWidths, WithColumnFormatting
{
    /** Row numbers of work-item summary rows, used for styling. */
    private array $workItemRows = [];
    private int   $currentRow  = 6; // 6 header rows already counted

    // ─────────────────────────────────────────────────────────────────────────
    // Data
    // ─────────────────────────────────────────────────────────────────────────

    public function generator(): Generator
    {
        // Rows 1–4: company / document info
        yield ['PT VALE INDONESIA, TBK'];
        yield ['DEPARTMENT ENGINEERING AND CONSTRUCTION - ENGINEERING SERVICE'];
        yield [''];  // empty spacer row — must not be [] or flatMap silently drops it
        yield ['PTVI STANDARD DETAILED DATABASE COST ESTIMATE'];

        // Row 5: section group labels
        yield [
            'WORK ITEM INFO', '', '', '', '',
            'MANPOWER',       '', '', '', '',
            'TOOLS & EQUIPMENT', '', '', '', '',
            'MATERIAL',       '', '', '', '',
            'STATUS', 'CREATED BY',
        ];

        // Row 6: sub-column labels
        yield [
            'Code', 'Description', 'Type', 'Volume', 'Unit',
            'Description', 'Unit', 'Coef', 'Rate',       'Amount',
            'Description', 'Unit', 'Qty',  'Unit Price', 'Amount',
            'Description', 'Unit', 'Qty',  'Unit Price', 'Amount',
            '', '',
        ];

        // Data – loaded 100 records at a time, never fully in memory
        // Creator name and type title resolved via JOIN to avoid hasOne chain issues
        foreach (
            WorkItem::select([
                'work_items.id',
                'work_items.code',
                'work_items.description',
                'work_items.volume',
                'work_items.unit',
                'work_items.status',
                'profiles.full_name as creator_full_name',
                'work_item_types.title as type_title',
            ])
            ->leftJoin('users as wi_creator', 'work_items.created_by', '=', 'wi_creator.id')
            ->leftJoin('profiles', 'wi_creator.id', '=', 'profiles.user_id')
            ->leftJoin('work_item_types', 'work_items.work_item_type_id', '=', 'work_item_types.id')
            ->with(['manPowers', 'equipmentTools', 'materials'])
            ->lazyById(100, 'work_items.id', 'id') as $workItem
        ) {
            $manPowers  = $workItem->manPowers;
            $equipments = $workItem->equipmentTools;
            $materials  = $workItem->materials;
            $maxRows    = max($manPowers->count(), $equipments->count(), $materials->count(), 0);

            // Track row number for styling later
            $this->workItemRows[] = ++$this->currentRow;

            // Work item summary row
            yield [
                $workItem->code,
                $workItem->description,
                $workItem->type_title ?? '',
                $workItem->volume,
                $workItem->unit,
                '', '', '', '', '',
                '', '', '', '', '',
                '', '', '', '', '',
                $workItem->status,
                $workItem->creator_full_name ?? '',
            ];

            // Detail rows: manpower / equipment / material placed side-by-side
            for ($i = 0; $i < $maxRows; $i++) {
                $this->currentRow++;

                $mp  = $manPowers[$i]  ?? null;
                $et  = $equipments[$i] ?? null;
                $mat = $materials[$i]  ?? null;

                yield [
                    '', '', '', '', '',
                    // Manpower (F–J)
                    $mp?->title ?? '',
                    $mp?->pivot->labor_unit ?? '',
                    $mp ? str_replace(',', '.', $mp->pivot->labor_coefisient) : '',
                    $mp?->overall_rate_hourly ?? '',
                    $mp ? round(
                        (float) str_replace(',', '.', $mp->pivot->labor_coefisient) * ($mp->overall_rate_hourly ?? 0),
                        2
                    ) : '',
                    // Tools & Equipment (K–O)
                    $et?->description ?? '',
                    $et?->pivot->unit ?? '',
                    $et?->pivot->quantity ?? '',
                    $et?->pivot->unit_price ?? '',
                    $et?->pivot->amount ?? '',
                    // Material (P–T)
                    $mat?->tool_equipment_description ?? '',
                    $mat?->unit ?? '',
                    $mat?->pivot->quantity ?? '',
                    $mat?->rate ?? '',
                    $mat?->pivot->amount ?? '',
                    '', '',
                ];
            }
        }
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Styling
    // ─────────────────────────────────────────────────────────────────────────

    public function styles(Worksheet $sheet): void
    {
        $lastRow = $sheet->getHighestRow();

        // ── Rows 1–4: company info ────────────────────────────
        foreach (['A1:V1', 'A2:V2', 'A4:V4'] as $range) {
            $sheet->mergeCells($range);
        }
        $sheet->getStyle('A1:A2')->applyFromArray(['font' => ['bold' => true, 'size' => 11]]);
        $sheet->getStyle('A4')->applyFromArray([
            'font'      => ['bold' => true, 'size' => 11],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical'   => Alignment::VERTICAL_CENTER,
            ],
        ]);

        // ── Rows 5-6: column headers ──────────────────────────
        // Section merges (row 5 groups + row 6 rowspan for U/V)
        $sheet->mergeCells('A5:E5');
        $sheet->mergeCells('F5:J5');
        $sheet->mergeCells('K5:O5');
        $sheet->mergeCells('P5:T5');
        $sheet->mergeCells('U5:U6');
        $sheet->mergeCells('V5:V6');

        // Helper closure: section header style
        $sectionStyle = fn(string $bgArgb, bool $darkText = false): array => [
            'font'      => [
                'bold'  => true,
                'color' => ['argb' => $darkText ? 'FF000000' : 'FFFFFFFF'],
            ],
            'fill'      => [
                'fillType'   => Fill::FILL_SOLID,
                'startColor' => ['argb' => $bgArgb],
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical'   => Alignment::VERTICAL_CENTER,
            ],
            'borders'   => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color'       => ['argb' => 'FF000000'],
                ],
            ],
        ];

        $sheet->getStyle('A5:E6')->applyFromArray($sectionStyle('FF2E75B6'));         // blue  – work item
        $sheet->getStyle('F5:J6')->applyFromArray($sectionStyle('FFED7D31'));         // orange – manpower
        $sheet->getStyle('K5:O6')->applyFromArray($sectionStyle('FF548235'));         // green  – tools & equipment
        $sheet->getStyle('P5:T6')->applyFromArray($sectionStyle('FFFFC000', true));  // gold   – material
        $sheet->getStyle('U5:V6')->applyFromArray($sectionStyle('FF2E75B6'));         // blue   – status / created by

        // ── Data rows ─────────────────────────────────────────
        if ($lastRow >= 7) {
            // Thin gray grid for all data cells
            $sheet->getStyle("A7:V{$lastRow}")->applyFromArray([
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color'       => ['argb' => 'FFBFBFBF'],
                    ],
                ],
            ]);

            // Highlight work-item summary rows: light-gray fill + bold
            foreach ($this->workItemRows as $row) {
                $sheet->getStyle("A{$row}:V{$row}")->applyFromArray([
                    'fill' => [
                        'fillType'   => Fill::FILL_SOLID,
                        'startColor' => ['argb' => 'FFD9D9D9'],
                    ],
                    'font' => ['bold' => true],
                ]);
            }
        }

        // Text wrap for description-heavy columns
        foreach (['B', 'F', 'K', 'P'] as $col) {
            $sheet->getStyle($col)->getAlignment()->setWrapText(true);
        }
    }

    // ─────────────────────────────────────────────────────────────────────────
    // Column config
    // ─────────────────────────────────────────────────────────────────────────

    public function columnWidths(): array
    {
        return [
            'A' => 15,  // Code
            'B' => 50,  // Work Description
            'C' => 25,  // Work Item Type
            'D' => 10,  // Volume
            'E' => 10,  // Unit
            'F' => 35,  // MP Description
            'G' => 10,  // MP Unit
            'H' => 10,  // MP Coef
            'I' => 20,  // MP Rate
            'J' => 20,  // MP Amount
            'K' => 35,  // ET Description
            'L' => 10,  // ET Unit
            'M' => 10,  // ET Qty
            'N' => 20,  // ET Unit Price
            'O' => 20,  // ET Amount
            'P' => 35,  // Mat Description
            'Q' => 10,  // Mat Unit
            'R' => 10,  // Mat Qty
            'S' => 20,  // Mat Unit Price
            'T' => 20,  // Mat Amount
            'U' => 15,  // Status
            'V' => 25,  // Created By
        ];
    }

    public function columnFormats(): array
    {
        return [
            'I' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,  // MP Rate
            'J' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,  // MP Amount
            'N' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,  // ET Unit Price
            'O' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,  // ET Amount
            'S' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,  // Mat Unit Price
            'T' => NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1,  // Mat Amount
        ];
    }
}
