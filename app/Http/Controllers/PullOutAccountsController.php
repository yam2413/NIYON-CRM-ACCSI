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
use App\Models\CrmLogs;
use App\Models\SystemLogs;
use App\Models\PulloutLogs;
use App\Models\Statuses;
use App\Models\FileHeaders;
use App\Models\ManualNumbers;
use App\Jobs\PulloutAccountsJob;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class PullOutAccountsController extends Controller
{
    public function index(){
    	$groups   = Groups::get();
    	$filter_status = array(
            '0' => 'New Leads',
            '1' => 'Promise to Pay',
            '2' => 'Below Promise to Pay',
            '3' => 'Broken Promise',
            '4' => 'Paid',
        );
    	return view('pages.pullout.index', compact('groups','filter_status'));
    }

    public function update_pullouts(Request $request){
    	$user = Auth::user();
        $uniq_id    =  substr(md5(rand(999, 999999)), 0, 3).date('YmdHis');
        $insert_data = array(
            'user'          => $user->id,
            'file_id'       => $uniq_id,
            'file_path'     => '',
            'file_name'     => '',
            'status'        => 0,
            'pullout_type'  => 'manual',
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        );
        PulloutLogs::insert($insert_data);

        $import_array = array(
        	'import_filename' 	=> '',
        	'import_path'     	=> '',
        	'user_id'     		=> $user->id,
        	'uniq_id'     		=> $uniq_id,
            'group_id'          => $request->group_id,
        );

        PulloutAccountsJob::dispatch($request->id, json_encode($import_array), 'manual');
        SystemLogs::saveLogs($user->id, 'Manual select pullout accounts');
        
        return response()->json([
           'error'  => 'false',
           'msg'    => 'Selected account has been added in pullout, go to the pullout logs to download the file.'
        ]);
    }

    public function update_pullout_all(Request $request){
        $user = Auth::user();
        $uniq_id    =  substr(md5(rand(999, 999999)), 0, 3).date('YmdHis');
        $insert_data = array(
            'user'          => $user->id,
            'file_id'       => $uniq_id,
            'file_path'     => '',
            'file_name'     => '',
            'status'        => 0,
            'pullout_type'  => 'all',
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        );
        PulloutLogs::insert($insert_data);

        $import_array = array(
            'import_filename'   => '',
            'import_path'       => '',
            'user_id'           => $user->id,
            'uniq_id'           => $uniq_id,
            'group_id'          => $request->group_id,
        );

        PulloutAccountsJob::dispatch($request->id, json_encode($import_array), 'all');
        SystemLogs::saveLogs($user->id, 'Pullout All accounts from '.$request->groups_name);
        
        return response()->json([
           'error'  => 'false',
           'msg'    => 'All account from '.$request->groups_name.' has been added in pullout, go to the pullout logs to download the file.'
        ]);
    }

    public function import_pullout(Request $request){
    	$user = \Auth::user();
    	$uniq_id =  substr(md5(rand(999, 999999)), 0, 3).date('YmdHis');
    	$error = "false";
    	$msg = "";
        $error_msg 	= [];
        $extension 	= $request->file->extension();
        $path 		= $request->file->storeAs('public/file_upload', $uniq_id.'.'.$extension);

        $insert_data = array(
            'user'          => $user->id,
            'file_id'       => $uniq_id,
            'file_path'     => '',
            'file_name'     => '',
            'status'        => 0,
            'pullout_type'  => 'import',
            'created_at'    => Carbon::now(),
            'updated_at'    => Carbon::now(),
        );
        PulloutLogs::insert($insert_data);

        $import_array = array(
        	'import_filename' => $uniq_id.'.'.$extension,
        	'import_path'     => $path,
        	'user_id'     	  => $user->id,
        	'uniq_id'     	  => $uniq_id,
            'group_id'        => $request->group_id,
        );
        PulloutAccountsJob::dispatch($request->id, json_encode($import_array), 'import');
        SystemLogs::saveLogs($user->id, 'Import pullout accounts');

    	return response()->json([
           'error'  => 'false',
           'msg'    => 'Successfully upload the file in queuing list, go to the pullout logs to download the file.'
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

     // $start_date        = (isset($new_dates[0])) ? $new_dates[0].' 00:00:00':date('Y-m-d').' 00:00:00';
     // $end_date          = (isset($new_dates[1])) ? $new_dates[1].' 23:00:00':date('Y-m-d').' 23:00:00';

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
     $records->where('crm_leads.deleted','=' ,0);

     
     $records->skip($start);
     $records->take($rowperpage);
     $records = $records->get();

     $data_arr = array();

     foreach($records as $record){
        $id 			= $record->id;
        $profile_id 	= $record->profile_id;
        $full_name 		= $record->full_name;
        $status 		= $record->status;
        $ptp_amount         = $record->ptp_amount;
        $payment_date         = $record->payment_date;
        $assign_user 	= User::getName($record->assign_user);
        $account_number = $record->account_number;
        $assign_group 	= Groups::usersGroup($record->assign_group);
        $created_at 	= $record->created_at->diffForHumans();
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
          "full_name" 		=> $full_name,
          "status" 			=> '<center>'.$status_label.$priority_label.'</center>',
          "assign_user" 	=> $assign_user,
          "account_number" 	=> $account_number,
          "assign_group" 	=> $assign_group,
          "created_at" 		=> $created_at,
          "ptp_amount"  	=> $ptp_amount,
          "payment_date"    => $payment_date,
          "id" 			=> $id
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

   public function getPulloutLogs(Request $request){

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
     $totalRecordswithFilter = PulloutLogs::select('count(*) as allcount')
     ->where(function($query) use ($searchValue)  {
              $query->where('file_id', 'like', '%' .$searchValue . '%');
        })
     ->whereRaw('created_at >= DATE_ADD(CURDATE(), INTERVAL -4 DAY)')
     ->count();

     // Fetch records
     $records = PulloutLogs::orderBy($columnName,$columnSortOrder)
        ->where(function($query) use ($searchValue)  {
              $query->where('file_id', 'like', '%' .$searchValue . '%');
        })
       ->whereRaw('created_at >= DATE_ADD(CURDATE(), INTERVAL -4 DAY)')
       ->skip($start)
       ->take($rowperpage)
       ->get();

     $data_arr = array();

     foreach($records as $record){
        $id = $record->id;

        $file_id 			= $record->file_id;
        $file_path        	= $record->file_path;
        $file_name        	= $record->file_name;
        $pullout_type       = $record->pullout_type;
        $status 			= $record->status;
        $create_by 			= User::getName($record->user);
        $created_at 		= $record->created_at->diffForHumans();


       switch ($status) {
       	case '1':
       		$status_label = '<center><span class="label label-xl label-success label-inline mr-2">Done</span></center>';
       		$action = '<a class="btn btn-secondary font-weight-lighter mr-2" href="'.asset(Storage::url($file_path)).'"  title="Download pullout logs">Download</a>';
       		break;
       	
       	default:
       		$status_label = '<center><span class="label label-xl label-secondary label-inline mr-2">Downloading...</span></center>';
       		$action = '';
       		break;
       }
       
        $data_arr[] = array(
          "file_id" 		=> $file_id,
          "status" 			=> '<center>'.$status_label.'</center>',
          "user" 			=> $create_by,
          "pullout_type"    => $pullout_type,
          "created_at" 		=> $created_at,
          "action" 			=> $action
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
