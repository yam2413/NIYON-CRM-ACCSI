<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use AppHelper;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Groups;
use App\Models\SystemLogs;
use App\Models\CrmBorrowers;
use App\Models\CrmLeads;
use App\Models\CrmPtpHistories;
use App\Models\CrmCallLogs;
use App\Models\CrmLogs;
use App\Models\CronActivities;
use App\Models\Statuses;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(){
        $user = Auth::user();
        $groups   = Groups::get();
        $agent_stats = User::selectRaw('users.*, (select count(*) from crm_leads t where t.assign_user = users.id AND t.deleted = 0 ) as total_assign, (select count(*) from crm_ptp_histories x left join crm_leads ON crm_leads.id = x.leads_id where x.created_by = users.id) as total_process, (select count(*) from added_campaigns_leads y where y.collector_id = users.id) as total_assign_dialer');
        $agent_stats->where('users.email','!=','root');

        if($user->level == '2' || $user->level == '1'){ //Check the User Level
            $agent_stats->where('users.group','=',$user->group);
        }
        
        $agent_stats->where('users.level','=',3);
        $agent_stats->orderByRaw('total_process DESC, total_assign DESC');
        $agent_stats->skip(0);
        $agent_stats->take(5);
        $agent_stats = $agent_stats->get();


        $agent_call_logs = CrmCallLogs::select(
            [
                'crm_call_logs.*',
                'crm_borrowers.full_name',
            ]
        );
        $agent_call_logs->orderByRaw('crm_call_logs.created_at DESC');
        $agent_call_logs->leftjoin('crm_borrowers', 'crm_borrowers.profile_id', '=', 'crm_call_logs.profile_id');
        $agent_call_logs->where('crm_call_logs.call_by','=' ,$user->id);
        $agent_call_logs->skip(0);
        $agent_call_logs->take(10);
        $agent_call_logs = $agent_call_logs->get();

    	return view('pages.dashboard.index', compact('agent_stats', 'agent_call_logs','groups'));
    }

    public function view_status($statuses){
        $user = Auth::user();
        $groups   = Groups::get();
        return view('pages.dashboard.view_status', compact('statuses','groups'));
    }

    public function call_monitoring(){
        $user     = Auth::user();
        $groups   = Groups::get();
        return view('pages.dashboard.call_monitoring', compact('groups'));
    }

    public function get_call_monitoring(Request $request){
        $user = Auth::user();
        $get_data = [];

        $records = User::select(
            [
                'users.id',
                'users.name',
                'users.avatar',
                'users.group',
                'users.extension',
                'crm_call_logs.contact_no',
                'crm_call_logs.created_at',
                'crm_call_logs.call_by',
            ]
        );
        $records->join('crm_call_logs', 'crm_call_logs.call_by', '=', 'users.id');
        $records->join(DB::raw('(SELECT call_by, MAX(created_at) AS maxdate FROM crm_call_logs GROUP BY call_by) AS b'), function($join)
                         {
                $join->on('crm_call_logs.call_by', '=', 'b.call_by');
                $join->on('crm_call_logs.created_at', '=', 'b.maxdate');
        });
        $records->orderBy('crm_call_logs.created_at','DESC');
        $records->whereRaw('DATE(crm_call_logs.created_at) = "'.date('Y-m-d').'"');
        $records->where('users.group', '=', $request->group_id);
        $records = $records->get();

        foreach ($records as $key => $value) {

            $asterisk_status = trim(AppHelper::get_asterisk_status($value->extension, $value->contact_no));
            $dialplan_listen      = "*222".$value->extension;
            $dialplan_whisper     = "*223".$value->extension;
            $dialplan_barge       = "*224".$value->extension;

            if($asterisk_status != ''){
                switch ($asterisk_status) {
                    case 'Ringing':
                        $status = "Ringing";
                        $color  = "warning";
                        break;

                    case 'Down':
                        $status = "Connecting";
                        $color  = "warning";
                        break;

                    case 'Up':
                        
                        $status = "On Call";
                        $color  = "success";
                        break;

                    default:
                       $status = $asterisk_status;
                       $color  = "light";
                       break;
                }
                    
        }else{
            $status = 'Idle';
            $color  = "light";
        }
            $get_data[] = array(
                'name'                  => $value->name,
                'avatar'                => $value->avatar,
                'extension'             => $value->extension,
                'contact_no'            => $value->contact_no,
                'call_status'           => $status,
                'call_color'            => $color,
                'dialplan_listen'       => $dialplan_listen,
                'dialplan_whisper'      => $dialplan_whisper,
                'dialplan_barge'        => $dialplan_barge,
           );
        }

        return response()->json([
            'error'         => 'false',
            'total'         => count($records),
            'get_data'      => $get_data
        ]);
    }

    public function get_leads_status(Request $request){
        $user = Auth::user();
        $get_data = [];

        $new_dates         = explode('|', str_replace(' ', '', $request->date)); 
        $start_date        = (isset($new_dates[0])) ? $new_dates[0]:date('Y-m-d');
        $end_date          = (isset($new_dates[1])) ? $new_dates[1]:date('Y-m-d');


        $records = Statuses::where('group', '=', $request->group_id);
        $records->orderByRaw('status_name <> "NEW",status_name <> "BEST TIME TO CALL"');
        $records = $records->get();

        foreach ($records as $key => $value) {

            $total_statuses = CrmLeads::select('count(*) as allcount');
            $total_statuses->where('deleted','=' , 0);
            $total_statuses->where('status','=' , $value->status_name);
            $total_statuses->where('assign_group','=' , $value->group);
            if($user->level == '3' || $user->level == '2'){
                $total_statuses->where('assign_user','=' , $user->id);
            }
            $total_statuses->whereRaw('DATE(status_updated) BETWEEN "'.$start_date.'" AND "'.$end_date.'" ');
            $total_statuses = $total_statuses->count();

            $get_data[] = array(
                'status_name'   => $value->status_name,
                'total_count'   => $total_statuses
           );
        }

        return response()->json([
            'error'  => 'false',
            'get_data'   => $get_data
        ]);
    }

    public function getDemographStatusLeads(Request $request){
        $user = Auth::user();
        
        $new_dates         = explode('|', str_replace(' ', '', $request->date)); 
        $start_date        = (isset($new_dates[0])) ? $new_dates[0].' 00:00:00':date('Y-m-d').' 00:00:00';
        $end_date          = (isset($new_dates[1])) ? $new_dates[1].' 23:00:00':date('Y-m-d').' 23:00:00';

        $add_query1 = '';
        $add_query2 = '';
        $add_query3 = '';
        $add_query4 = '';
        $add_query5 = '';

        if($user->level == '3'){

            $add_query1 = ' AND t.assign_user = '.$user->id.' ';
            $add_query2 = ' AND x.created_by = '.$user->id.' ';
            $add_query3 = ' AND y.created_by = '.$user->id.' ';
            $add_query4 = ' AND z.created_by = '.$user->id.' ';
            $add_query5 = ' AND q.created_by = '.$user->id.' ';

        }else if($user->level == '2' || $user->level == '1'){
            $add_query1 = ' AND t.assign_group = '.$user->group.' ';
            $add_query2 = ' AND x.assign_group = '.$user->group.' ';
            $add_query3 = ' AND y.assign_group = '.$user->group.' ';
            $add_query4 = ' AND z.assign_group = '.$user->group.' ';
            $add_query5 = ' AND q.assign_group = '.$user->group.' ';
        }

        $records = CrmLeads::selectRaw('crm_leads.*, (select count(*) from crm_leads t where t.created_at between "'.$start_date.'" AND "'.$end_date.'" AND t.status = 0 AND t.deleted = 0 '.$add_query1.') as new_lead, (select count(*) from crm_ptp_histories x left join crm_leads ON crm_leads.profile_id = x.profile_id where x.created_at between "'.$start_date.'" AND "'.$end_date.'" AND x.status = 1 AND crm_leads.deleted = 0 '.$add_query2.') as ptp, (select count(*) from crm_ptp_histories y left join crm_leads ON crm_leads.profile_id = y.profile_id where y.created_at between "'.$start_date.'" AND "'.$end_date.'" AND y.status = 2 AND crm_leads.deleted = 0 '.$add_query3.' ) as bptp, (select count(*) from crm_ptp_histories z left join crm_leads ON crm_leads.profile_id = z.profile_id where z.created_at between "'.$start_date.'" AND "'.$end_date.'" AND z.status = 3 AND crm_leads.deleted = 0 '.$add_query4.') as bp, (select count(*) from crm_ptp_histories q left join crm_leads ON crm_leads.profile_id = q.profile_id where q.created_at between "'.$start_date.'" AND "'.$end_date.'" AND q.status = 4 AND crm_leads.deleted = 0 '.$add_query5.') as paid');
        $records = $records->first();

        return response()->json([
                'new_lead'  => (isset($records->new_lead)) ? $records->new_lead:'0',
                'ptp'       => (isset($records->ptp)) ? $records->ptp:'0',
                'bptp'      => (isset($records->bptp)) ? $records->bptp:'0',
                'bp'      	=> (isset($records->bp)) ? $records->bp:'0',
                'paid'      => (isset($records->paid)) ? $records->paid:'0',
        ]);
    }

    public function LeadsStatisticsGraph(Request $request){
        $user = \Auth::user();
        $year = $request->year;
        $data  = [];
        $x = 1;
        

        for ($i=0; $i < 12; $i++) { 

        	$total_response[$i] = CrmLeads::select('count(*) as allcount');
        	$total_response[$i]->whereMonth('status_updated', $x);
            $total_response[$i]->whereYear('status_updated', date('Y'));
            $total_response[$i]->where('status', '!=', '0');
            if($user->level == '3'){
                $total_response[$i]->where('assign_user', '=', $user->id);
            }else if($user->level == '2' || $user->level == '3'){
                $total_response[$i]->where('assign_group', '=',$user->group);
            }
            $total_response[$i] = $total_response[$i]->count();
            $x++;

            $data[] = $total_response[$i];
        }
       
        $list = $data;

        return response()->json([
            'error'    => 'false',
            'total'    => $list,
        ]);
    }

    public function getLeads(Request $request){

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

     $new_dates         = explode('|', str_replace(' ', '', $request->date)); 
     $start_date        = (isset($new_dates[0])) ? $new_dates[0]:date('Y-m-d');
     $end_date          = (isset($new_dates[1])) ? $new_dates[1]:date('Y-m-d');

     if($request->ptp_date != ''){
        $new_ptp_date         = explode('|', str_replace(' ', '', $request->ptp_date)); 
        $start_ptp_date        = (isset($new_ptp_date[0])) ? $new_ptp_date[0]:date('Y-m-d');
        $end_ptp_date          = (isset($new_ptp_date[1])) ? $new_ptp_date[1]:date('Y-m-d');
     }

     // Total records
     //$totalRecords = Groups::select('count(*) as allcount')->count();
     $totalRecordswithFilter = CrmLeads::select('count(*) as allcount');
     $totalRecordswithFilter->leftjoin('crm_borrowers', 'crm_borrowers.profile_id', '=', 'crm_leads.profile_id');
     $totalRecordswithFilter->where(function($query) use ($searchValue)  {
              $query->where('crm_borrowers.full_name', 'like', '%' .$searchValue . '%')
              ->orWhere('crm_leads.account_number', 'like', '%' .$searchValue . '%');
        });
     $totalRecordswithFilter->whereRaw('DATE(crm_leads.status_updated) BETWEEN "'.$start_date.'" AND "'.$end_date.'" ');

     if($request->ptp_date != ''){
        $totalRecordswithFilter->whereRaw('DATE(crm_leads.payment_date) BETWEEN "'.$start_ptp_date.'" AND "'.$end_ptp_date.'" ');
     }

     switch ($user->level) {
         case '0':
             if($request->groups != 0){
                $totalRecordswithFilter->where('assign_group','=' ,$request->groups);
             }
             if($request->collector != '0' && $request->collector != ''){
                $totalRecordswithFilter->where('crm_leads.assign_user','=' ,$request->collector);
             }
             break;
        
        case '1':
             $totalRecordswithFilter->where('assign_group','=' ,$user->group);
             if($request->collector != '0' && $request->collector != ''){
                $totalRecordswithFilter->where('crm_leads.assign_user','=' ,$request->collector);
             }
             break;

        case '2':
             $totalRecordswithFilter->where('assign_group','=' ,$user->group);
             $totalRecordswithFilter->where('crm_leads.assign_user','=' ,$user->id);
             break;
         
         default:
             $totalRecordswithFilter->where('assign_group','=' ,$user->group);
             $totalRecordswithFilter->where('crm_leads.assign_user','=' ,$user->id);
             break;
     }
     
     $totalRecordswithFilter->where('crm_leads.deleted','=' ,0);

        if($request->status != ''){
            $totalRecordswithFilter->where('crm_leads.status','=' ,$request->status);

        }
        
     
     $totalRecordswithFilter = $totalRecordswithFilter->count();

     // Fetch records
     $records = CrmLeads::select(
            [
                'crm_borrowers.full_name',
                'crm_borrowers.home_no',
                'crm_borrowers.cellphone_no',
                'crm_leads.*',
            ]
        );
     $records->orderByRaw('crm_leads.priority <> 1');
     $records->orderBy($columnName,$columnSortOrder);
     $records->leftjoin('crm_borrowers', 'crm_borrowers.profile_id', '=', 'crm_leads.profile_id');
     $records->where(function($query) use ($searchValue)  {
              $query->where('crm_borrowers.full_name', 'like', '%' .$searchValue . '%')
              ->orWhere('crm_leads.account_number', 'like', '%' .$searchValue . '%');
        });
     $records->whereRaw('DATE(crm_leads.status_updated) BETWEEN "'.$start_date.'" AND "'.$end_date.'" ');
     if($request->ptp_date != ''){
        $records->whereRaw('DATE(crm_leads.payment_date) BETWEEN "'.$start_ptp_date.'" AND "'.$end_ptp_date.'" ');
     }
     switch ($user->level) {
         case '0':
             if($request->groups != 0){
                $records->where('crm_leads.assign_group','=' ,$request->groups);
             }
             if($request->collector != '0' && $request->collector != ''){
                $records->where('crm_leads.assign_user','=' ,$request->collector);
             }
             break;

        case '1':
             $records->where('assign_group','=' ,$user->group);
             if($request->collector != '0' && $request->collector != ''){
                $records->where('crm_leads.assign_user','=' ,$request->collector);
             }
             break;

        case '2':
             $records->where('assign_group','=' ,$user->group);
             $records->where('crm_leads.assign_user','=' ,$user->id);
             break;
         
         default:
             $records->where('assign_group','=' ,$user->group);
             $records->where('crm_leads.assign_user','=' ,$user->id);
             break;
     }
     $records->where('crm_leads.deleted','=' ,0);
     $records->where('crm_borrowers.full_name','!=' ,'');

        if($request->status != ''){
            $records->where('crm_leads.status','=' ,$request->status);

        }
        
        
     
     
     $records->skip($start);
     $records->take($rowperpage);
     $records = $records->get();

     $data_arr = array();

     foreach($records as $record){
        $id                     = $record->id;
        $profile_id             = $record->profile_id;
        $full_name              = $record->full_name;
        $status                 = $record->status;
        $ptp_amount             = $record->ptp_amount;
        $payment_date           = $record->payment_date;
        $assign_user            = User::getName($record->assign_user);
        $account_number         = $record->account_number;
        $assign_group           = Groups::usersGroup($record->assign_group);
        $created_at             = $record->created_at->diffForHumans();
        $priority               = $record->priority;
        $outstanding_balance    = number_format($record->outstanding_balance, 2);
        $home_no                = $record->home_no;
        $cellphone_no           = $record->cellphone_no;
        $status_updated         = $record->status_updated;

        if($priority == 1){
            $priority_label = '<i class="fas fa-exclamation-circle text-warning mr-5" data-toggle="tooltip" data-theme="dark" title="This account is priority"></i>';
        }else{
            $priority_label = '';
        }

        switch ($status) {
            case '0':
                $status_label = 'New';
                break;
            
            default:
                $status_label = $status;
                break;
        }

        $action_btn   = '<center>
                            <a href="'.route('pages.leads.profile', ['profile_id' => $profile_id]).'" class="btn btn-icon btn-outline-secondary btn-circle btn-sm mr-2" title="View account details">
                                <i class="fas fa-user-cog"></i>
                            </a>
                        </center>';


        $data_arr[] = array(
          "full_name"           => $full_name,
          "status"              => '<center>'.$status_label.$priority_label.'</center>',
          "assign_user"         => $assign_user,
          "account_number"      => $account_number,
          "assign_group"        => $assign_group,
          "created_at"          => $created_at,
          "ptp_amount"          => $ptp_amount,
          "outstanding_balance" => $outstanding_balance,
          "payment_date"        => $payment_date,
          "home_no"             => $home_no,
          "cellphone_no"        => $cellphone_no,
          "status_updated"      => $status_updated,
          "action"              => $action_btn
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
