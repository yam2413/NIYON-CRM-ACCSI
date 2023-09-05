<?php

namespace App\Http\Controllers;

use DB;
use DateTime;
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
use App\Models\Statuses;
use App\Models\SystemLogs;
use App\Models\ImportLogs;
use App\Mail\LeadsEmail;
use App\Jobs\SendEmailJob;
use App\Jobs\ImportLeadsStatusJob;
use App\Jobs\BulkUpdateStatusJob;
use App\Models\Campaigns;
use App\Models\AddedCampaignsLeads;
use App\Models\AddedCampaignsAgents;
use App\Models\FileHeaders;
use App\Models\ManualNumbers;
use Illuminate\Http\Request;

class LeadsStatusController extends Controller
{
    public function index(){

        $user = Auth::user();
        $groups   = Groups::get();

        return view('pages.leads_status.index', compact('user','groups'));
    }

    public function import_account_status(){

        $user = Auth::user();
        $groups   = Groups::get();

        return view('pages.leads_status.import', compact('user','groups'));
    }

    public function view_import($file_id, $group_id){

        $user = Auth::user();

        return view('pages.leads_status.view_import', compact('user','group_id','file_id'));
    }

    public function upload_account_status(Request $request){
        $user = Auth::user();
        $uniq_id    =  substr(md5(rand(999, 999999)), 0, 3).date('YmdHis');
        $error_msg  = [];

        if($request->group_id == ''){
            return response()->json([
               'error'      => 'true',
               'msg'        => 'Please select a group'
            ]);
        }
        $extension  = $request->file->extension();
        $path       = $request->file->storeAs('public/update_status', $uniq_id.'.'.$extension);

        $insert_data = array(
            'user'          => $user->id,
            'file_id'       => $uniq_id,
            'file_path'     => $path,
            'file_name'     => $uniq_id.'.'.$extension,
            'status'        => 0,
            'report_type'   => 'account_status',
            'log_type'      => 'update_account_status',
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        );
        ImportLogs::insert($insert_data);

        $import_array = array(
            'import_filename' => $uniq_id.'.'.$extension,
            'import_path'     => $path,
            'user_id'         => $user->id,
            'uniq_id'         => $uniq_id,
            'group_id'         => $request->group_id,
        );

        ImportLeadsStatusJob::dispatch(json_encode($import_array));
        SystemLogs::saveLogs($user->id, 'Import Accounts to be update status');
        
        return response()->json([
           'error'      => 'false',
           'file_id'    => $uniq_id,
           'group_id'   => $request->group_id,
           'msg'        => 'Successfully imported leads account'
        ]);
    }

    public function update_status(Request $request){
        $user = Auth::user();

        $import_array = array(
            'user_id'           => $user->id,
            'new_status'        => $request->new_status,
            'group_id'          => $request->group_id,
            'date'              => $request->date,
            'collector'         => $request->collector,
            'old_status'        => $request->old_status,
            'file_id'           => $request->file_id,
        );

        BulkUpdateStatusJob::dispatch($request->id, json_encode($import_array), $request->type);
        SystemLogs::saveLogs($user->id, 'Bulk updated status to '.$request->new_status.' has been start');
        
        return response()->json([
           'error'  => 'false',
           'msg'    => 'Selected account has been added in queuing list for update status.'
        ]);
    }

    public function getLeadsList(Request $request){

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
     //$totalRecords = Groups::select('count(*) as allcount')->count();
     $totalRecordswithFilter = CrmLeads::select('count(*) as allcount');
     $totalRecordswithFilter->leftjoin('crm_borrowers', 'crm_borrowers.profile_id', '=', 'crm_leads.profile_id');
     $totalRecordswithFilter->where(function($query) use ($searchValue)  {
              $query->where('crm_borrowers.full_name', 'like', '%' .$searchValue . '%')
              ->orWhere('crm_leads.account_number', 'like', '%' .$searchValue . '%');
        });
     $totalRecordswithFilter->where('assign_group','=' ,$request->groups);
     if($request->collector != 0){
        $totalRecordswithFilter->where('crm_leads.assign_user','=' ,$request->collector);
     }
     if($request->status != ''){
        $totalRecordswithFilter->where('crm_leads.status','=' ,$request->status);
     }
     $totalRecordswithFilter->where('crm_leads.deleted','=' ,0);
     $totalRecordswithFilter->whereRaw('DATE(crm_leads.status_updated) BETWEEN "'.$start_date.'" AND "'.$end_date.'" ');
     $totalRecordswithFilter = $totalRecordswithFilter->count();

     // Fetch records
     $records = CrmLeads::select(
            [
                'crm_borrowers.full_name',
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
     $records->where('crm_leads.assign_group','=' ,$request->groups);

     if($request->collector != 0){
        $records->where('crm_leads.assign_user','=' ,$request->collector);
     }
     if($request->status != ''){
        $records->where('crm_leads.status','=' ,$request->status);
     }
     $records->whereRaw('DATE(crm_leads.status_updated) BETWEEN "'.$start_date.'" AND "'.$end_date.'" ');
     $records->where('crm_leads.deleted','=' ,0);

     
     $records->skip($start);
     $records->take($rowperpage);
     $records = $records->get();

     $data_arr = array();

     foreach($records as $record){
        $id             = $record->id;
        $profile_id     = $record->profile_id;
        $full_name      = $record->full_name;
        $status         = $record->status;
        $ptp_amount         = $record->ptp_amount;
        $payment_date         = $record->payment_date;
        $assign_user    = User::getName($record->assign_user);
        $account_number = $record->account_number;
        $assign_group   = Groups::usersGroup($record->assign_group);
        $created_at     = $record->created_at->diffForHumans();
        $priority     = $record->priority;

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
          "full_name"       => $full_name,
          "status"          => '<center>'.$status_label.$priority_label.'</center>',
          "assign_user"     => $assign_user,
          "account_number"  => $account_number,
          "assign_group"    => $assign_group,
          "created_at"      => $created_at,
          "ptp_amount"      => $ptp_amount,
          "payment_date"    => $payment_date,
          "id"              => $id
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

   public function get_imported_list(Request $request){

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
     //$totalRecords = Groups::select('count(*) as allcount')->count();
     $totalRecordswithFilter = CrmLeads::select('count(*) as allcount');
     $totalRecordswithFilter->leftjoin('crm_borrowers', 'crm_borrowers.profile_id', '=', 'crm_leads.profile_id');
     $totalRecordswithFilter->leftjoin('temp_uploads', 'temp_uploads.data1', '=', 'crm_leads.account_number');
     $totalRecordswithFilter->where(function($query) use ($searchValue)  {
              $query->where('crm_borrowers.full_name', 'like', '%' .$searchValue . '%')
              ->orWhere('crm_leads.account_number', 'like', '%' .$searchValue . '%');
        });
     $totalRecordswithFilter->where('crm_leads.deleted','=' ,0);
     $totalRecordswithFilter->where('temp_uploads.file_id','=' ,$request->file_id);
     $totalRecordswithFilter->where('temp_uploads.groups','=' ,$request->group_id);
     $totalRecordswithFilter = $totalRecordswithFilter->count();

     // Fetch records
     $records = CrmLeads::select(
            [
                'crm_borrowers.full_name',
                'crm_leads.id',
                'crm_leads.profile_id',
                'crm_leads.status',
                'crm_leads.account_number',
                'crm_leads.created_at',
                'crm_leads.assign_user',
                'crm_leads.assign_group',
                'temp_uploads.data1',
                'temp_uploads.file_id',
            ]
        );
     //$records->orderByRaw('crm_leads.priority <> 1');
     //$records->orderBy($columnName,$columnSortOrder);
     $records->leftjoin('crm_borrowers', 'crm_borrowers.profile_id', '=', 'crm_leads.profile_id');
     $records->leftjoin('temp_uploads', 'temp_uploads.data1', '=', 'crm_leads.account_number');
     $records->where(function($query) use ($searchValue)  {
              $query->where('crm_borrowers.full_name', 'like', '%' .$searchValue . '%')
              ->orWhere('crm_leads.account_number', 'like', '%' .$searchValue . '%');
     });
     $records->where('crm_leads.deleted','=' ,0);
     $records->where('temp_uploads.file_id','=' ,$request->file_id);
     $records->where('temp_uploads.groups','=' ,$request->group_id);
     $records->skip($start);
     $records->take($rowperpage);
     $records = $records->get();

     $data_arr = array();

     foreach($records as $record){
        $id             = $record->id;
        $profile_id     = $record->profile_id;
        $full_name      = $record->full_name;
        $status         = $record->status;
        $assign_user    = User::getName($record->assign_user);
        $account_number = $record->account_number;
        $assign_group   = Groups::usersGroup($record->assign_group);
        $created_at     = $record->created_at->diffForHumans();
        $status_label = $status;


        $data_arr[] = array(
          "full_name"       => $full_name,
          "status"          => '<center>'.$status_label.'</center>',
          "assign_user"     => $assign_user,
          "account_number"  => $account_number,
          "assign_group"    => $assign_group,
          "created_at"      => $created_at,
          "id"              => $id
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
