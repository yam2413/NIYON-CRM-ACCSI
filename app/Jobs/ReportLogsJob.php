<?php

namespace App\Jobs;

use DB;
use Auth;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Groups;
use App\Models\ReportLogs;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\HeadingRowImport;
use App\Exports\CallStatusExport;
use App\Exports\SystemLogsExport;
use App\Exports\LeadsLogsExport;
use App\Exports\SummaryCallsExport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ReportLogsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $import_array, $type;
    public function __construct($import_array, $type)
    {
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
        $group_id   = $decode['group_id'];
        $status     = $decode['status'];
        $date       = $decode['date'];
        $collector  = $decode['collector'];

        switch ($this->type) {
            case 'call_status':
                $file_name  = 'call_status_'.$uniq_id.'.xlsx';
                $saveto     = 'reports/'.$file_name;
                $done = Excel::store(new CallStatusExport($date, $group_id, $collector, $status), $saveto, 'public');

                if($done){
                    $post_sync_status = array(
                      'file_path'     => $saveto,
                      'file_name'     => $file_name,
                      'status'        => 1,
                      'updated_at'     => Carbon::now(),
                    );
                            
                    ReportLogs::where('file_id', '=', $uniq_id)->update($post_sync_status);
                }
                
                break;

            case 'system_logs':

                $file_name  = 'system_logs_'.$uniq_id.'.xlsx';
                $saveto     = 'reports/'.$file_name;
                $done = Excel::store(new SystemLogsExport($date, $collector), $saveto, 'public');

                if($done){
                    $post_sync_status = array(
                      'file_path'     => $saveto,
                      'file_name'     => $file_name,
                      'status'        => 1,
                      'updated_at'     => Carbon::now(),
                    );
                            
                    ReportLogs::where('file_id', '=', $uniq_id)->update($post_sync_status);
                }
                
                break;

            case 'leads_logs':

                $file_name  = 'leads_logs_'.$uniq_id.'.xlsx';
                $saveto     = 'reports/'.$file_name;
                $done = Excel::store(new LeadsLogsExport($date, $collector), $saveto, 'public');

                if($done){
                    $post_sync_status = array(
                      'file_path'     => $saveto,
                      'file_name'     => $file_name,
                      'status'        => 1,
                      'updated_at'     => Carbon::now(),
                    );
                            
                    ReportLogs::where('file_id', '=', $uniq_id)->update($post_sync_status);
                }
                
                break;

            case 'summary_calls':

                $file_name  = 'summary_calls_'.$uniq_id.'.xlsx';
                $saveto     = 'reports/'.$file_name;
                $done = Excel::store(new SummaryCallsExport($date, $group_id, $collector), $saveto, 'public');

                if($done){
                    $post_sync_status = array(
                      'file_path'     => $saveto,
                      'file_name'     => $file_name,
                      'status'        => 1,
                      'updated_at'     => Carbon::now(),
                    );
                            
                    ReportLogs::where('file_id', '=', $uniq_id)->update($post_sync_status);
                }
                
                break;

            
        }
        

        
    }
}
