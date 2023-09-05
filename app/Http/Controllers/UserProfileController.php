<?php

namespace App\Http\Controllers;

use Auth;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Groups;
use App\Models\SystemLogs;
use App\Models\CrmLogs;
use App\Models\CampaignLogs;
use Illuminate\Http\Request;

class UserProfileController extends Controller
{
    public function index(){
    	$user = Auth::user();
    	$groups   = Groups::get();
    	$group_name = Groups::usersGroup($user->group);
    	$level 		= User::usersRole($user->level);
    	return view('pages.user_profile.index', compact('user', 'groups','group_name', 'level'));
    }

    public function change_password(){
    	$user = Auth::user();
    	$groups   = Groups::get();
    	$group_name = Groups::usersGroup($user->group);
    	$level 		= User::usersRole($user->level);
    	return view('pages.user_profile.change_password', compact('user', 'groups','group_name', 'level'));
    }

    public function my_activity(){
    	$user = Auth::user();
    	$groups   = Groups::get();
    	$group_name = Groups::usersGroup($user->group);
    	$level 		= User::usersRole($user->level);
    	return view('pages.user_profile.my_activity', compact('user', 'groups','group_name', 'level'));
    }


    public function update(Request $request){
        $user = Auth::user();

        $email = User::where('email', $request['email'])->where('id','!=' ,$request->id)->first();

        if($email){
            return response()->json([
                'error'  => 'true',
                'msg'    => 'Email is already exists.'
            ]);
        }

        $name = ucfirst($request['firstname']).' '.ucfirst($request['lastname']);

        $uniq_id =  substr(md5(rand(999, 999999)), 0, 10);
        
        if($request->file != 'undefined'){
            $files = $request->file;
            $profileImage = $uniq_id . "." . $files->getClientOriginalExtension();
            $path = $request->file->storeAs('public/avatar', $profileImage);
        }else{
            $path = NULL;
        }

        $post_sync = array(
            'name'       		=> $name,
            'lastname'       	=> ucfirst($request->lastname),
            'firstname'       	=> ucfirst($request->firstname),
            'email'      		=> $request->email,
            'avatar'            => $path,
            'updated_at'        => Carbon::now(),
        );
        
        User::where('id', '=', $request->id)->update($post_sync);
        SystemLogs::saveLogs($user->id, $name.' account has been updated ');
        
        return response()->json([   
                'error'  => 'false',
                'msg'    => 'Successfully update personal information'
            ]);
    }

    public function getMyactivity(Request $request){

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
     $crm_logs_cnt = CrmLogs::select('count(id) as allcount')->where('crm_logs.user','=',$user->id)->count();

     $campaign_logs_cnt = CampaignLogs::select('count(id) as allcount')->where('campaign_logs.user','=',$user->id)->count();

     $totalRecordswithFilter = SystemLogs::select('count(id) as allcount');
     $totalRecordswithFilter->where('system_logs.user','=' ,$user->id);
     $totalRecordswithFilter = $totalRecordswithFilter->count();

     $total_all = $campaign_logs_cnt+$crm_logs_cnt+$totalRecordswithFilter;
     // Fetch records
    $crm_logs = CrmLogs::select(
            [
                'crm_logs.actions',
                'crm_logs.created_at AS fdate',
                'crm_logs.created_at',
            ]
        )->where('crm_logs.user','=',$user->id);

    $campaign_logs = CampaignLogs::select(
            [
                'campaign_logs.log_type as actions',
                'campaign_logs.created_at  AS fdate',
                'campaign_logs.created_at',
            ]
        )->where('campaign_logs.user','=',$user->id);

    $records = SystemLogs::select(
            [
                'system_logs.actions',
                'system_logs.created_at  AS fdate',
                'system_logs.created_at',
            ]
        );
     $records->union($crm_logs)->orderByRaw('fdate desc')->get();
     $records->union($campaign_logs)->orderByRaw('fdate desc')->get();
     $records->where(function($query) use ($searchValue)  {
              $query->where('actions', 'like', '%' .$searchValue . '%');
     });
     $records->where('system_logs.user','=' ,$user->id);

     $records->skip($start);
     $records->take($rowperpage);
     $records = $records->get();

     $data_arr = array();

     foreach($records as $record){
        //$id 				= $record->id;
        $actions 			= $record->actions;
        $created_at 		= $record->created_at->diffForHumans();;

         

        $data_arr[] = array(
          "actions" 		=> $actions,
          "created_at" 		=> $created_at
        );
     }

     $response = array(
        "draw" => intval($draw),
        "iTotalRecords" => $total_all,
        "iTotalDisplayRecords" => $total_all,
        "aaData" => $data_arr
     );

     echo json_encode($response);
     exit;
   }
}
