<?php

namespace App\Imports;

use App\Models\Material;
use App\Models\MaterialCategory;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\RemembersRowNumber;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithUpserts;
use Maatwebsite\Excel\Events\AfterImport;

class MaterialImport implements ToModel, WithMapping, WithStartRow, WithBatchInserts,
    WithUpserts, WithChunkReading, WithEvents
{

    use RemembersRowNumber;
    use Importable;
    use RegistersEventListeners;
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    private $uniqueIdentifiers = [];
    private $sheetName;

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

    public function map($row): array {

        $category = MaterialCategory::where('description', $row[3])->first();
        $user = auth()->user()->id;

        return [
            'code' => $row[1],
            'tool_equipment_description' => $row[2] ?? '',
            'category_id' => optional($category)->id ?? null,
            'quantity' => $row[4] ?? '',
            'unit' => $row[5] ?? '',
            'rate' => $row[6] ?? '',
            'ref_material_number' => $row[7] ?? '',
            'remark' => $row[9] ?? '',
            'status' => 'DRAFT',
            'created_at' => now(),
            'updated_at' => now(),
            'created_by' => $user,
            'updated_by' => $user,
            'stock_code' => $row[10] ?? ''
        ];
    }

    public function model(array $row){
        try {
            if(isset($row['code'])){
                $uniqueValue = $row['code'];
                $data = [
                    'code' => $row['code'],
                    'tool_equipment_description' => $row['tool_equipment_description'] ?? '',
                    'category_id' => $row['category_id'],
                    'quantity' => $row['quantity'] ?? '',
                    'unit' => $row['unit'] ?? '',
                    'rate' => $row['rate'] ?? '',
                    'ref_material_number' => $row['ref_material_number'] ?? '',
                    'remark' => $row['remark'] ?? '',
                    'status' => 'DRAFT',
                    'created_at' => now(),
                    'updated_at' => now(),
                    'created_by' => $row['remark'],
                    'updated_by' => $row['remark'],
                    'stock_code' => $row['stock_code'] ?? ''
                ];

                $this->uniqueIdentifiers[] = $uniqueValue;
                return new Material($data);
            }
        } catch (\Exception $e) {
            return null;
        }
    }

    public static function afterImport(AfterImport $event){
        Log::info('AfterImport event fired');
        $importInstance = $event->getConcernable();
        Material::whereNotIn('code', $importInstance->uniqueIdentifiers)->delete();
    }
}
