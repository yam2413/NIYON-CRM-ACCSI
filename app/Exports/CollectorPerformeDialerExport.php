<?php

namespace App\Exports;

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
use App\Models\CampaignLogs;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\FromCollection;

class CollectorPerformeDialerExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public $start_date, $end_date, $file_id;
    public function __construct($start_date, $end_date, $file_id)
    {
        $this->start_date = $start_date;
        $this->end_date   = $end_date;
        $this->file_id    = $file_id;
    }

    public function collection()
    {
    	$start_date        = (isset($this->start_date)) ? $this->start_date.' 00:00:00':date('Y-m-d').' 00:00:00';
     	$end_date          = (isset($this->end_date)) ? $this->end_date.' 23:00:00':date('Y-m-d').' 23:00:00';

        $records = AddedCampaignsAgents::select(
            [
            	'users.name',
                'added_campaigns_agents.*',
            ]
        );

     $date_query = "AND crm_ptp_histories.created_at BETWEEN '".$start_date."' AND '".$end_date."' ";
     $date_query_3 = "AND c.created_at BETWEEN '".$start_date."' AND '".$end_date."' ";
     $date_query_1 = "AND a.dial_date BETWEEN '".$start_date."' AND '".$end_date."' ";
     $date_query_2 = "AND b.process_date BETWEEN '".$start_date."' AND '".$end_date."' ";

     $sql1 = "(select count(*) from added_campaigns_leads a where a.collector_id = added_campaigns_agents.collector_id AND a.dial = 1 AND a.file_id =  added_campaigns_agents.file_id ".$date_query_1.") as total_dial";

     $sql2 = "(select count(*) from added_campaigns_leads b where b.collector_id = added_campaigns_agents.collector_id AND b.process = 1 AND b.file_id =  added_campaigns_agents.file_id ".$date_query_2.") as total_process";

     $sql3 = "(select count(*) from campaign_logs c where c.user = added_campaigns_agents.collector_id AND c.log_type = 'pause' AND c.file_id =  added_campaigns_agents.file_id ".$date_query_3.") as total_pause";

     $sql4 = "(select count(*) from added_campaigns_leads d LEFT JOIN crm_ptp_histories ON crm_ptp_histories.leads_id = d.leads_id where d.collector_id = added_campaigns_agents.collector_id AND crm_ptp_histories.call_status = 'Answered' AND d.file_id =  added_campaigns_agents.file_id ".$date_query." ) as total_answered";

     $sql5 = "(select count(*) from added_campaigns_leads e LEFT JOIN crm_ptp_histories ON crm_ptp_histories.leads_id = e.leads_id where e.collector_id = added_campaigns_agents.collector_id AND crm_ptp_histories.call_status = 'Busy' AND e.file_id =  added_campaigns_agents.file_id ".$date_query.") as total_busy";

     $sql6 = "(select count(*) from added_campaigns_leads f LEFT JOIN crm_ptp_histories ON crm_ptp_histories.leads_id = f.leads_id where f.collector_id = added_campaigns_agents.collector_id AND crm_ptp_histories.call_status = 'Not Getting Service' AND f.file_id =  added_campaigns_agents.file_id ".$date_query.") as total_not_getting_service";

     $sql7 = "(select count(*) from added_campaigns_leads g LEFT JOIN crm_ptp_histories ON crm_ptp_histories.leads_id = g.leads_id where g.collector_id = added_campaigns_agents.collector_id AND crm_ptp_histories.call_status = 'Just Ringing' AND g.file_id =  added_campaigns_agents.file_id ".$date_query.") as total_just_ringing";

     $sql8 = "(select count(*) from added_campaigns_leads h LEFT JOIN crm_ptp_histories ON crm_ptp_histories.leads_id = h.leads_id where h.collector_id = added_campaigns_agents.collector_id AND crm_ptp_histories.call_status = 'Hang up/Cannot be reached' AND h.file_id =  added_campaigns_agents.file_id ".$date_query.") as total_hangup";

     $sql9 = "(select count(*) from added_campaigns_leads i where i.collector_id = added_campaigns_agents.collector_id AND i.file_id =  added_campaigns_agents.file_id) as total_assign";

     $records = AddedCampaignsAgents::selectRaw('added_campaigns_agents.*, users.name, '.$sql1.', '.$sql2.', '.$sql3.', '.$sql4.', '.$sql5.', '.$sql6.', '.$sql7.', '.$sql8.', '.$sql9.' ');
     $records->orderBy('total_dial','DESC');
     $records->leftjoin('users', 'users.id', '=', 'added_campaigns_agents.collector_id');
     $records->where('added_campaigns_agents.file_id','=' , $this->file_id);
     $records = $records->get();
     $data = [];

	     foreach ($records as $key => $record) {
	     	$id 						= $record->id;
	        $name 						= $record->name;
	        $total_dial 				= $record->total_dial;
	        $total_process 				= $record->total_process;
	        $total_pause 				= $record->total_pause;
	        $total_answered 			= $record->total_answered;
	        $total_busy 				= $record->total_busy;
	        $total_not_getting_service 	= $record->total_not_getting_service;
	        $total_just_ringing 		= $record->total_just_ringing;
	        $total_hangup 				= $record->total_hangup;
	        $total_assign 				= $record->total_assign;

	     	$data[] = array(
	     	  "name" 						=> $name,
	          "total_dial" 					=> $total_dial,
	          "total_process" 				=> $total_process,
	          "total_pause" 				=> $total_pause,
	          "total_answered" 				=> $total_answered,
	          "total_busy" 					=> $total_busy,
	          "total_not_getting_service" 	=> $total_not_getting_service,
	          "total_just_ringing" 			=> $total_just_ringing,
	          "total_hangup" 				=> $total_hangup,
	          "total_assign" 				=> $total_assign,
	     	);

	     }


	     return collect($data);
    }

    public function headings(): array
    {
        return ['Name', 'Total Assign Leads', 'Total Dial', 'Total Process', 'Total Pause', 'Answered', 'Busy', 'Not Getting Service', 'Just Ringing', 'Hang up/Cannot be reached'];
        
    }

    public function map($data): array
    {
    	$name 							= $data['name']; 
        $total_assign 					= $data['total_assign']; 
        $total_dial 					= $data['total_dial'];
        $total_process 					= $data['total_process'];
        $total_pause 					= $data['total_pause'];
        $total_answered 				= $data['total_answered'];
        $total_busy   					= $data['total_busy'];
        $total_not_getting_service    	= $data['total_not_getting_service'];
        $total_just_ringing    			= $data['total_just_ringing'];
        $total_hangup    				= $data['total_hangup'];

		return [$name, $total_assign, $total_dial, $total_process, $total_pause, $total_answered, $total_busy, $total_not_getting_service, $total_just_ringing, $total_hangup];
    	
    }
}
