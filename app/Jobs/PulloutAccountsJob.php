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
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\HeadingRowImport;
use App\Exports\PulloutAccountsExport;
use App\Imports\PulloutAccountsImport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class PulloutAccountsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $id, $import_array, $type;
    public function __construct($id, $import_array, $type)
    {
        $this->id            = $id;
        $this->import_array  = $import_array;
        $this->type          = $type;
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
        

        switch ($this->type) {
            case 'manual':
                $group_id   = $decode['group_id'];
                $file_name  = 'pullout_accounts_'.$uniq_id.'.xlsx';
                $saveto     = 'pullout/'.$file_name;
                $done = Excel::store(new PulloutAccountsExport($this->id, $user_id, $uniq_id, $group_id, 'manual'), $saveto, 'public');

                if($done){
                    $post_sync_status = array(
                      'file_path'     => $saveto,
                      'file_name'     => $file_name,
                      'status'        => 1,
                      'updated_at'     => Carbon::now(),
                    );
                            
                    PulloutLogs::where('file_id', '=', $uniq_id)->update($post_sync_status);
                }
                
                break;

            case 'all':
                $group_id   = $decode['group_id'];
                $file_name  = 'pullout_accounts_'.$uniq_id.'.xlsx';
                $saveto     = 'pullout/'.$file_name;
                $done = Excel::store(new PulloutAccountsExport($this->id, $user_id, $uniq_id, $group_id, 'all'), $saveto, 'public');

                if($done){
                    $post_sync_status = array(
                      'file_path'     => $saveto,
                      'file_name'     => $file_name,
                      'status'        => 1,
                      'updated_at'     => Carbon::now(),
                    );
                            
                    PulloutLogs::where('file_id', '=', $uniq_id)->update($post_sync_status);
                }
                
                break;
            
            case 'import':
                $group_id   = $decode['group_id'];
                $file_path  = storage_path('app/'.$decode['import_path']);
                $import_status = Excel::import(new PulloutAccountsImport($user_id, $uniq_id), $file_path);

                if($import_status){

                  $file_name  = 'pullout_accounts_'.$uniq_id.'.xlsx';
                  $saveto     = 'pullout/'.$file_name;
                  $done = Excel::store(new PulloutAccountsExport('', $user_id, $uniq_id, $group_id, 'import'), $saveto, 'public');

                  if($done){
                      $post_sync_status = array(
                        'file_path'     => $saveto,
                        'file_name'     => $file_name,
                        'status'        => 1,
                        'updated_at'     => Carbon::now(),
                      );
                              
                      PulloutLogs::where('file_id', '=', $uniq_id)->update($post_sync_status);
                  }
                }
                break;
        }
        

        
    }
}
