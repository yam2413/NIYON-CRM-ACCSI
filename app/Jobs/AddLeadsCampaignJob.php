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
use App\Models\CrmPtpHistories;
use App\Models\CrmCallLogs;
use App\Models\CrmLogs;
use App\Models\TempEmails;
use App\Models\TempSms;
use App\Models\CronActivities;
use App\Models\Campaigns;
use App\Models\AddedCampaignsLeads;
use App\Models\AddedCampaignsAgents;
use App\Models\SystemLogs;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AddLeadsCampaignJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $file_id, $user_id;
    public function __construct($file_id, $user_id)
    {
        $this->file_id = $file_id;
        $this->user_id = $user_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
         $get_campaigns = Campaigns::where('file_id','=',$this->file_id)->first();

         $groups = $get_campaigns->group;
         $campaign_name = $get_campaigns->campaign_name;


         $records = CrmLeads::select(
            [
                'crm_borrowers.full_name',
                'crm_leads.*',
            ]
         );
         $records->orderByRaw('crm_leads.priority <> 1');
         $records->leftjoin('crm_borrowers', 'crm_borrowers.profile_id', '=', 'crm_leads.profile_id');
         $records->where('crm_leads.assign_group','=' ,$groups);
         $records->where('crm_leads.deleted','=' ,0);
         //$records->where('crm_leads.status','!=' ,4);
         $records->whereRaw('crm_leads.id NOT IN (SELECT b.leads_id FROM added_campaigns_leads b WHERE b.file_id = "'.$this->file_id.'" )');
         $records = $records->get();

         foreach ($records as $record) {
             $insert_data = array(
                'file_id'           => $this->file_id,
                'leads_id'          => $record->id,
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now(),
            );
            AddedCampaignsLeads::insert($insert_data);
            CrmLogs::saveLogs( $this->user_id, $record->profile_id, $record->full_name, 'Account has been added in the '.$campaign_name.' campaign ');
         }

         $post_sync = array(
            'sync_all_leads'  => 0,    
        ); 
        Campaigns::where('file_id', '=', $this->file_id)->update($post_sync);

    }
}
