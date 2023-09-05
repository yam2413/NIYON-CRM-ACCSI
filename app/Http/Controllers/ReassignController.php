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
use Illuminate\Http\Request;

class ReassignController extends Controller
{
    public function index(){
     	$groups   = Groups::get();
    	return view('pages.leads.reassign.index', compact('groups'));
    }

    public function get_user_list(Request $request){
        $users   = User::where('group','=' ,$request->group)->orderBy('name','DESC')->get();
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

    public function get_new_user_list(Request $request){
        $users   = User::where('group','=' ,$request->group)->where('id','!=' ,$request->user)->orderBy('name','DESC')->get();
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

    public function update_reassign(Request $request){
    	$user = Auth::user();
        $post_sync = array(
            'assign_user'   => $request->new_collector,
            'updated_at'    => Carbon::now(),
        );
        
        $users = User::find($request->new_collector);
        CrmLeads::where('id', '=', $request->leads_id)->update($post_sync);

        $crm_leads   = CrmLeads::where('id','=' ,$request->leads_id)->first();
        $crm_borrowers   = CrmBorrowers::where('profile_id','=' ,$crm_leads->profile_id)->first();
        CrmLogs::saveLogs($user->id, $crm_leads->profile_id, $crm_borrowers->full_name, 'Account successfully re-assign to '.$users->name.'.');
        
        return response()->json([
           'error'  => 'false',
           'msg'    => 'Account successfully re-assign.'
        ]);
    }

    public function getLeadsReAssign(Request $request){

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
     $totalRecordswithFilter->where('crm_leads.assign_user','=' ,$request->collector);
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
     $records->where('crm_leads.assign_user','=' ,$request->collector);
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


        $action_btn   = '<center>
        					<a href="'.route('pages.leads.profile', ['profile_id' => $profile_id]).'" class="btn btn-icon btn-outline-secondary btn-circle btn-sm mr-2" title="View account details">
								<i class="fas fa-user-cog"></i>
							</a>
						</center>';


        $data_arr[] = array(
          "full_name" 		=> $full_name,
          "status" 			=> '<center>'.$status.$priority_label.'</center>',
          "assign_user" 	=> $assign_user,
          "account_number" 	=> $account_number,
          "assign_group" 	=> $assign_group,
          "created_at" 		=> $created_at,
          "ptp_amount"  => $ptp_amount,
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
}
