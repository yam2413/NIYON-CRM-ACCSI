<?php

namespace App\Imports;

use DB;
use Auth;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Groups;
use App\Models\TempUploads;
use App\Models\FileUploadLogs;
use App\Models\CrmBorrowers;
use App\Models\CrmLeads;
use App\Models\CrmLogs;
use App\Models\CrmCallLogs;
use App\Models\CrmPtpHistories;
use App\Models\AddedCampaignsLeads;
use App\Models\AddedCampaignsAgents;
use App\Models\SystemLogs;
use App\Models\ImportLogs;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Readers\LaravelExcelReader;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;

class UpdateLeadsStatusImport implements ToModel, WithBatchInserts, WithChunkReading, WithStartRow, WithCalculatedFormulas
{
    /**
    * @param Collection $collection
    */
    public $user_id, $uniq_id, $group_id;

    public function __construct($user_id, $uniq_id, $group_id)
    {
        $this->uniq_id = $uniq_id;
        $this->user_id = $user_id;
        $this->group_id = $group_id;
    }


    public function model(array $row)
    {
        $uniq_id = $this->uniq_id;
        return new TempUploads([
                'user'          => $this->user_id,
                'upload_type'   => 'update_lead_status',
                'header'        => 0,
                'file_id'       => $uniq_id,
                'groups'        => $this->group_id,
                'data1'         => (isset($row[0]) ? $row[0]:'--'),
                'data2'         => (isset($row[1]) ? $row[1]:'--'),
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
