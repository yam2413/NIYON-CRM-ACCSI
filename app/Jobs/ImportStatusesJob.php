<?php

namespace App\Jobs;

use DB;
use Auth;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Groups;
use App\Models\TempUploads;
use App\Models\SystemLogs;
use App\Models\Statuses;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\HeadingRowImport;
use App\Imports\StatusesImport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ImportStatusesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $id, $group_id, $user_id, $path;
    public function __construct($id, $group_id, $user_id, $path)
    {
        $this->id            = $id;
        $this->group_id      = $group_id;
        $this->user_id       = $user_id;
        $this->path       = $path;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $group_id   = $this->group_id;
        $uniq_id    = $this->id;
        $user_id    = $this->user_id;
        $path       = $this->path;

        $file_path      = storage_path('app/'.$path);
        $import_status  = Excel::import(new StatusesImport($user_id, $group_id), $file_path);

    }
}
