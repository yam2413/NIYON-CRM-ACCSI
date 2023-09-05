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

class CallLogsSummaryDialerExport implements FromCollection, WithHeadings, WithMapping
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
        // Fetch records
        $start_date        = (isset($this->start_date)) ? $this->start_date.' 00:00:00':date('Y-m-d').' 00:00:00';
     	$end_date          = (isset($this->end_date)) ? $this->end_date.' 23:00:00':date('Y-m-d').' 23:00:00';
	     $records = CrmCallLogs::select(
	            [
	            	'crm_call_logs.*',
	                'crm_borrowers.full_name',
	                'crm_leads.status',
	                'crm_leads.assign_group',
	                'crm_ptp_histories.call_status',
	            ]
	        );
	     $records->orderBy('crm_call_logs.created_at','DESC');
	     $records->leftjoin('added_campaigns_leads', 'added_campaigns_leads.leads_id', '=', 'crm_call_logs.leads_id');
	     $records->leftjoin('crm_leads', 'crm_leads.id', '=', 'crm_call_logs.leads_id');
	     $records->leftjoin('crm_ptp_histories', 'crm_ptp_histories.leads_id', '=', 'crm_call_logs.leads_id');
	     $records->leftjoin('crm_borrowers', 'crm_borrowers.profile_id', '=', 'crm_leads.profile_id');
	     $records->where('crm_leads.deleted','=' ,0);
	     $records->whereBetween('crm_call_logs.created_at', [$start_date, $end_date]);
	     $records->where('added_campaigns_leads.file_id','=' , $this->file_id);
	     $records = $records->get();
	     $data = [];

	     foreach ($records as $key => $record) {
	     	$id 			= $record->id;
        $call_id 		= $record->call_id;
        $profile_id 	= $record->profile_id;
        $call_by 		= User::getName($record->call_by);
        $contact_no 	= $record->contact_no;
        $extension 		= $record->extension;
        $call_id 		= $record->call_id;
        $full_name 		= $record->full_name;
        $status 		= $record->status;
        $agent_status 	= $record->call_status;
        $assign_group 	= Groups::usersGroup($record->assign_group);
        $created_at 	= $record->created_at->diffForHumans();

        switch ($status) {
        	case '1':
        		$status_label = 'PTP';
        		break;

        	case '2':
        		$status_label = 'BPTP';
        		break;

        	case '3':
        		$status_label = 'BP';
        		break;

          	case '4':
            	$status_label = 'Paid';
            	break;
        	
        	default:
        		$status_label = 'New';
        		break;
        }

        $cdr_exist = DB::connection('mysql2')->table('cdr')->where('cnam','=',$call_id)->first();

        if($cdr_exist){
			$last_app = $cdr_exist->lastapp;
        }else{
	        $action_btn   = '';
	        $last_app 	  = '';
        }

	     	$data[] = array(
	     	  "full_name" 		=> $full_name,
	     	  "status" 			=> $status_label,
	     	  "call_status" 	=> $last_app,
	     	  "contact_no" 		=> $contact_no,
	          "call_by" 		=> $call_by,
	          "agent_status" 	=> $agent_status,
	          "created_at" 		=> $created_at,
	     	);

	     }


	     return collect($data);
    }

    public function headings(): array
    {
        return ['Name', 'Lead Status', 'Call Disposition', 'Dial No.', 'Collector', 'Agent Disposition', 'Created Date'];
        
    }

    public function map($data): array
    {
    	$full_name 		= $data['full_name']; 
        $status 		= $data['status']; 
        $call_status 	= $data['call_status'];
        $contact_no 	= $data['contact_no'];
        $call_by 		= $data['call_by'];
        $agent_status   = $data['agent_status'];
        $created_at    	= $data['created_at'];

		return [$full_name, $status, $call_status, $contact_no, $call_by, $agent_status, $created_at];
    	
    }
}
