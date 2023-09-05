<?php

namespace App\Jobs;

use Auth;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Groups;
use App\Models\TempUploads;
use App\Models\FileUploadLogs;
use App\Models\CrmBorrowers;
use App\Models\CrmLeads;
use App\Models\CrmLogs;
use App\Models\CrmPtpHistories;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SyncPaidsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $file_id, $groups, $account_no, $paid_amount, $paid_date;
    public function __construct($file_id, $groups, $account_no, $paid_amount, $paid_date)
    {
        $this->file_id          = $file_id;
        $this->groups           = $groups;
        $this->account_no       = $account_no;
        $this->paid_amount      = $paid_amount;
        $this->paid_date        = $paid_date;
       
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $file_id          = $this->file_id;
        $groups           = $this->groups;
        $account_no       = $this->account_no;
        $paid_amount      = $this->paid_amount;
        $paid_date        = $this->paid_date;
        



       
        $records = TempUploads::orderBy('id','DESC')
        ->where('header','=','0')
        ->where('file_id','=',$file_id)
        ->get();

        foreach ($records as $key => $record) {

            

            $check_account_no   = CrmLeads::select('count(*) as allcount')
                                    ->where('account_number', '=', $record[$account_no])
                                    ->where('assign_group', '=', $groups)
                                    ->count();
            //Check if the account no. exist or not
            if($check_account_no == 0){
                $post_sync = array(
                    'error'      => 1,
                    'error_msg'  => 'Account No. not found or Account is not belong to the group',
                    'updated_at' => Carbon::now(),
                );
                            
                TempUploads::where('file_id', '=', $file_id)->where('id', '=', $record['id'])
                                ->update($post_sync);
                continue;
            }//End if the validation


                $update_leads = array(
                    'status'        => 4,
                    'payment_date'  => $record[$paid_date],
                    'ptp_amount'    => $record[$paid_amount],
                    'updated_at'    => Carbon::now(),
                );
                                
                CrmLeads::where('account_number', '=', $record[$account_no])->where('assign_group', '=', $groups)->where('deleted', '=', 0)->update($update_leads);

                $crm_leads       = CrmLeads::where('account_number', '=', $record[$account_no])->where('assign_group', '=', $groups)->where('deleted', '=', 0)->first();
                $crm_borrowers   = CrmBorrowers::where('profile_id', '=', $crm_leads->profile_id)->first();
                $uniq_id         = $crm_borrowers->profile_id;
            
                $log_txt = "'Updated account paid status";
                CrmLogs::saveLogs(0, $uniq_id, $crm_borrowers->full_name, $log_txt);

                $insert_ptp = array(
                    'payment_no'          => time(),
                    'profile_id'          => $uniq_id,
                    'leads_id'            => $crm_leads->id,
                    'assign_group'        => $groups,
                    'status'              => 4,
                    'payment_date'        => $record[$paid_date],
                    'payment_amount'      => $record[$paid_amount],
                    'remarks'             => '---',
                    'attempt'             => '---',
                    'place_call'          => '---',
                    'contact_type'        => '---',
                    'created_by'          => $record->user,
                    'created_at'          => Carbon::now(),
                    'updated_at'          => Carbon::now(),
                );
                CrmPtpHistories::insert($insert_ptp);


                $post_sync = array(
                    'success'       => 1,
                    'profile_id'    => $uniq_id,
                    'updated_at'    => Carbon::now(),
                );
                            
                TempUploads::where('file_id', '=', $file_id)->where('id', '=', $record['id'])
                                ->update($post_sync);


        }//End of foreach for list temp uploads


        $post_sync_status = array(
          'status'         => 2,
          'updated_at'     => Carbon::now(),
        );
                    
        FileUploadLogs::where('file_id', '=', $file_id)->update($post_sync_status);
    }
}
