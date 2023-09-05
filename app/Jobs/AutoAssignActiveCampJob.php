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

class AutoAssignActiveCampJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $file_id;
    public function __construct($file_id)
    {
        $this->file_id = $file_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        
        $records = AddedCampaignsLeads::select(
                [       
                        'added_campaigns_leads.leads_id',
                        'added_campaigns_leads.file_id',
                        'added_campaigns_leads.dial',
                        'added_campaigns_leads.process',
                        'added_campaigns_leads.id',    
                        'crm_leads.assign_user',
                    ]
                );
        $records->leftjoin('crm_leads', 'crm_leads.id', '=', 'added_campaigns_leads.leads_id');
        $records->where('added_campaigns_leads.file_id', '=', $this->file_id);
        $records->where('added_campaigns_leads.dial', '!=', 1);
        $records->where('added_campaigns_leads.process', '!=', 1);
        $records->where('crm_leads.deleted', '=', 0);
        $records = $records->get();

        foreach ($records as $key => $record) {
            $post_sync = array(
                'collector_id'      => $record->assign_user,
                'updated_at'        => Carbon::now(),
            );
                
            AddedCampaignsLeads::where('id', '=', $record->id)->update($post_sync);

            $total_added_campaigns_agents    = AddedCampaignsAgents::select('count(*) as allcount')
                                                ->where('file_id', '=', $this->file_id)
                                                ->where('collector_id', '=', $record->assign_user)
                                                ->count();
            if($total_added_campaigns_agents == 0){

                $insert_data = array(
                    'file_id'           => $this->file_id,
                    'collector_id'      => $record->assign_user,
                    'created_at'        => Carbon::now(),
                    'updated_at'        => Carbon::now(),
                );
                AddedCampaignsAgents::insert($insert_data);

            }
            
        }


        $post_campaigns = array(
            'active_dialer'     => 1,
            'updated_at'        => Carbon::now(),
        );
                
        Campaigns::where('file_id', '=', $this->file_id)->update($post_campaigns);

    }
}
