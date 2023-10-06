<?php

namespace App\Imports;

use App\Models\ManPower;
use Illuminate\Support\Facades\Log;
use Maatwebsite\Excel\Concerns\Importable;
use Maatwebsite\Excel\Concerns\RegistersEventListeners;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithProgressBar;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Maatwebsite\Excel\Concerns\WithUpserts;
use Maatwebsite\Excel\Concerns\RemembersRowNumber;
use Maatwebsite\Excel\Events\AfterImport;

class ManPowerImport implements ToModel, WithMapping ,WithStartRow,
    WithBatchInserts, WithUpserts, WithChunkReading, WithProgressBar,
    WithEvents
{
    use RemembersRowNumber;
    use Importable;
    use RegistersEventListeners;

    private $uniqueIdentifiers = [];
    public function startRow(): int
    {
        return 7; // Start row to read data
    }

    public function batchSize(): int
    {
        return 500; // Set the batch size as needed
    }

    public function uniqueBy()
    {
        return 'code'; // Specify the unique column for upsert
    }

    public function map($row): array {
        return [
            'code' => $row[1],
            'skill_level' => $this->getValueToKey($row[2]) ?? '',
            'title' => $row[3] ?? '',
            'basic_rate_month' => $row[4] ?? '',
            'basic_rate_hour' => $row[5] ?? '',
            'general_allowance' => $row[6] ?? '',
            'bpjs' => $row[7] ?? '',
            'bpjs_kesehatan' => $row[8] ?? '',
            'thr' => $row[9] ?? '',
            'public_holiday' => $row[10] ?? '',
            'leave' => $row[11] ?? '',
            'pesangon' => $row[12] ?? '',
            'asuransi' => $row[13] ?? '',
            'safety' => $row[14] ?? '',
            'total_benefit_hourly' => $row[15] ?? '',
            'overall_rate_hourly' => $row[16] ?? '',
            'monthly' => $row[17] ?? '',
            'created_by' => auth()->user()->id,
            'updated_by' => auth()->user()->id,
            'status' => ManPower::DRAFT
        ];
    }


    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        try {
            if (isset($row['code'])) {
                $uniqueValue = $row['code'];
                $data = [
                    'code' => $row['code'],
                    'skill_level' => $row['skill_level'] ?? '',
                    'title' => $row['title'] ?? '',
                    'basic_rate_month' => $row['basic_rate_month'] ?? '',
                    'basic_rate_hour' => $row['basic_rate_hour'] ?? '',
                    'general_allowance' => $row['general_allowance'] ?? '',
                    'bpjs' => $row['bpjs'] ?? '',
                    'bpjs_kesehatan' => $row['bpjs_kesehatan'] ?? '',
                    'thr' => $row['thr'] ?? '',
                    'public_holiday' => $row['public_holiday'] ?? '',
                    'leave' => $row['leave'] ?? '',
                    'pesangon' => $row['pesangon'] ?? '',
                    'asuransi' => $row['asuransi'] ?? '',
                    'safety' => $row['safety'] ?? '',
                    'total_benefit_hourly' => $row['total_benefit_hourly'] ?? '',
                    'overall_rate_hourly' => $row['overall_rate_hourly'] ?? '',
                    'monthly' => $row['monthly'] ?? '',
                    'created_by' => auth()->user()->id,
                    'updated_by' => auth()->user()->id,
                    'status' => $row['status']
                ];

                $this->uniqueIdentifiers[] = $uniqueValue;
                return new ManPower($data);
            }
        } catch (\Exception $e) {
            return null;
        }

    }

    public static function afterImport(AfterImport $event){
        Log::info('AfterImport event fired');
        $importInstance = $event->getConcernable();
        ManPower::whereNotIn('code', $importInstance->uniqueIdentifiers)->delete();
    }

    public function convertToDecimal($val){
        if(!$val) return '';
        $value = str_replace('.','',$val);
        $value = str_replace(',','.',$value);
        return $value;
    }

    function generateRandomString($length = 10) {
        $bytes = random_bytes($length);
        return bin2hex($bytes);
    }

    function getValueToKey($value) {
        $skillLevelMapping = [
            'Skilled' => 'skilled',
            'Semi Skilled' => 'semi_skilled',
            'Un Skilled' => 'unskilled',
        ];

        return $skillLevelMapping[$value] ?? null;
    }

    public function chunkSize(): int
    {
        return 500;
    }
}
