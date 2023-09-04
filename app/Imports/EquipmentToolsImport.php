<?php

namespace App\Imports;

use App\Models\EquipmentTools;
use App\Models\EquipmentToolsCategory;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\RemembersRowNumber;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithUpserts;

class EquipmentToolsImport implements ToModel, WithMapping ,WithStartRow,
    WithBatchInserts, WithUpserts, WithChunkReading
{
    use RemembersRowNumber;
    use Importable;

    public function startRow(): int
    {
        return 7;
    }

    public function batchSize(): int
    {
        return 500;
    }

    public function uniqueBy()
    {
        return 'code'; // Specify the unique column for upsert
    }

    public function map($row): array
    {
        $desc = $row[3] ?? null;
        $category = EquipmentToolsCategory::where('description', $desc)->first();

        return [
            'code' => $row[1],
            'description' => $row[2] ?? null,
            'category_id' => optional($category)->id ?? null,
            'quantity' => $row[4] ?? '',
            'unit' => $row[5] ?? '',
            'local_rate' => $row[6] ?? '',
            'national_rate' => $row[7] ?? '',
            'remark' => $row[9] ?? ''
        ];
    }

    public function model(array $row)
    {
        try {
            DB::beginTransaction();
            if(isset($row['code'])){
                $data = [
                    'code' => $row['code'],
                    'description' => $row['description'],
                    'category_id' => $row['category_id'] ?? null, // Handle the key properly
                    'quantity' => $row['quantity'],
                    'unit' => $row['unit'],
                    'local_rate' => $row['local_rate'],
                    'national_rate' => $row['national_rate'],
                    'remark' => $row['remark'],
                    'created_by' => auth()->user()->id,
                    'updated_by' => auth()->user()->id,
                    'status' => EquipmentTools::REVIEWED
                ];

                DB::commit();
                return new EquipmentTools($data);
            }
        } catch (\Exception $e) {
            DB::rollback();
            return null;
        }
    }

    public function chunkSize(): int
    {
        return 500;
    }


}
