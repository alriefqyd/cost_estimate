<?php

namespace App\Imports;

use App\Models\MaterialCategory;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\RemembersRowNumber;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithUpserts;
use Maatwebsite\Excel\Events\AfterImport;

class MaterialCategoryImport implements ToModel, WithMapping, WithStartRow, WithBatchInserts, WithUpserts, WithChunkReading
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    use RemembersRowNumber;
    use Importable;
    use RegistersEventListeners;

    private $sheetName;


    private $uniqueIdentifiers = [];

    public function batchSize(): int
    {
        return 500;
    }

    public function chunkSize(): int
    {
        return 500;
    }

    public function startRow(): int
    {
        return 7;
    }

    public function uniqueBy()
    {
       return 'code';
    }

    public function model(array $row)
    {
        try {
            if (isset($row['code'])) {
                $uniqueValue = $row['code'];
                $data = [
                    'code' => $row['code'],
                    'description' => $row['description'] ?? '',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];

                $this->uniqueIdentifiers[] = $uniqueValue;
                return new MaterialCategory($data);
            }
        } catch (\Exception $e) {
            return null;
        }
    }

    public function map($row): array {
        return [
            'code' => $row[1],
            'description' => $row[2] ?? '',
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }

    public static function afterImport(AfterImport $event){
        Log::info('AfterImport event fired');
        $importInstance = $event->getConcernable();
        MaterialCategory::whereNotIn('code', $importInstance->uniqueIdentifiers)->delete();
    }
}
