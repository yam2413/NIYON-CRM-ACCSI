<?php

namespace App\Http\Controllers;

use DB;
use Auth;
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
use App\Models\ReportLogs;
use App\Jobs\DownloadRecordJob;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\HeadingRowImport;
use App\Exports\SummaryCallsExport;
use App\Exports\CallStatusExport;
use App\Exports\SystemLogsExport;
use App\Exports\LeadsLogsExport;
use App\Exports\SMSLogsExport;
use App\Exports\EamilLogsExport;
use App\Jobs\ReportLogsJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ReportsController extends Controller
{
    public function view_call_logs(){
    ##To view the main page from the call logs reports
    	$groups   = Groups::get();
        $filter_status = array(
            '0' => 'New Leads',
            '1' => 'Promise to Pay',
            '2' => 'Below Promise to Pay',
            '3' => 'Broken Promise',
            '4' => 'Paid',
        );
    	return view('pages.reports.call_logs.index', compact('groups','filter_status'));
    }

    public function view_summary_calls(){
    ##To view the main page from the summary & calls reports
    	$groups   = Groups::get();
        $filter_status = array(
            '0' => 'New Leads',
            '1' => 'Promise to Pay',
            '2' => 'Below Promise to Pay',
            '3' => 'Broken Promise',
            '4' => 'Paid',
        );
    	return view('pages.reports.summary_calls.index', compact('groups','filter_status'));
    }

    public function view_call_status(){
    ##To view the main page from the call status reports
        $groups   = Groups::get();
        $filter_status = array(
            '1' => 'Promise to Pay',
            '2' => 'Below Promise to Pay',
            '3' => 'Broken Promise',
            '4' => 'Paid',
            // 'unanswered_call' => 'Unanswered Call',
            // 'answered_call' => 'Answered Call',
        );
        return view('pages.reports.call_status.index', compact('groups','filter_status'));
    }

    public function view_system_logs(){
    ##To view the main page from the system logs reports
        $groups   = Groups::get();
        $users   = User::get();

        return view('pages.reports.system_logs.index', compact('groups','users'));
    }

    public function view_leads_logs(){
    ##To view the main page from the system logs reports
        $groups   = Groups::get();
        $users   = User::get();

        return view('pages.reports.leads_logs.index', compact('groups','users'));
    }

    public function view_sms_logs(){


        return view('pages.reports.sms.index');
    }

    public function view_email_logs(){
    ##To view the main page from the system logs reports

        return view('pages.reports.email.index');
    }

    public function view_report_logs(){
    ##To view the main page from the system logs reports

        return view('pages.reports.report_logs.index');
    }

    public function get_user_list(Request $request){
        $user = Auth::user();

        if($user->level == '3'){
            $users   = User::where('group','=' ,$request->group)->where('id','=' ,$user->id)->orderBy('name','DESC')->get();
        }else{
            $users   = User::where('group','=' ,$request->group)->orderBy('name','DESC')->get();
        }
        
        $get_data = [];

        foreach ($users as $key => $user) {
           $get_data[] = array(
                'id' => $user->id,
                'name' => $user->name
           );
        }

        return response()->json([
            'error'  => 'false',
            'get_data'   => $get_data
        ]);

    }

    public function download_recordings(Request $request){
    	DownloadRecordJob::dispatch($request->id);
    }

    public function export_summary_calls($date, $group, $collector){

        return Excel::download(new SummaryCallsExport($date, $group, $collector), 'summary_calls_'.date('Y-m-d H:i:s').'.xlsx');
    }

    public function export_call_status($date, $group, $collector, $status){

        return Excel::download(new CallStatusExport($date, $group, $collector, $status), 'call_status_'.date('Y-m-d H:i:s').'.xlsx');
    }

    public function export_system_logs($date, $collector){

        return Excel::download(new SystemLogsExport($date, $collector), 'system_logs_'.date('Y-m-d H:i:s').'.xlsx');
    }

    public function export_leads_logs($date, $collector){

        return Excel::download(new LeadsLogsExport($date, $collector), 'leads_logs_'.date('Y-m-d H:i:s').'.xlsx');
    }

    public function export_sms_logs($date){

        return Excel::download(new SMSLogsExport($date), 'sms_logs_'.date('Y-m-d H:i:s').'.xlsx');
    }

    public function export_email_logs($date){

        return Excel::download(new EamilLogsExport($date), 'email_logs_'.date('Y-m-d H:i:s').'.xlsx');
    }

    public function export_reports(Request $request){
        $user = Auth::user();
        $uniq_id    =  substr(md5(rand(999, 999999)), 0, 3).date('YmdHis');
        $insert_data = array(
            'user'          => $user->id,
            'file_id'       => $uniq_id,
            'file_path'     => '',
            'file_name'     => '',
            'status'        => 0,
            'report_type'   => $request->report_type,
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        );
        ReportLogs::insert($insert_data);

        $import_array = array(
            'import_filename'   => '',
            'import_path'       => '',
            'user_id'           => $user->id,
            'uniq_id'           => $uniq_id,
            'group_id'          => $request->groups,
            'status'            => $request->status,
            'date'              => $request->date,
            'collector'         => $request->collector,
        );

        ReportLogsJob::dispatch(json_encode($import_array), $request->report_type);
        SystemLogs::saveLogs($user->id, strtoupper($request->report_type).' report has been exported ');
        
        return response()->json([
           'error'  => 'false',
           'msg'    => strtoupper($request->report_type).' report has been processing to extract, go to the report logs to download the file.'
        ]);
    }

    public function getDemographSMS(Request $request){
        $user = Auth::user();
        
        $new_dates         = explode('|', str_replace(' ', '', $request->date)); 
        $start_date        = (isset($new_dates[0])) ? $new_dates[0].' 00:00:00':date('Y-m-d').' 00:00:00';
        $end_date          = (isset($new_dates[1])) ? $new_dates[1].' 23:00:00':date('Y-m-d').' 23:00:00';

        $records = CronActivities::selectRaw('cron_activities.*, (select count(*) from cron_activities t where t.created_at between "'.$start_date.'" AND "'.$end_date.'" AND t.status = 1 AND t.type = "sms") as no_success, (select count(*) from cron_activities x where  x.created_at between "'.$start_date.'" AND "'.$end_date.'" AND x.status = 2 AND x.type = "sms") as no_error, (select count(*) from cron_activities y where  y.created_at between "'.$start_date.'" AND "'.$end_date.'" AND y.status = 0 AND y.type = "sms") as no_processing');
        $records = $records->first();

        return response()->json([
                'success'         => number_format($records->no_success, 0),
                'error'           => number_format($records->no_error, 0),
                'processing'      => number_format($records->no_processing, 0),
        ]);
    }

    public function getDemographEmail(Request $request){
        $user = Auth::user();
        
        $new_dates         = explode('|', str_replace(' ', '', $request->date)); 
        $start_date        = (isset($new_dates[0])) ? $new_dates[0].' 00:00:00':date('Y-m-d').' 00:00:00';
        $end_date          = (isset($new_dates[1])) ? $new_dates[1].' 23:00:00':date('Y-m-d').' 23:00:00';

        $records = CronActivities::selectRaw('cron_activities.*, (select count(*) from cron_activities t where t.created_at between "'.$start_date.'" AND "'.$end_date.'" AND t.status = 1 AND t.type = "email") as no_success, (select count(*) from cron_activities x where  x.created_at between "'.$start_date.'" AND "'.$end_date.'" AND x.status = 2 AND x.type = "email") as no_error, (select count(*) from cron_activities y where  y.created_at between "'.$start_date.'" AND "'.$end_date.'" AND y.status = 0 AND y.type = "email") as no_processing');
        $records = $records->first();

        return response()->json([
                'success'         => number_format($records->no_success, 0),
                'error'           => number_format($records->no_error, 0),
                'processing'      => number_format($records->no_processing, 0),
        ]);
    }

    public function CallLogsStatisticsGraph(Request $request){
        $user = \Auth::user();
        $year = date('Y');
        $data  = [];
        $x = 1;
        
        for ($i=0; $i < 12; $i++) { 

            $total_response[$i] = CrmCallLogs::select('count(*) as allcount');
            // $total_response[$i]->where('member_id','=' ,$user->member_id);
            $total_response[$i]->leftjoin('crm_leads', 'crm_leads.profile_id', '=', 'crm_call_logs.profile_id');
            if($request->groups != 0){
                $total_response[$i]->where('crm_leads.assign_group','=' ,$request->groups);
             }
             switch ($request->status) {
                    case '0':
                        $total_response[$i]->where('crm_leads.status','=' ,'0');
                        break;

                    case '1':
                        $total_response[$i]->where('crm_leads.status','=' ,'1');
                        break;

                    case '2':
                        $total_response[$i]->where('crm_leads.status','=' ,'2');
                        break;

                    case '3':
                        $total_response[$i]->where('crm_leads.status','=' ,'3');
                        break;

                    case '4':
                        $total_response[$i]->where('crm_leads.status','=' ,'4');
                        break;
                    
                    default:
                        # code...
                        break;
             }

            $total_response[$i]->whereMonth('crm_call_logs.created_at', $x);
            $total_response[$i]->whereYear('crm_call_logs.created_at', $year);
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

    public function SummaryCallsPie(Request $request){
        $user = \Auth::user();

        $new_dates         = explode('|', str_replace(' ', '', $request->date)); 
        $start_date        = (isset($new_dates[0])) ? $new_dates[0]:date('Y-m-d');
        $end_date          = (isset($new_dates[1])) ? $new_dates[1]:date('Y-m-d');
        $no_calls          = [];
        $no_ptp            = [];
        $data              = [];

        // Fetch records
         $records = User::selectRaw('users.*, (select count(*) from crm_call_logs t where t.call_by = users.id AND DATE(t.created_at) between "'.$start_date.'" AND "'.$end_date.'") as no_calls, (select count(*) from crm_ptp_histories x where x.created_by = users.id AND DATE(x.created_at) between "'.$start_date.'" AND "'.$end_date.'") as no_ptp');
         $records->orderByRaw('no_calls DESC,no_ptp DESC');
         if($request->groups != '0'){
            $records->where('users.group','=' ,$request->groups);
         }
         $records->where('users.level','=' ,3);

         if($request->collector != '0'){
            $records->where('users.id','=' ,$request->collector);
         }
         $records = $records->get();


         $add_no_calls  = 0;
         $add_no_ptp    = 0;
         foreach ($records as $key => $record) {
             $add_no_calls  = $add_no_calls + $record->no_calls;
             $add_no_ptp    = $add_no_ptp + $record->no_ptp;
         }
        
        $no_calls[] = $add_no_calls;
        $no_ptp[] = $add_no_ptp;


        $data = [$add_no_calls, $add_no_ptp];

        return response()->json([
            'error'     => 'false',
            'total'  => $data,
        ]);
    }


    public function CallStatusGraph(Request $request){
        $user = \Auth::user();
        $year = date('Y');
        $data  = [];
        $categories  = [];
        $x = 1;

        $new_dates         = explode('|', str_replace(' ', '', $request->date)); 
        $start_date        = (isset($new_dates[0])) ? $new_dates[0]:date('Y-m-d');
        $end_date          = (isset($new_dates[1])) ? $new_dates[1]:date('Y-m-d');

        //All Statuses
        $records = Statuses::orderByRaw('status_name <> "NEW",status_name <> "BEST TIME TO CALL"');
        if($request->status != '0'){
            $records->where('status_name','=' , $request->status);
        }
        //if($request->groups != '0'){
            $records->where('group', '=', $request->groups);
        //}
        
        $records = $records->get();

        foreach ($records as $key => $value) {

            $total_statuses = CrmLeads::select('count(*) as allcount');
            $total_statuses->where('deleted','=' , 0);
            $total_statuses->where('status','=' , $value->status_name);
            $total_statuses->where('assign_group','=' , $value->group);
            if($user->level == '3'){
                $total_statuses->where('assign_user','=' , $user->id);
            }
            $total_statuses->whereRaw('DATE(status_updated) BETWEEN "'.$start_date.'" AND "'.$end_date.'" ');
            $total_statuses = $total_statuses->count();

            $categories[] = $value->status_name;


            $data[] = $total_statuses;
        }

        
        // $data = [number_format($total_ptp,0),number_format($total_bptp,0),number_format($total_bp,0),number_format($total_paid,0)];

        $list = $data;

        return response()->json([
            'error'    => 'false',
            'categories'    => $categories,
            'total'         => $list,
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


     $new_dates         = explode('|', str_replace(' ', '', $request->date)); 
     $start_date        = (isset($new_dates[0])) ? $new_dates[0]:date('Y-m-d');
     $end_date          = (isset($new_dates[1])) ? $new_dates[1]:date('Y-m-d');

     // Total records
     //$totalRecords = User::select('count(*) as allcount')->count();
     $totalRecordswithFilter = CrmCallLogs::select('count(*) as allcount');
     $totalRecordswithFilter->where(function($query) use ($searchValue)  {
              $query->where('crm_borrowers.full_name', 'like', '%' .$searchValue . '%')
                      ->orWhere('crm_call_logs.contact_no', 'like', '%' .$searchValue . '%')
                      ->orWhere('crm_call_logs.extension', 'like', '%' .$searchValue . '%');
     });
     $totalRecordswithFilter->leftjoin('crm_borrowers', 'crm_borrowers.profile_id', '=', 'crm_call_logs.profile_id');
     $totalRecordswithFilter->leftjoin('crm_leads', 'crm_leads.profile_id', '=', 'crm_call_logs.profile_id');
     $totalRecordswithFilter->where('crm_leads.deleted','=' ,0);
     if($request->groups != '0'){
     	$totalRecordswithFilter->where('crm_leads.assign_group','=' ,$request->groups);
     }
     $totalRecordswithFilter->whereRaw('DATE(crm_call_logs.created_at) BETWEEN "'.$start_date.'" AND "'.$end_date.'" ');
     if($request->status != '0'){
        $totalRecordswithFilter->where('crm_leads.status','=' ,$request->status);
     }
     if($request->collector != '0'){
        $totalRecordswithFilter->where('crm_leads.assign_user','=' ,$request->collector);
     }
     $totalRecordswithFilter = $totalRecordswithFilter->count();

     // Fetch records
     $records = CrmCallLogs::select(
            [
            	'crm_call_logs.*',
                'crm_borrowers.full_name',
                'crm_leads.status',
                'crm_leads.assign_group',
                'crm_leads.assign_user',
            ]
        );
     $records->orderBy($columnName,$columnSortOrder);
     $records->where(function($query) use ($searchValue)  {
              $query->where('crm_borrowers.full_name', 'like', '%' .$searchValue . '%')
                      ->orWhere('crm_call_logs.contact_no', 'like', '%' .$searchValue . '%')
                      ->orWhere('crm_call_logs.extension', 'like', '%' .$searchValue . '%');
     });
     $records->leftjoin('crm_borrowers', 'crm_borrowers.profile_id', '=', 'crm_call_logs.profile_id');
     $records->leftjoin('crm_leads', 'crm_leads.profile_id', '=', 'crm_call_logs.profile_id');
     $records->where('crm_leads.deleted','=' ,0);
     if($request->groups != '0'){
     	$records->where('crm_leads.assign_group','=' ,$request->groups);
     }
     $records->whereRaw('DATE(crm_call_logs.created_at) BETWEEN "'.$start_date.'" AND "'.$end_date.'" ');
     if($request->status != '0'){
        $records->where('crm_leads.status','=' ,$request->status);
     }
     if($request->collector != '0'){
        $records->where('crm_leads.assign_user','=' ,$request->collector);
     }

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
        $assign_group 	= Groups::usersGroup($record->assign_group);
        $created_at 	= $record->created_at->diffForHumans();


        $cdr_exist = DB::connection('mysql2')->table('cdr')->where('cnam','=',$call_id)->first();

        if($cdr_exist){

            $record_url = $cdr_exist->recordingfile;
            $explode_url = explode('/', $cdr_exist->recordingfile);

            if(isset($explode_url[5])){
                $url_download   = 'https://'.env('ASTERISK_HOST').'/record/'.$explode_url[5].'/'.$explode_url[6].'/'.$explode_url[7].'/'.$explode_url[8];
                $action_btn   = '<audio controls>
                          <source src="'.$url_download.'" type="audio/wav">
                        Your browser does not support the audio element.
                        </audio>';
            }else{
                $action_btn   = '';
            }
            
        }else{
            $action_btn   = '';
        }

        $data_arr[] = array(
          "contact_no" 		=> $contact_no,
          "status" 			=> '<center>'.$status.'</center>',
          "assign_group" 	=> $assign_group,
          "full_name" 		=> $full_name,
          "extension" 		=> $extension,
          "call_by" 		=> $call_by,
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


   public function getSummaryCallsList(Request $request){

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
     $start_date        = (isset($new_dates[0])) ? $new_dates[0].' 00:00:00':date('Y-m-d').' 00:00:00';
     $end_date          = (isset($new_dates[1])) ? $new_dates[1].' 23:00:00':date('Y-m-d').' 23:00:00';

     // Total records
     //$totalRecords = User::select('count(*) as allcount')->count();
     $totalRecordswithFilter = User::select('count(*) as allcount');
     $totalRecordswithFilter->where(function($query) use ($searchValue)  {
              $query->where('users.name', 'like', '%' .$searchValue . '%');
     });
     if($request->groups != '0' && $request->groups != ''){
        $totalRecordswithFilter->where('users.group','=' ,$request->groups);
     }

     if($request->collector != '0' && $request->collector != ''){
        $totalRecordswithFilter->where('users.id','=' ,$request->collector);
     }
     $totalRecordswithFilter->where('users.level','=' ,3);
     $totalRecordswithFilter = $totalRecordswithFilter->count();

     // Fetch records
     $records = User::selectRaw('users.*, (select count(*) from crm_call_logs t where t.call_by = users.id AND t.created_at between "'.$start_date.'" AND "'.$end_date.'") as no_calls, (select count(*) from crm_ptp_histories x where x.created_by = users.id AND x.created_at between "'.$start_date.'" AND "'.$end_date.'") as no_ptp, (select SUM(REPLACE(y.payment_amount, ",", "")) from crm_ptp_histories y where y.created_by = users.id AND y.created_at between "'.$start_date.'" AND "'.$end_date.'") as total_ptp_amount');
     $records->orderByRaw('no_calls DESC,no_ptp DESC,total_ptp_amount DESC');
     $records->where(function($query) use ($searchValue)  {
        $query->where('users.name', 'like', '%' .$searchValue . '%');
     });
     //$records->join('crm_call_logs', 'crm_call_logs.call_by', '=', 'users.id');
     if($request->groups != '0' && $request->groups != ''){
        $records->where('users.group','=' ,$request->groups);
     }
     $records->where('users.level','=' ,3);

     if($request->collector != '0' && $request->collector != ''){
        $records->where('users.id','=' ,$request->collector);
     }

     $records->skip($start);
     $records->take($rowperpage);
     $records = $records->get();

     $data_arr = array();

     foreach($records as $record){
        $id 			= $record->id;
        $name 			= $record->name;
        $group 			= Groups::usersGroup($record->group);
        $created_at 	= '';



        $data_arr[] = array(
          "no_calls" 			=> $record->no_calls,
          "no_ptp" 				=> $record->no_ptp,
          "total_ptp_amount" 	=> number_format($record->total_ptp_amount, 2),
          "name" 				=> $name,
          "assign_group" 		=> $group,
          "created_at" 			=> $created_at,
          "action" 				=> ''
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

   public function getCallStatusList(Request $request){

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

     // Total records
     //$totalRecords = User::select('count(*) as allcount')->count();
     $totalRecordswithFilter = CrmLeads::select('count(*) as allcount');
     $totalRecordswithFilter->where(function($query) use ($searchValue)  {
              $query->where('crm_borrowers.full_name', 'like', '%' .$searchValue . '%');
     });
     $totalRecordswithFilter->leftjoin('crm_borrowers', 'crm_borrowers.profile_id', '=', 'crm_leads.profile_id');
     $totalRecordswithFilter->where('crm_leads.deleted','=' ,0);
     //if($request->groups != '0'){
        $totalRecordswithFilter->where('crm_leads.assign_group','=' ,$request->groups);
     //}
     if($request->collector != '0'){
        $totalRecordswithFilter->where('crm_leads.assign_user','=' ,$request->collector);
     }
     //$totalRecordswithFilter->whereRaw('DATE(crm_leads.status_updated) BETWEEN "'.$start_date.'" AND "'.$end_date.'" ');
     $totalRecordswithFilter->whereRaw('(DATE(crm_leads.status_updated) BETWEEN "'.$start_date.'" AND "'.$end_date.'" OR DATE(crm_leads.updated_at) BETWEEN "'.$start_date.'" AND "'.$end_date.'") ');
     if($request->status != '0'){
        $totalRecordswithFilter->where('crm_leads.status','=' ,$request->status);
     }
     $totalRecordswithFilter = $totalRecordswithFilter->count();

     // Fetch records
     $records = CrmLeads::select(
            [
                'crm_leads.*',
                'crm_borrowers.full_name',
            ]
        );
     $records->orderBy($columnName,$columnSortOrder);
     $records->where(function($query) use ($searchValue)  {
              $query->where('crm_borrowers.full_name', 'like', '%' .$searchValue . '%');
     });
     $records->leftjoin('crm_borrowers', 'crm_borrowers.profile_id', '=', 'crm_leads.profile_id');
     $records->where('crm_leads.deleted','=' ,0);
     //if($request->groups != '0'){
        $records->where('crm_leads.assign_group','=' ,$request->groups);
     //}
     if($request->collector != '0'){
        $records->where('crm_leads.assign_user','=' ,$request->collector);
     }
     $records->whereRaw('(DATE(crm_leads.status_updated) BETWEEN "'.$start_date.'" AND "'.$end_date.'" OR DATE(crm_leads.updated_at) BETWEEN "'.$start_date.'" AND "'.$end_date.'") ');
     if($request->status != '0'){
        $records->where('crm_leads.status','=' ,$request->status);
     }
     

     $records->skip($start);
     $records->take($rowperpage);
     $records = $records->get();

     $data_arr = array();

     foreach($records as $record){
        $id             = $record->id;
        $profile_id     = $record->profile_id;
        $assign_user    = User::getName($record->assign_user);
        $full_name      = $record->full_name;

        $account_number = $record->account_number;
        $payment_date   = $record->payment_date;
        $ptp_amount     = $record->ptp_amount;
        $remarks        = $record->remarks;
        

        $data_arr[] = array(
          "full_name"       => $full_name,
          "account_number"  => $account_number,
          "payment_date"    => $payment_date,
          "ptp_amount"      => $ptp_amount,
          "remarks"         => $remarks,
          "assign_user"     => $assign_user,
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


   public function getSystemLogsList(Request $request){

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

     // Total records
     //$totalRecords = User::select('count(*) as allcount')->count();
     $totalRecordswithFilter = SystemLogs::select('count(*) as allcount');
     $totalRecordswithFilter->where(function($query) use ($searchValue)  {
              $query->where('users.name', 'like', '%' .$searchValue . '%')
              ->orWhere('system_logs.actions', 'like', '%' .$searchValue . '%');
     });
     $totalRecordswithFilter->leftjoin('users', 'users.id', '=', 'system_logs.user');
     if($request->collector != '0' && $request->collector != ''){
        $totalRecordswithFilter->where('system_logs.user','=' ,$request->collector);
     }
     $totalRecordswithFilter->whereRaw('DATE(system_logs.created_at) BETWEEN "'.$start_date.'" AND "'.$end_date.'" ');
     $totalRecordswithFilter = $totalRecordswithFilter->count();

     // Fetch records
     $records = SystemLogs::select(
            [
                'system_logs.*',
                'users.name',
            ]
        );
     $records->orderBy($columnName,$columnSortOrder);
     $records->where(function($query) use ($searchValue)  {
              $query->where('users.name', 'like', '%' .$searchValue . '%')
               ->orWhere('system_logs.actions', 'like', '%' .$searchValue . '%');
     });
     $records->leftjoin('users', 'users.id', '=', 'system_logs.user');
     if($request->collector != '0' && $request->collector != ''){
        $records->where('system_logs.user','=' ,$request->collector);
     }
     $records->whereRaw('DATE(system_logs.created_at) BETWEEN "'.$start_date.'" AND "'.$end_date.'" ');

     $records->skip($start);
     $records->take($rowperpage);
     $records = $records->get();

     $data_arr = array();

     foreach($records as $record){
        $id             = $record->id;
        $name           = $record->name;
        $actions        = $record->actions;
        $created_at     = $record->created_at->diffForHumans();

        

        $data_arr[] = array(
          "name"          => $name,
          "actions"       => $actions,
          "created_at"    => $created_at,
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

   public function getLeadsLogsList(Request $request){

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
     $start_date        = (isset($new_dates[0])) ? $new_dates[0].' 00:00:00':date('Y-m-d').' 00:00:00';
     $end_date          = (isset($new_dates[1])) ? $new_dates[1].' 23:00:00':date('Y-m-d').' 23:00:00';

     // Total records
     //$totalRecords = User::select('count(*) as allcount')->count();
     $totalRecordswithFilter = CrmLogs::select('count(*) as allcount');
     $totalRecordswithFilter->where(function($query) use ($searchValue)  {
              $query->where('users.name', 'like', '%' .$searchValue . '%')
              ->orWhere('crm_borrowers.full_name', 'like', '%' .$searchValue . '%');
     });
     $totalRecordswithFilter->leftjoin('crm_borrowers', 'crm_borrowers.profile_id', '=', 'crm_logs.profile_id');
     $totalRecordswithFilter->join('users', 'users.id', '=', 'crm_logs.user');
     if($request->collector != '0' && $request->collector != ''){
        $totalRecordswithFilter->where('crm_logs.user','=' ,$request->collector);
     }
     $totalRecordswithFilter->whereBetween('crm_logs.created_at', [$start_date, $end_date]);
     $totalRecordswithFilter = $totalRecordswithFilter->count();

     // Fetch records
     $records = CrmLogs::select(
            [
                'crm_logs.*',
                'users.name',
                'crm_borrowers.full_name',
            ]
        );
     $records->orderBy($columnName,$columnSortOrder);
     $records->where(function($query) use ($searchValue)  {
              $query->where('users.name', 'like', '%' .$searchValue . '%')
              ->orWhere('crm_borrowers.full_name', 'like', '%' .$searchValue . '%');
     });
     $records->leftjoin('crm_borrowers', 'crm_borrowers.profile_id', '=', 'crm_logs.profile_id');
     $records->join('users', 'users.id', '=', 'crm_logs.user');
     if($request->collector != '0' && $request->collector != ''){
        $records->where('crm_logs.user','=' ,$request->collector);
     }
     $records->whereBetween('crm_logs.created_at', [$start_date, $end_date]);

     $records->skip($start);
     $records->take($rowperpage);
     $records = $records->get();

     $data_arr = array();

     foreach($records as $record){
        $id             = $record->id;
        $full_name           = $record->full_name;
        $actions        = $record->actions;
        $created_at     = $record->created_at->diffForHumans();

        

        $data_arr[] = array(
          "full_name"          => $full_name,
          "actions"       => $actions,
          "created_at"    => $created_at,
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

   public function getSMSLogsList(Request $request){

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
     $start_date        = (isset($new_dates[0])) ? $new_dates[0].' 00:00:00':date('Y-m-d').' 00:00:00';
     $end_date          = (isset($new_dates[1])) ? $new_dates[1].' 23:00:00':date('Y-m-d').' 23:00:00';

     // Total records
     //$totalRecords = User::select('count(*) as allcount')->count();
     $totalRecordswithFilter = CronActivities::select('count(*) as allcount');
     $totalRecordswithFilter->where(function($query) use ($searchValue)  {
              $query->where('crm_borrowers.full_name', 'like', '%' .$searchValue . '%');
     });
     $totalRecordswithFilter->leftjoin('crm_borrowers', 'crm_borrowers.profile_id', '=', 'cron_activities.profile_id');
     $totalRecordswithFilter->whereBetween('cron_activities.created_at', [$start_date, $end_date]);
     $totalRecordswithFilter->where('cron_activities.type','=' ,'sms');
     $totalRecordswithFilter = $totalRecordswithFilter->count();

     // Fetch records
     $records = CronActivities::select(
            [
                'cron_activities.*',
                'crm_borrowers.full_name',
            ]
        );
     $records->orderBy($columnName,$columnSortOrder);
     $records->where(function($query) use ($searchValue)  {
              $query->where('crm_borrowers.full_name', 'like', '%' .$searchValue . '%');
     });
     $records->leftjoin('crm_borrowers', 'crm_borrowers.profile_id', '=', 'cron_activities.profile_id');
     $records->whereBetween('cron_activities.created_at', [$start_date, $end_date]);
     $records->where('cron_activities.type','=' ,'sms');

     $records->skip($start);
     $records->take($rowperpage);
     $records = $records->get();

     $data_arr = array();

     foreach($records as $record){
        $id             = $record->id;
        $full_name      = $record->full_name;
        $to             = $record->to;
        $body           = $record->body;
        $status         = $record->status;
        $error_msg      = $record->error_msg;
        $created_at     = $record->created_at->diffForHumans();
        $user           = User::getName($record->user);

        switch ($status) {
          case '1':
            $status_label = "Added Queuing List";
            break;
          
          case '2':
            $status_label = "Failed to add in queue list";
            break;
          
          default:
            $status_label = "Processing";
            break;
        }

        $data_arr[] = array(
          "full_name"     => $full_name,
          "to"            => $to,
          "body"          => $body,
          "status"        => $status_label,
          "user"          => $user,
          "created_at"    => $created_at,
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

   public function getEmailLogsList(Request $request){

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
     $start_date        = (isset($new_dates[0])) ? $new_dates[0].' 00:00:00':date('Y-m-d').' 00:00:00';
     $end_date          = (isset($new_dates[1])) ? $new_dates[1].' 23:00:00':date('Y-m-d').' 23:00:00';

     // Total records
     //$totalRecords = User::select('count(*) as allcount')->count();
     $totalRecordswithFilter = CronActivities::select('count(*) as allcount');
     $totalRecordswithFilter->where(function($query) use ($searchValue)  {
              $query->where('crm_borrowers.full_name', 'like', '%' .$searchValue . '%');
     });
     $totalRecordswithFilter->leftjoin('crm_borrowers', 'crm_borrowers.profile_id', '=', 'cron_activities.profile_id');
     $totalRecordswithFilter->whereBetween('cron_activities.created_at', [$start_date, $end_date]);
     $totalRecordswithFilter->where('cron_activities.type','=' ,'email');
     $totalRecordswithFilter = $totalRecordswithFilter->count();

     // Fetch records
     $records = CronActivities::select(
            [
                'cron_activities.*',
                'crm_borrowers.full_name',
            ]
        );
     $records->orderBy($columnName,$columnSortOrder);
     $records->where(function($query) use ($searchValue)  {
              $query->where('crm_borrowers.full_name', 'like', '%' .$searchValue . '%');
     });
     $records->leftjoin('crm_borrowers', 'crm_borrowers.profile_id', '=', 'cron_activities.profile_id');
     $records->whereBetween('cron_activities.created_at', [$start_date, $end_date]);
     $records->where('cron_activities.type','=' ,'email');

     $records->skip($start);
     $records->take($rowperpage);
     $records = $records->get();

     $data_arr = array();

     foreach($records as $record){
        $id             = $record->id;
        $full_name      = $record->full_name;
        $to             = $record->to;
        $body           = $record->body;
        $status         = $record->status;
        $error_msg      = $record->error_msg;
        $created_at     = $record->created_at->diffForHumans();
        $user           = User::getName($record->user);

        switch ($status) {
          case '1':
            $status_label = "Send";
            break;
          
          case '2':
            $status_label = "Failed";
            break;
          
          default:
            $status_label = "Processing";
            break;
        }

        $data_arr[] = array(
          "full_name"     => $full_name,
          "to"            => $to,
          "body"          => $body,
          "status"        => $status_label,
          "user"          => $user,
          "created_at"    => $created_at,
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

   public function get_Report_Logs(Request $request){

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

     // Total records
     //$totalRecords = Groups::select('count(*) as allcount')->count();
     $totalRecordswithFilter = ReportLogs::select('count(*) as allcount')
     ->where(function($query) use ($searchValue)  {
              $query->where('file_id', 'like', '%' .$searchValue . '%');
        })
     ->whereRaw('created_at >= DATE_ADD(CURDATE(), INTERVAL -4 DAY)')
     ->where('user', '=', $user->id)
     ->count();

     // Fetch records
     $records = ReportLogs::orderBy($columnName,$columnSortOrder)
        ->where(function($query) use ($searchValue)  {
              $query->where('file_id', 'like', '%' .$searchValue . '%');
        })
       ->whereRaw('created_at >= DATE_ADD(CURDATE(), INTERVAL -4 DAY)')
       ->where('user', '=', $user->id)
       ->skip($start)
       ->take($rowperpage)
       ->get();

     $data_arr = array();

     foreach($records as $record){
        $id = $record->id;

        $file_id            = $record->file_id;
        $file_path          = $record->file_path;
        $file_name          = $record->file_name;
        $report_type        = $record->report_type;
        $status             = $record->status;
        $create_by          = User::getName($record->user);
        $created_at         = $record->created_at->diffForHumans();


       switch ($status) {
        case '1':
            $status_label = '<center><span class="label label-xl label-success label-inline mr-2">Done</span></center>';
            $action = '<a class="btn btn-secondary font-weight-lighter mr-2" href="'.asset(Storage::url($file_path)).'"  title="Download report logs">Download</a>';
            break;
        
        default:
            $status_label = '<center><span class="label label-xl label-secondary label-inline mr-2">Downloading...</span></center>';
            $action = '';
            break;
       }
       
        $data_arr[] = array(
          "file_id"         => $file_id,
          "status"          => '<center>'.$status_label.'</center>',
          "user"            => $create_by,
          "report_type"    => $report_type,
          "created_at"      => $created_at,
          "action"          => $action
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
