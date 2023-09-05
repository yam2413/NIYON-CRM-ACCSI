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
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class BulkUpdateStatusJob implements ShouldQueue
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
        $decode         = json_decode($this->import_array, true);
        $user_id        = $decode['user_id'];
        $new_status     = $decode['new_status'];
        $group_id       = $decode['group_id'];
        $date           = $decode['date'];
        $collector      = $decode['collector'];
        $old_status     = $decode['old_status'];
        $file_id        = $decode['file_id'];

        $new_dates         = explode('|', str_replace(' ', '', $date)); 
        $start_date        = (isset($new_dates[0])) ? $new_dates[0]:date('Y-m-d');
        $end_date          = (isset($new_dates[1])) ? $new_dates[1]:date('Y-m-d');

        

         $records = CrmLeads::select(
            [
                'crm_borrowers.full_name',
                'crm_leads.*',
            ]
         );
         $records->leftjoin('crm_borrowers', 'crm_borrowers.profile_id', '=', 'crm_leads.profile_id');
         $records->where('crm_leads.assign_group','=' ,$group_id);

         if($file_id != '0'){
            $records->where('temp_uploads.file_id','=' ,$file_id);
         }

         if($collector != '0'){
            $records->where('crm_leads.assign_user','=' ,$collector);
         }
         if($old_status != '0'){
            $records->where('crm_leads.status','=' ,$old_status);
         }
         if($date != '0'){
            $records->whereRaw('DATE(crm_leads.status_updated) BETWEEN "'.$start_date.'" AND "'.$end_date.'" ');
         }
         
         $records->where('crm_leads.deleted','=' ,0);

         switch ($this->type) {
            case 'selected':
                $records->whereRaw('crm_leads.id IN('.$this->id.') ');
                break;
            
            case 'all':
                // code...
                break;

            case 'import':
                $records->leftjoin('temp_uploads', 'temp_uploads.data1', '=', 'crm_leads.account_number');
                break;

                
        }
         $records = $records->get();
         foreach ($records as $key => $record) {
             
             $post_sync = array(
                'status'              => $new_status,
                'status_updated'      => date('Y-m-d H:i'),
                'updated_at'          => Carbon::now(),
             );
            
             CrmLeads::where('id', '=', $record->id)->update($post_sync);
             CrmLogs::saveLogs($user_id, $record->profile_id, $record->full_name, 'Status has been updated from '.$record->status.' to '.$new_status.' ');
         }

         SystemLogs::saveLogs($user_id, 'Bulk updated status to '.$new_status.' has been done.');
    }
}
