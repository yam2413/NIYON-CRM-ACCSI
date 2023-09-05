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

class ActivateCampaignJob implements ShouldQueue
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
        
        $total_added_campaigns_leads    = AddedCampaignsLeads::select('count(*) as allcount')
                                                ->where('file_id', '=', $this->file_id)
                                                ->where('dial', '!=', 1)
                                                ->where('process', '!=', 1)
                                                ->count();

        $total_added_campaigns_agents    = AddedCampaignsAgents::select('count(*) as allcount')->where('file_id', '=', $this->file_id)->count();

        $total_take = round(($total_added_campaigns_leads/$total_added_campaigns_agents),0,PHP_ROUND_HALF_DOWN);
        
        $skip = 0;
        $array_id = [];
        $added_campaigns_agents         = AddedCampaignsAgents::where('file_id', '=', $this->file_id)->get();
        foreach ($added_campaigns_agents as $data_agents) {
            
            $added_campaigns_leads  = AddedCampaignsLeads::where('file_id', '=', $this->file_id)
                    ->where('dial', '!=', 1)
                    ->where('process', '!=', 1)
                    ->skip($skip)
                    ->take($total_take)
                    ->get();
            foreach ($added_campaigns_leads as $data_leads) {
                $post_sync = array(
                    'collector_id'      => $data_agents->collector_id,
                    'updated_at'        => Carbon::now(),
                );
                
                AddedCampaignsLeads::where('id', '=', $data_leads->id)->update($post_sync);
            }

            $skip = $skip+$total_take;
            $array_id[] = $data_agents->collector_id;
        }


        $total_left_campaigns_leads    = AddedCampaignsLeads::select('count(*) as allcount')
                                            ->where('file_id', '=', $this->file_id)
                                            ->where('collector_id', '=', 0)
                                            ->where('dial', '!=', 1)
                                            ->where('process', '!=', 1)
                                            ->count();
        if($total_left_campaigns_leads > 0){
            $rand_added_campaigns_leads  = AddedCampaignsLeads::where('file_id', '=', $this->file_id)
                    ->where('collector_id', '=', 0)
                    ->where('dial', '!=', 1)
                    ->where('process', '!=', 1)
                    ->get();
            foreach ($rand_added_campaigns_leads as $data_leads) {
                //$explode_agent = explode(',', $added_campaigns_agents);
                // $random_agent = array($explode_agent);
                $random_id = array_rand($array_id,3);

                $post_sync_rand = array(
                    'collector_id'      => $array_id[$random_id[0]],
                    'updated_at'        => Carbon::now(),
                );
                
                AddedCampaignsLeads::where('id', '=', $data_leads->id)->update($post_sync_rand);
            }
        }


        $post_campaigns = array(
            'active_dialer'     => 1,
            'updated_at'        => Carbon::now(),
        );
                
        Campaigns::where('file_id', '=', $this->file_id)->update($post_campaigns);
    }
}
