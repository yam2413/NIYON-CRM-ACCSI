<?php

namespace App\Http\Controllers;

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
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\HeadingRowImport;
use App\Exports\CallLogsSummaryDialerExport;
use App\Exports\CollectorPerformeDialerExport;
use Illuminate\Http\Request;

class InsightsController extends Controller
{
    public function insights($file_id){
    	return view('pages.auto_dialer.insights', compact('file_id'));
    }

    public function export_callsummary_dialer($start_date, $end_date, $file_id){

        return Excel::download(new CallLogsSummaryDialerExport($start_date, $end_date, $file_id), 'call_logs_summary_'.date('Y-m-d H:i:s').'.xlsx');
    }

    public function export_collectorperforme_dialer($start_date, $end_date, $file_id){

        return Excel::download(new CollectorPerformeDialerExport($start_date, $end_date, $file_id), 'collector_performance_'.date('Y-m-d H:i:s').'.xlsx');
    }

    public function get_dialer_status(Request $request){
    	$user = \Auth::user();

    	//Count All Dial Accounts
        $total_dial = AddedCampaignsLeads::select('count(*) as allcount');
     	$total_dial->where('file_id','=' ,$request->file_id);
     	$total_dial->where('dial','=' ,1);
     	$total_dial = $total_dial->count();

     	//Count All Process Accounts
        $total_process = AddedCampaignsLeads::select('count(*) as allcount');
     	$total_process->where('file_id','=' ,$request->file_id);
     	$total_process->where('process','=' ,1);
     	$total_process = $total_process->count();

     	//Count All Not Process Accounts
        $total_pending_accounts = AddedCampaignsLeads::select('count(*) as allcount');
     	$total_pending_accounts->where('file_id','=' ,$request->file_id);
     	$total_pending_accounts->where('process','=' ,0);
     	$total_pending_accounts = $total_pending_accounts->count();

     	//Count All Not Process Accounts
        $total_pause = CampaignLogs::select('count(*) as allcount');
     	$total_pause->where('file_id','=' ,$request->file_id);
     	$total_pause->where('log_type','=' ,'pause');
     	$total_pause = $total_pause->count();

     	$total_answered = AddedCampaignsLeads::select('count(*) as allcount');
     	$total_answered->leftjoin('crm_ptp_histories', 'crm_ptp_histories.leads_id', '=', 'added_campaigns_leads.leads_id');
     	$total_answered->where('added_campaigns_leads.file_id','=' ,$request->file_id);
     	$total_answered->where('crm_ptp_histories.call_status','=' ,'Answered');
     	$total_answered = $total_answered->count();

     	$total_no_answered = AddedCampaignsLeads::select('count(*) as allcount');
     	$total_no_answered->leftjoin('crm_ptp_histories', 'crm_ptp_histories.leads_id', '=', 'added_campaigns_leads.leads_id');
     	$total_no_answered->where('added_campaigns_leads.file_id','=' ,$request->file_id);
     	$total_no_answered->where('crm_ptp_histories.call_status','!=' ,'Answered');
     	$total_no_answered = $total_no_answered->count();

    	return response()->json([
            'total_dial' 				=> $total_dial,
            'total_process' 			=> $total_process,
            'total_pending_accounts' 	=> $total_pending_accounts,
            'total_pause' 				=> $total_pause,
            'total_answered' 			=> $total_answered,
            'total_no_answered' 		=> $total_no_answered,
        ]);

    }

    public function get_leads_status(Request $request){
    	$user = \Auth::user();

    	//Count All New Leads
        $new_leads = AddedCampaignsLeads::select('count(*) as allcount');
     	$new_leads->leftjoin('crm_leads', 'crm_leads.id', '=', 'added_campaigns_leads.leads_id');
     	$new_leads->where('added_campaigns_leads.file_id','=' ,$request->file_id);
     	$new_leads->where('crm_leads.status','=' ,0);
     	$new_leads = $new_leads->count();

     	//Count all ptp
     	$ptp = AddedCampaignsLeads::select('count(*) as allcount');
     	$ptp->leftjoin('crm_leads', 'crm_leads.id', '=', 'added_campaigns_leads.leads_id');
     	$ptp->where('added_campaigns_leads.file_id','=' ,$request->file_id);
     	$ptp->where('crm_leads.status','=' ,1);
     	$ptp = $ptp->count();

     	//Count all bptp
     	$bptp = AddedCampaignsLeads::select('count(*) as allcount');
     	$bptp->leftjoin('crm_leads', 'crm_leads.id', '=', 'added_campaigns_leads.leads_id');
     	$bptp->where('added_campaigns_leads.file_id','=' ,$request->file_id);
     	$bptp->where('crm_leads.status','=' ,2);
     	$bptp = $bptp->count();

     	//Count all bp
     	$bp = AddedCampaignsLeads::select('count(*) as allcount');
     	$bp->leftjoin('crm_leads', 'crm_leads.id', '=', 'added_campaigns_leads.leads_id');
     	$bp->where('added_campaigns_leads.file_id','=' ,$request->file_id);
     	$bp->where('crm_leads.status','=' ,3);
     	$bp = $bp->count();

     	//Count all Paid
     	$paid = AddedCampaignsLeads::select('count(*) as allcount');
     	$paid->leftjoin('crm_leads', 'crm_leads.id', '=', 'added_campaigns_leads.leads_id');
     	$paid->where('added_campaigns_leads.file_id','=' ,$request->file_id);
     	$paid->where('crm_leads.status','=' ,4);
     	$paid = $paid->count();

        return response()->json([
            'new_leads' => $new_leads,
            'ptp'       => $ptp,
            'bptp'      => $bptp,
            'bp'      	=> $bp,
            'paid'      => $paid,
        ]);
    }

    public function getCallLogsList(Request $request){

     ## Read value
     $user = Auth::user();

     $draw = $request->get('draw');
     $start = $request->get("start");
     $rowperpage = $request->get("length"); // Rows display per page

     $columnIndex_arr = $request->get('order');
     $columnName_arr = $request->get('columns');
     $order_arr = $request->get('order');
     $search_arr = $request->get('search');

     $columnIndex = $columnIndex_arr[0]['column']; // Column index
     $columnName = $columnName_arr[$columnIndex]['data']; // Column name
     $columnSortOrder = $order_arr[0]['dir']; // asc or desc
     $searchValue = $search_arr['value']; // Search value

     $start_date        = (isset($request->start_date)) ? $request->start_date.' 00:00:00':date('Y-m-d').' 00:00:00';
     $end_date          = (isset($request->end_date)) ? $request->end_date.' 23:00:00':date('Y-m-d').' 23:00:00';

     // Total records
     //$totalRecords = User::select('count(*) as allcount')->count();
     $totalRecordswithFilter = CrmCallLogs::select('count(*) as allcount');
     $totalRecordswithFilter->where(function($query) use ($searchValue)  {
              $query->where('crm_borrowers.full_name', 'like', '%' .$searchValue . '%')
                      ->orWhere('crm_call_logs.contact_no', 'like', '%' .$searchValue . '%')
                      ->orWhere('crm_call_logs.extension', 'like', '%' .$searchValue . '%');
     });
     $totalRecordswithFilter->leftjoin('added_campaigns_leads', 'added_campaigns_leads.leads_id', '=', 'crm_call_logs.leads_id');
     $totalRecordswithFilter->leftjoin('crm_leads', 'crm_leads.id', '=', 'crm_call_logs.leads_id');
     $totalRecordswithFilter->leftjoin('crm_ptp_histories', 'crm_ptp_histories.leads_id', '=', 'crm_call_logs.leads_id');
     $totalRecordswithFilter->leftjoin('crm_borrowers', 'crm_borrowers.profile_id', '=', 'crm_leads.profile_id');
     $totalRecordswithFilter->where('crm_leads.deleted','=' ,0);
     $totalRecordswithFilter->where('added_campaigns_leads.file_id','=' , $request->file_id);
     $totalRecordswithFilter->whereBetween('crm_call_logs.created_at', [$start_date, $end_date]);
     $totalRecordswithFilter = $totalRecordswithFilter->count();

     // Fetch records
     $records = CrmCallLogs::select(
            [
            	'crm_call_logs.*',
                'crm_borrowers.full_name',
                'crm_leads.status',
                'crm_leads.assign_group',
                'crm_ptp_histories.call_status',
            ]
        );
     $records->orderBy($columnName,$columnSortOrder);
     $records->where(function($query) use ($searchValue)  {
              $query->where('crm_borrowers.full_name', 'like', '%' .$searchValue . '%')
                      ->orWhere('crm_call_logs.contact_no', 'like', '%' .$searchValue . '%')
                      ->orWhere('crm_call_logs.extension', 'like', '%' .$searchValue . '%');
     });
     $records->leftjoin('added_campaigns_leads', 'added_campaigns_leads.leads_id', '=', 'crm_call_logs.leads_id');
     $records->leftjoin('crm_leads', 'crm_leads.id', '=', 'crm_call_logs.leads_id');
     $records->leftjoin('crm_ptp_histories', 'crm_ptp_histories.leads_id', '=', 'crm_call_logs.leads_id');
     $records->leftjoin('crm_borrowers', 'crm_borrowers.profile_id', '=', 'crm_leads.profile_id');
     
     
     $records->where('crm_leads.deleted','=' ,0);
     $records->whereBetween('crm_call_logs.created_at', [$start_date, $end_date]);
     $records->where('added_campaigns_leads.file_id','=' , $request->file_id);


     $records->skip($start);
     $records->take($rowperpage);
     $records = $records->get();

     $data_arr = array();

     foreach($records as $record){
        $id 			= $record->id;
        $call_id 		= $record->call_id;
        $profile_id 	= $record->profile_id;
        $call_by 		= User::getName($record->call_by);
        $contact_no 	= $record->contact_no;
        $extension 		= $record->extension;
        $full_name 		= $record->full_name;
        $status 		= $record->status;
        $agent_status 	= $record->call_status;
        $assign_group 	= Groups::usersGroup($record->assign_group);
        $created_at 	= $record->created_at->diffForHumans();

        // switch ($status) {
        // 	case '1':
        // 		$status_label = '<span class="label label-xl label-warning label-inline mr-2">PTP</span>';
        // 		break;

        // 	case '2':
        // 		$status_label = '<span class="label label-xl label-danger label-inline mr-2">BPTP</span>';
        // 		break;

        // 	case '3':
        // 		$status_label = '<span class="label label-xl label-secondary label-inline mr-2">BP</span>';
        // 		break;

        //   case '4':
        //     $status_label = '<span class="label label-xl label-primary label-inline mr-2">Paid</span>';
        //     break;
        	
        // 	default:
        // 		$status_label = '<span class="label label-xl label-success label-inline mr-2">New</span>';
        // 		break;
        // }

        $cdr_exist = DB::connection('mysql2')->table('cdr')->where('cnam','=',$call_id)->first();

        if($cdr_exist){

            $record_url = $cdr_exist->recordingfile;
            $explode_url = explode('/', $cdr_exist->recordingfile);
            
            if(isset($explode_url[5])){
                $url_download   = 'https://'.env('ASTERISK_HOST').'/record/'.$explode_url[5].'/'.$explode_url[6].'/'.$explode_url[7].'/'.$explode_url[8];
            }else{
                $url_download   = 'https://'.env('ASTERISK_HOST').'/record/'.$cdr_exist->recordingfile;
            }
            $url_download   = $url_download;
            $action_btn   = '<audio controls>
                          <source src="'.$url_download.'" type="audio/wav">
                        Your browser does not support the audio element.
                        </audio>';
              //$action_btn   = $url_download;
            $last_app = $cdr_exist->lastapp;
        }else{
            $action_btn   = $call_id;
            $last_app     = '';
        }
       

        

        $data_arr[] = array(
          "contact_no" 		=> $contact_no,
          "status" 			=> '<center>'.$status.'</center>',
          "call_status" 	=> $last_app,
          "assign_group" 	=> $assign_group,
          "full_name" 		=> $full_name,
          "extension" 		=> $extension,
          "call_by" 		=> $call_by,
          "agent_status" 	=> $agent_status,
          "created_at" 		=> $created_at,
          "action" 			=> $action_btn
        );
     }

     $response = array(
        "draw" => intval($draw),
        "iTotalRecords" => $totalRecordswithFilter,
        "iTotalDisplayRecords" => $totalRecordswithFilter,
        "aaData" => $data_arr
     );

     echo json_encode($response);
     exit;
   }

   public function getAgentPerformance(Request $request){

     ## Read value
     $user = Auth::user();

     $draw = $request->get('draw');
     $start = $request->get("start");
     $rowperpage = $request->get("length"); // Rows display per page

     $columnIndex_arr = $request->get('order');
     $columnName_arr = $request->get('columns');
     $order_arr = $request->get('order');
     $search_arr = $request->get('search');

     $columnIndex = $columnIndex_arr[0]['column']; // Column index
     $columnName = $columnName_arr[$columnIndex]['data']; // Column name
     $columnSortOrder = $order_arr[0]['dir']; // asc or desc
     $searchValue = $search_arr['value']; // Search value

     $start_date        = (isset($request->start_date)) ? $request->start_date.' 00:00:00':date('Y-m-d').' 00:00:00';
     $end_date          = (isset($request->end_date)) ? $request->end_date.' 23:00:00':date('Y-m-d').' 23:00:00';

     // Total records
     //$totalRecords = User::select('count(*) as allcount')->count();
     $totalRecordswithFilter = AddedCampaignsAgents::select('count(*) as allcount');
     $totalRecordswithFilter->where(function($query) use ($searchValue)  {
              $query->where('users.name', 'like', '%' .$searchValue . '%');
     });
     $totalRecordswithFilter->leftjoin('users', 'users.id', '=', 'added_campaigns_agents.collector_id');
     $totalRecordswithFilter->where('added_campaigns_agents.file_id','=' , $request->file_id);
     $totalRecordswithFilter = $totalRecordswithFilter->count();

     // Fetch records
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
     $records->orderBy($columnName,$columnSortOrder);
     $records->where(function($query) use ($searchValue)  {
              $query->where('users.name', 'like', '%' .$searchValue . '%');
     });
     $records->leftjoin('users', 'users.id', '=', 'added_campaigns_agents.collector_id');
     $records->where('added_campaigns_agents.file_id','=' , $request->file_id);


     $records->skip($start);
     $records->take($rowperpage);
     $records = $records->get();

     $data_arr = array();

     foreach($records as $record){
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

        $data_arr[] = array(
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

     $response = array(
        "draw" => intval($draw),
        "iTotalRecords" => $totalRecordswithFilter,
        "iTotalDisplayRecords" => $totalRecordswithFilter,
        "aaData" => $data_arr
     );

     echo json_encode($response);
     exit;
   }


}
