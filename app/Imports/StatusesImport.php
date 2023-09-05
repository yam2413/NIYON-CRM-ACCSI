<?php

namespace App\Imports;

use App\Models\Statuses;
use App\Models\SystemLogs;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Readers\LaravelExcelReader;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;

class StatusesImport implements ToModel, WithBatchInserts, WithChunkReading, WithStartRow, WithCalculatedFormulas
{
    /**
    * @param Collection $collection
    */
    public $user_id, $group_id;

    public function __construct($user_id, $group_id)
    {
        $this->group_id = $group_id;
        $this->user_id  = $user_id;
    }


    public function model(array $row)
    {
        return new Statuses([
                'value'         => 0,
                'status_name'   => (isset($row[0]) ? $row[0]:'--'),
                'group'         => $this->group_id,
                'description'   => (isset($row[1]) ? $row[1]:'--'),
                'added_by'      => $this->user_id,
        ]);



    }

    public function startRow(): int
    {
        return 2;
    }

    public function batchSize(): int
    {
        return 500;
    }

    public function chunkSize(): int
    {
        return 500;
    }
}
