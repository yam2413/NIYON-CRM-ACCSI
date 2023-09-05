<?php

namespace App\Jobs;

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
use App\Models\SystemLogs;
use App\Models\PulloutLogs;
use App\Models\ImportLogs;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\HeadingRowImport;
use App\Imports\UpdateLeadsStatusImport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ImportLeadsStatusJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $import_array;
    public function __construct($import_array)
    {
        $this->import_array  = $import_array;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $decode     = json_decode($this->import_array, true);
        $uniq_id    = $decode['uniq_id'];
        $user_id    = $decode['user_id'];
        $group_id   = $decode['group_id'];

        $file_path      = storage_path('app/'.$decode['import_path']);
        $import_status  = Excel::import(new UpdateLeadsStatusImport($user_id, $uniq_id, $group_id), $file_path);

        if($import_status){
            $post_sync_status = array(
                'status'        => 1,
                'updated_at'    => Carbon::now(),
            );
                              
            ImportLogs::where('file_id', '=', $uniq_id)->update($post_sync_status);
        }
    }
}
