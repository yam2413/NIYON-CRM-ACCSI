<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use AppHelper;
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
use App\Models\Statuses;
use App\Jobs\ActivateCampaignJob;
use App\Jobs\AutoAssignActiveCampJob;
use App\Jobs\AddLeadsCampaignJob;
use App\Models\CampaignLogs;
use Illuminate\Http\Request;

class AutoDialerController extends Controller
{
    public function index(){
    	return view('pages.auto_dialer.index');
    }

    public function create(){
    	$groups   = Groups::get();
        $filter_status = array(
            '0' => 'New Leads',
            '1' => 'Promise to Pay',
            '2' => 'Below Promise to Pay',
            '3' => 'Broken Promise',
        );
    	return view('pages.auto_dialer.create', compact('groups', 'filter_status'));
    }

    public static function select_my_campaign(){
        $user = Auth::user();

        $added_campaigns_agents = Campaigns::select(
            [
                'campaigns.*',
                'added_campaigns_agents.collector_id',
                'added_campaigns_agents.file_id',
            ]
        );
        $added_campaigns_agents->leftjoin('added_campaigns_agents', 'added_campaigns_agents.file_id', '=', 'campaigns.file_id');
        $added_campaigns_agents->where('added_campaigns_agents.collector_id','=' ,$user->id);
        $added_campaigns_agents->where('campaigns.group','=' ,$user->group);
        return $added_campaigns_agents = $added_campaigns_agents->get();
        
        
    }

    public function dialer($file_id){

    	$campaigns 					= Campaigns::where('file_id', $file_id)->first();
    	$group_name 				= Groups::usersGroup($campaigns->group);
        $list_users                 = User::where('group', $campaigns->group)->get();

    	$added_campaigns_agents = User::select(
            [
                'users.*',
                'added_campaigns_agents.collector_id',
                'added_campaigns_agents.file_id',
            ]
        );
     	$added_campaigns_agents->leftjoin('added_campaigns_agents', 'added_campaigns_agents.collector_id', '=', 'users.id');
     	$added_campaigns_agents->where('added_campaigns_agents.file_id','=' ,$file_id);
     	$added_campaigns_agents->where('users.group','=' ,$campaigns->group);
     	$added_campaigns_agents = $added_campaigns_agents->get();

        $array_collectors = [];
        foreach ($added_campaigns_agents as $key => $value) {
            $array_collectors[] = $value['id'];
        }
        

    	return view('pages.auto_dialer.dialer', compact('campaigns', 'file_id', 'group_name', 'added_campaigns_agents', 'list_users','array_collectors'));
    }


    public function edit($file_id){
    	$groups   = Groups::get();
    	$campaigns = Campaigns::where('file_id', $file_id)->first();

    	return view('pages.auto_dialer.edit', compact('campaigns', 'groups', 'file_id'));
    }

    public function view_assign($id, $file_id){
        $user           = User::where('id', $id)->first();
        $campaign_agent = AddedCampaignsAgents::where('file_id', $file_id)->where('collector_id', $id)->first();
        $campaigns = Campaigns::where('file_id', $file_id)->first();

        $statuses   = Statuses::where('group','=' ,$user->group);
        $statuses->orderByRaw('status_name <> "NEW",status_name <> "BEST TIME TO CALL"');
        $statuses = $statuses->get();

        return view('pages.auto_dialer.view_assign', compact('id', 'file_id', 'user', 'campaign_agent', 'statuses','campaigns'));
    }

    public function add_leads($file_id){
    	
    	$campaigns = Campaigns::where('file_id', $file_id)->first();
        $groups    = Groups::where('id','=' ,$campaigns->group)->get();

    	$filter_status = Statuses::where('group','=' ,$campaigns->group);
        $filter_status->orderByRaw('status_name <> "NEW",status_name <> "BEST TIME TO CALL"');
        $filter_status = $filter_status->get();

    	return view('pages.auto_dialer.add_leads', compact('campaigns', 'groups', 'file_id', 'filter_status'));
    }

    public function get_total_added_leads(Request $request){
    	$user = \Auth::user();
        
        $total_leads = AddedCampaignsLeads::select('count(*) as allcount')
                            ->where('file_id','=',$request->file_id)
                            ->count();

        $total_dials = AddedCampaignsLeads::select('count(*) as allcount')
                            ->where('file_id','=',$request->file_id)
                            ->where('dial','=',1)
                            ->count();

        $total_current_leads    = AddedCampaignsLeads::select('count(*) as allcount')
                            ->leftjoin('crm_leads', 'crm_leads.id', '=', 'added_campaigns_leads.leads_id')
                            ->where('added_campaigns_leads.file_id', '=', $request->file_id)
                            ->where('crm_leads.deleted','=' ,1)
                            ->count();

        $total_agents = AddedCampaignsAgents::select('count(*) as allcount')
                            ->where('file_id', '=', $request->file_id)
                            ->count();
        $total_agents_in_call = AddedCampaignsAgents::select('count(*) as allcount')
                            ->where('file_id', '=', $request->file_id)
                            ->where('account_status', '=', 1)
                            ->count();

        $total_agents_paused = AddedCampaignsAgents::select('count(*) as allcount')
                            ->where('file_id', '=', $request->file_id)
                            ->where('account_status', '=', 2)
                            ->count();

        $total_agents_logged_in = AddedCampaignsAgents::select('count(*) as allcount')
                            ->where('file_id', '=', $request->file_id)
                            ->where('logged_in', '=', 1)
                            ->count();

        $get_campaigns = Campaigns::where('file_id','=',$request->file_id)->first();

          return response()->json([
            'total_leads'               => $total_leads,
            'total_agents'              => $total_agents,
            'total_dials'               => $total_dials,
            'total_agents_in_call'      => $total_agents_in_call,
            'total_agents_paused'       => $total_agents_paused,
            'total_agents_logged_in'    => $total_agents_logged_in,
            'total_current_leads'       => $total_current_leads,
            'active_dialer'             => $get_campaigns['active_dialer'],
            'sync_all_leads'            => $get_campaigns['sync_all_leads'],
        ]);
    }

    public function update(Request $request){
        $user = Auth::user();

        $post_sync = array(
            'group'       		            => ($user->level == 0) ? $request->groups:$user->group,
            'start_time'      	            => $request->start_time,
            'end_time'                      => $request->end_time,
            'campaign_name'                 => $request->campaign_name,
            'one_day_before'                => ($request->one_day_before == 'true') ? 1:0,
            'prioritize_new_leads'          => ($request->prioritize_new_leads == 'true') ? 1:0,
            'filter_cycle_day'              => ($request->filter_cycle_day) ? $request->filter_cycle_day:0,
            'filter_outstanding_balance'    => ($request->filter_outstanding_balance) ? $request->filter_outstanding_balance:0,
            'filter_endo_date'              => ($request->filter_endo_date) ? $request->filter_endo_date:0,
            'auto_assign'                   => ($request->auto_assign == 'true') ? 1:0,
            'updated_at'                    => Carbon::now(),
        );
        
        Campaigns::where('id', '=', $request->id)->update($post_sync);
        SystemLogs::saveLogs($user->id, $request->campaign_name.' campaign has been updated ');
        
        return response()->json([
                'error'  => 'false',
                'msg'    => 'Campaign updated successfully'
            ]);
    }

    public function add_leads_to_campaign(Request $request){
        $user = Auth::user();

        $insert_data = array(
            'file_id'       	=> $request->file_id,
            'leads_id'       	=> $request->leads_id,
            'created_at'        => Carbon::now(),
            'updated_at'        => Carbon::now(),
        );
        AddedCampaignsLeads::insert($insert_data);
        //SystemLogs::saveLogs($user->id, $request->campaign_name.' added accounts leads ');
        
        return response()->json([
                'error'  => 'false',
                'msg'    => 'Accounts added successfully in the campaign'
            ]);
    }

    public function add_all_leads_to_campaign(Request $request){
        $user = Auth::user();

        $post_sync = array(
            'sync_all_leads'  => 1,    
        ); 
        Campaigns::where('file_id', '=', $request->file_id)->update($post_sync);
        AddLeadsCampaignJob::dispatch($request->file_id, $user->id);
        SystemLogs::saveLogs($user->id, $request->campaign_name.' campaign added all accounts in leads ');
        
        return response()->json([
            'error'  => 'false',
            'msg'    => 'Accounts added successfully in the campaign'
        ]);
    }

    public function add_collectors_to_campaign(Request $request){
        $user = Auth::user();
        $array = [];
        $array_explode = explode(',', $request->listbox_collectors);

        $collectors_remove  = AddedCampaignsAgents::where('file_id', '=', $request->file_id)->delete();

        // if($collectors_remove){
            if(!empty($array_explode)){
                foreach ($array_explode as $key => $value) {
            
                    $insert_data = array(
                        'file_id'           => $request->file_id,
                        'collector_id'      => $value,
                        'created_at'        => Carbon::now(),
                        'updated_at'        => Carbon::now(),
                    );
                    AddedCampaignsAgents::insert($insert_data);
                }
            }
            
        // }
        

        SystemLogs::saveLogs($user->id, $request->campaign_name.' campaign added collectors');
        
        return response()->json([
                'error'  => 'false',
                'msg'    => 'Collectors added successfully in the campaign'
            ]);
    }

    public function activate_campaign(Request $request){
        $user = Auth::user();
        $uniq_id =  substr(md5(rand(999, 999999)), 0, 3).date('YmdHis');
        $total_added_campaigns_agents    = AddedCampaignsAgents::select('count(*) as allcount')->where('file_id', '=', $request->file_id)->count();
        $total_added_campaigns_leads    = AddedCampaignsLeads::select('count(*) as allcount')
                                            ->where('file_id', '=', $request->file_id)
                                            ->where('dial', '!=', 1)
                                            ->where('process', '!=', 1)
                                            ->count();

        if($total_added_campaigns_leads == 0){
            return response()->json([
                'error'  => 'true',
                'msg'    => 'No Leads, Please add leads before you activate this campaign'
            ]);
        }

        if($request->auto_assign == 0){

            if($total_added_campaigns_agents == 0){
                return response()->json([
                    'error'  => 'true',
                    'msg'    => 'No Collectors, Please add collectors before you activate this campaign'
                ]);
            }

        }
        

        $post_campaigns = array(
            'active_dialer'     => 2,
            'updated_at'        => Carbon::now(),
        );
                
        Campaigns::where('file_id', '=', $request->file_id)->update($post_campaigns);

        if($request->auto_assign == 0){
            ActivateCampaignJob::dispatch($request->file_id);
        }else{
            AutoAssignActiveCampJob::dispatch($request->file_id);
        }
        
        SystemLogs::saveLogs($user->id, $request->campaign_name.' campaign has been activated');
        

        return response()->json([
            'error'  => 'false',
            'msg'    => 'Campaign successfully activated. the call will start engage once the collector is logged in.'
        ]);
    }

    public function pause_campaign(Request $request){
        $user = Auth::user();
        if($request->active_dialer == 1){
            $status = 2;
            $text = 'pause';
        }else{
            $status = 1;
            $text = 'unpaused';
        }
        $post_campaigns = array(
            'active_dialer'     => $status,
            'updated_at'        => Carbon::now(),
        );
                
        Campaigns::where('file_id', '=', $request->file_id)->update($post_campaigns);
        SystemLogs::saveLogs($user->id, $request->campaign_name.' campaign has been '.$text);
        return response()->json([
            'error'  => 'false',
            'msg'    => 'Campaign successfully '.$text.'.'
        ]);
    }

    public function disabled_campaign(Request $request){
        $user = Auth::user();
       
        $post_campaigns = array(
            'active_dialer'     => 0,
            'updated_at'        => Carbon::now(),
        );
                
        Campaigns::where('file_id', '=', $request->file_id)->update($post_campaigns);
        SystemLogs::saveLogs($user->id, $request->campaign_name.' campaign has been disabled');
        return response()->json([
            'error'  => 'false',
            'msg'    => 'Campaign successfully disabled.'
        ]);
    }

    public function store(Request $request){
        $user = Auth::user();
    	$uniq_id =  substr(md5(rand(999, 999999)), 0, 3).date('YmdHis');

        $insert_data = array(
            'created_by'                    => $user->id,
            'file_id'       	            => $uniq_id,
            'group'       		            => ($user->level == 0) ? $request->groups:$user->group,
            'start_time'      	            => $request->start_time,
            'end_time'                      => $request->end_time,
            'campaign_name'                 => $request->campaign_name,
            'one_day_before'                => ($request->one_day_before == 'true') ? 1:0,
            'prioritize_new_leads'          => ($request->prioritize_new_leads == 'true') ? 1:0,
            'filter_cycle_day'              => ($request->filter_cycle_day) ? $request->filter_cycle_day:0,
            'filter_outstanding_balance'    => ($request->filter_outstanding_balance) ? $request->filter_outstanding_balance:0,
            'filter_endo_date'              => ($request->filter_endo_date) ? $request->filter_endo_date:0,
            'auto_assign'                   => ($request->auto_assign == 'true') ? 1:0,
            'created_at'                    => Carbon::now(),
            'updated_at'                    => Carbon::now(),
        );
        $insert_campaign = Campaigns::insert($insert_data);

        if($insert_campaign){
            $post_sync = array(
                'sync_all_leads'  => 1,    
            ); 
            Campaigns::where('file_id', '=', $uniq_id)->update($post_sync);
            AddLeadsCampaignJob::dispatch($uniq_id, $user->id);
            SystemLogs::saveLogs($user->id, $request->campaign_name.' campaign added all accounts in leads ');

        }
        


        SystemLogs::saveLogs($user->id, $request->campaign_name.' campaign has been created ');

    	 return response()->json([
                'error'     => 'false',
                'file_id'   => $uniq_id,
                'msg'       => 'Campaign successfully created'
         ]);
    }

    public function reset_campaign(Request $request){
        $agent = \Auth::user();
        $campaigns = Campaigns::where('file_id', '=', $request->file_id)->first();

        $post_campaigns = array(
            'active_dialer'     => 0,
            'updated_at'        => Carbon::now(),
        );
                
        Campaigns::where('file_id', '=', $request->file_id)->update($post_campaigns);

        $added_campaigns_leads  = AddedCampaignsLeads::where('file_id', '=', $request->file_id);
        $added_campaigns_agents = AddedCampaignsAgents::where('file_id', '=', $request->file_id);
        $campaign_logs          = CampaignLogs::where('file_id', '=', $request->file_id);

        SystemLogs::saveLogs($agent->id, $campaigns->campaign_name.' campaign has been reset ');
        $added_campaigns_leads->delete();
        $added_campaigns_agents->delete();
        $campaign_logs->delete();

        return response()->json([
            'error'  => 'false',
            'msg'    => 'Campaign '.$campaigns->name.' successfully reset.'
        ]);
    }

    public function delete(Request $request){
        $agent = \Auth::user();
        $campaigns              = Campaigns::find($request->id);
        $added_campaigns_leads  = AddedCampaignsLeads::where('file_id', '=', $campaigns->file_id);
        $added_campaigns_agents = AddedCampaignsAgents::where('file_id', '=', $campaigns->file_id);

        SystemLogs::saveLogs($agent->id, $campaigns->campaign_name.' campaign has been deleted ');
        $campaigns->delete();
        $added_campaigns_leads->delete();
        $added_campaigns_agents->delete();

        return response()->json([
            'error'  => 'false',
            'msg'    => 'Campaign '.$campaigns->name.' Successfully deleted.'
        ]);
    }

    public function getCampaign(Request $request){

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
     //$totalRecords = User::select('count(*) as allcount')->count();
     $totalRecordswithFilter = Campaigns::select('count(*) as allcount');
     $totalRecordswithFilter->where(function($query) use ($searchValue)  {
              $query->where('campaign_name', 'like', '%' .$searchValue . '%')
                      ->orWhere('file_id', 'like', '%' .$searchValue . '%');
        });
     if($user->level == 2 || $user->level == 1){
        $totalRecordswithFilter->where('group','=' ,$user->group);
     }
     $totalRecordswithFilter = $totalRecordswithFilter->count();

     // Fetch records

     $records = Campaigns::orderBy($columnName,$columnSortOrder);
     $records->where(function($query) use ($searchValue)  {
              $query->where('campaign_name', 'like', '%' .$searchValue . '%')
                      ->orWhere('file_id', 'like', '%' .$searchValue . '%');
     });
     
     if($user->level == 2 || $user->level == 1){
        $records->where('group','=' ,$user->group);
     }
     
     $records->skip($start);
     $records->take($rowperpage);
     $records = $records->get();

     $data_arr = array();

     foreach($records as $record){
        $id = $record->id;

        $campaign_name 		= ucwords($record->campaign_name);
        $file_id 			= $record->file_id;
        $active_dialer 		= $record->active_dialer;
        $start_time 		= $record->start_time;
        $end_time 			= $record->end_time;
        $created_by 		= User::getName($record->created_by);
        $group 				= Groups::usersGroup($record->group);
        $created_at 		= $record->created_at->diffForHumans();

         $action_btn = '<div class="dropdown">
						    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						    <i class="flaticon-more-1"></i>
						    </button>
						    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
						        <a class="dropdown-item" href="'.route('pages.auto_dialer.dialer', ['file_id' => $file_id]).'"  title="Dialer Campaign Details">Dialer Campaign</a>
						        <a href="#" id="'.$id.'" alt="'.$campaign_name.'" class="dropdown-item delete_campaign" href="#">Delete Campaign</a>
						    </div>
						</div>';

         switch ($active_dialer) {
            case '0':
                $status_label = '<span class="label label-xl label-danger label-inline mr-2">Deactivated</span>';
                break;

            case '1':
                $status_label = '<span class="label label-xl label-success label-inline mr-2">Activated</span>';
                break;

            case '2':
                $status_label = '<span class="label label-xl label-warning label-inline mr-2">Pause</span>';
                break;
        }

        $data_arr[] = array(
          "file_id" 		=> $file_id,
          "campaign_name" 	=> $campaign_name,
          "active_dialer" 	=> $status_label,
          "start_time" 		=> $start_time,
          "end_time" 		=> $end_time,
          "group"        	=> $group,
          "created_by" 		=> $created_by,
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

   public function getAddLeads(Request $request){

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
     $totalRecordswithFilter->where('crm_leads.deleted','=' ,0);
     
     if($request->status != ''){
        $totalRecordswithFilter->where('crm_leads.status','=' ,$request->status);
     }
     $totalRecordswithFilter->where('crm_leads.status','!=' ,4);
     $totalRecordswithFilter->whereRaw('crm_leads.id NOT IN (SELECT b.leads_id FROM added_campaigns_leads b)');
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
     $records->where('crm_leads.deleted','=' ,0);
     if($request->status != '0'){
        $records->where('crm_leads.status','=' ,$request->status);
     }
     $records->whereRaw('crm_leads.id NOT IN (SELECT b.leads_id FROM added_campaigns_leads b)');
     $records->skip($start);
     $records->take($rowperpage);
     $records = $records->get();

     $data_arr = array();

     foreach($records as $record){

        $id 			= $record->id;
        $profile_id 	= $record->profile_id;
        $full_name 		= $record->full_name;
        $status 		= $record->status;
        $ptp_amount     = $record->ptp_amount;
        $payment_date   = $record->payment_date;
        $assign_user 	= User::getName($record->assign_user);
        $account_number = $record->account_number;
        $assign_group 	= Groups::usersGroup($record->assign_group);
        $created_at 	= $record->created_at->diffForHumans();
        $priority     	= $record->priority;


        $data_arr[] = array(
          "full_name" 		=> $full_name,
          "status" 			=> '<center>'.$status.'</center>',
          "account_number" 	=> $account_number,
          "created_at" 		=> $created_at,
          "id" 				=> $id
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

   public function getCampaignCollectors(Request $request){

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
     //$totalRecords = User::select('count(*) as allcount')->count();
     $totalRecordswithFilter = AddedCampaignsAgents::select('count(*) as allcount');
     $totalRecordswithFilter->leftjoin('users', 'users.id', '=', 'added_campaigns_agents.collector_id');
     $totalRecordswithFilter->where(function($query) use ($searchValue)  {
              $query->where('users.name', 'like', '%' .$searchValue . '%');
        });
     $totalRecordswithFilter->where('added_campaigns_agents.file_id','=' ,$request->file_id);
     $totalRecordswithFilter->where('users.group','=' ,$request->group);
     $totalRecordswithFilter = $totalRecordswithFilter->count();

     // Fetch records
     $records = AddedCampaignsAgents::select(
            [
                'added_campaigns_agents.collector_id',
                'added_campaigns_agents.file_id',
                'added_campaigns_agents.account_status',
                'added_campaigns_agents.current_account',
                'added_campaigns_agents.logged_in',
                'added_campaigns_agents.contact_no',
                'users.*',
            ]
        );
     $records->orderByRaw('added_campaigns_agents.logged_in <> 1');
     //$records->orderBy($columnName,$columnSortOrder);
     $records->leftjoin('users', 'users.id', '=', 'added_campaigns_agents.collector_id');
     $records->where(function($query) use ($searchValue)  {
              $query->where('users.name', 'like', '%' .$searchValue . '%');
        });
     $records->where('added_campaigns_agents.file_id','=' ,$request->file_id);
     $records->where('users.group','=' ,$request->group);
     $records->skip($start);
     $records->take($rowperpage);
     $records = $records->get();

     $data_arr = array();

     foreach($records as $record){
        $id = $record->id;

        $name                 = $record->name;
        $account_status       = $record->account_status;
        $logged_in            = $record->logged_in;
        $extension            = $record->extension;
        $action               = "";
        $dialplan_listen      = "*222".$extension;
        $dialplan_whisper     = "*223".$extension;
        $dialplan_barge       = "*224".$extension;

        $contact_no          = $record->contact_no;

        if($logged_in == 0){
            $status_label = '<span class="label label-xl label-warning label-inline mr-2">Logout</span>';
        }else{
            $asterisk_status = trim(AppHelper::get_asterisk_status($extension, $contact_no));
            if($asterisk_status != ''){
                switch ($asterisk_status) {
                    case 'Ringing':
                        $status_label = '<span class="label label-xl label-warning label-inline mr-2">Ringing</span>';
                        break;

                    case 'Down':
                        $status_label = '<span class="label label-xl label-info label-inline mr-2">Connecting</span>';
                        break;

                    case 'Up':
                        

                        switch ($user->level) {
                            case '0':
                                $action = '<div class="btn-group btn-group-toggle" data-toggle="buttons">
                                        <label class="btn btn-secondary">
                                            <a href="#" class="btn-listen" id="'.$dialplan_listen.'">Listen</a>
                                        </label>
                                        <label class="btn btn-secondary">
                                            <a href="#" class="btn-whisper" id="'.$dialplan_whisper.'">Whisper</a>
                                        </label>
                                        <label class="btn btn-secondary">
                                            <a href="#" class="btn-barge" id="'.$dialplan_barge.'">Barge</a>
                                        </label>
                                    </div>';
                                break;

                            case '1':
                                if(env('DIALER_M_ACCESS_VOICE_M') == 'true'){
                                    $action = '<div class="btn-group btn-group-toggle" data-toggle="buttons">
                                        <label class="btn btn-secondary">
                                            <a href="#" class="btn-listen" id="'.$dialplan_listen.'">Listen</a>
                                        </label>
                                        <label class="btn btn-secondary">
                                            <a href="#" class="btn-whisper" id="'.$dialplan_whisper.'">Whisper</a>
                                        </label>
                                        <label class="btn btn-secondary">
                                            <a href="#" class="btn-barge" id="'.$dialplan_barge.'">Barge</a>
                                        </label>
                                    </div>';
                                }
                                break;
                            
                            case '2':
                                if(env('DIALER_M_ACCESS_VOICE_M') == 'true'){
                                    $action = '<div class="btn-group btn-group-toggle" data-toggle="buttons">
                                        <label class="btn btn-secondary">
                                            <a href="#" class="btn-listen" id="'.$dialplan_listen.'">Listen</a>
                                        </label>
                                        <label class="btn btn-secondary">
                                            <a href="#" class="btn-whisper" id="'.$dialplan_whisper.'">Whisper</a>
                                        </label>
                                        <label class="btn btn-secondary">
                                            <a href="#" class="btn-barge" id="'.$dialplan_barge.'">Barge</a>
                                        </label>
                                    </div>';
                                }
                                break;
                        }
                        
                        $status_label = '<span class="label label-xl label-success label-inline mr-2">On Call</span>';
                        break;

                    default:
                       $status_label = '<span class="label label-xl label-secondary label-inline mr-2">'.$asterisk_status.'</span>';
                       break;
                    
                }
                
            }else{

                switch ($account_status) {
                    case '0':
                        $status_label = '<span class="label label-xl label-secondary label-inline mr-2">Lobby</span>';
                        break;

                    case '1':
                        if($asterisk_status == ''){
                            $status_label = '<span class="label label-xl label-secondary label-inline mr-2">Lobby</span>';
                        }else{
                            $status_label = '<span class="label label-xl label-success label-inline mr-2">Dialling</span>';
                        }
                        
                        break;

                    case '2':
                        $status_label = '<span class="label label-xl label-info label-inline mr-2">Pause</span>';
                        break;

                    case '3':
                        $status_label = '<span class="label label-xl label-info label-inline mr-2">Break Time</span>';
                        break;

                    default:
                       $status_label = '<span class="label label-xl label-secondary label-inline mr-2">Lobby</span>';
                       break;
                }

            }
            
        }

        
        


        $data_arr[] = array(
          "action"          => $record->id,
          "status"          => '<center>'.$status_label.'</center>',
          "name"            => $name,
          "file_id"         => $record->file_id,
          "monitoring"      => $action
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

   public function getLeadsAssign(Request $request){

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
     $totalRecordswithFilter = AddedCampaignsLeads::select('count(*) as allcount');
     $totalRecordswithFilter->leftjoin('crm_leads', 'crm_leads.id', '=', 'added_campaigns_leads.leads_id');
     $totalRecordswithFilter->leftjoin('crm_borrowers', 'crm_borrowers.profile_id', '=', 'crm_leads.profile_id');
     $totalRecordswithFilter->where(function($query) use ($searchValue)  {
              $query->where('crm_borrowers.full_name', 'like', '%' .$searchValue . '%')
              ->orWhere('crm_leads.account_number', 'like', '%' .$searchValue . '%');
        });
     $totalRecordswithFilter->where('added_campaigns_leads.collector_id','=' ,$request->collector);
     $totalRecordswithFilter->where('added_campaigns_leads.file_id','=' ,$request->file_id);
     $totalRecordswithFilter->where('crm_leads.deleted','=' ,0);
     if($request->status != '0'){
        $totalRecordswithFilter->where('crm_leads.status','=' ,$request->status);
     }
        
     
     $totalRecordswithFilter = $totalRecordswithFilter->count();

     // Fetch records
     $records = AddedCampaignsLeads::select(
            [
                'added_campaigns_leads.file_id',
                'added_campaigns_leads.collector_id',
                'added_campaigns_leads.leads_id',
                'added_campaigns_leads.dial',
                'added_campaigns_leads.process',
                'added_campaigns_leads.process_time',
                'added_campaigns_leads.process_date',
                'added_campaigns_leads.dial_date',
                'crm_leads.*',
                'crm_borrowers.full_name',
                'crm_borrowers.birthday',
                'crm_borrowers.email',
                'crm_borrowers.address',
                'crm_borrowers.home_no',
                'crm_borrowers.business_no',
                'crm_borrowers.cellphone_no',
                'crm_borrowers.other_phone_1',
                'crm_borrowers.other_phone_2',
                'crm_borrowers.other_phone_3',
                'crm_borrowers.other_phone_4',
                'crm_borrowers.other_phone_5',
            ]
        );
     $records->orderByRaw('crm_leads.priority <> 1');
     $records->orderBy($columnName,$columnSortOrder);
     $records->leftjoin('crm_leads', 'crm_leads.id', '=', 'added_campaigns_leads.leads_id');
     $records->leftjoin('crm_borrowers', 'crm_borrowers.profile_id', '=', 'crm_leads.profile_id');
     $records->where(function($query) use ($searchValue)  {
              $query->where('crm_borrowers.full_name', 'like', '%' .$searchValue . '%')
              ->orWhere('crm_leads.account_number', 'like', '%' .$searchValue . '%');
        });
     $records->where('added_campaigns_leads.collector_id','=' ,$request->collector);
     $records->where('added_campaigns_leads.file_id','=' ,$request->file_id);
     $records->where('crm_leads.deleted','=' ,0);
     if($request->status != '0'){
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
          "dial"                => ($record->dial == 1) ? 'YES':'NO',
          "process"             => ($record->process == 1) ? 'YES':'NO',
          "process_time"        => ($record->process_time != '') ? $record->process_time:'--',
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

   public function manager_call(Request $request){
        $user = Auth::user();
        //get post variables
        // $m = '*2221000';
        $m = $request->dial_plan;
        //$m = $request->contact_no;
        $voicelinkExt = $user->extension;
        $strHost = env('ASTERISK_HOST');
        #Based on the Click-To-Call script brought to you by VoipJots.com
        #Modified by Rafael Cortes for Asterisk PBXS www.asteriskpbxs.com

        # THIS IS IMPORTANT HERE #
        ## This one does not need a custom-context ##
        #


        #------------------------------------------------------------------------------------------
        #edit the below variable values to reflect your system/information
        #------------------------------------------------------------------------------------------

        #specify the name/ip address of your asterisk box
        #if your are hosting this page on your asterisk box, then you can use
        #127.0.0.1 as the host IP.  Otherwise, you will need to edit the following
        #line in manager.conf, under the Admin user section:
        #permit=127.0.0.1/255.255.255.0
        #change to:
        #permit=127.0.0.1/255.255.255.0,xxx.xxx.xxx.xxx ;(the ip address of the server this page is running on)


        #specify site port
        $port = env('ASTERISK_PORT');

        #specify the username you want to login with (these users are defined in /etc/asterisk/manager.conf)
        #this user is the default AAH AMP user; you shouldn't need to change, if you're using AAH.
        $strUser = env('ASTERISK_USERNAME');

        #specify the password for the above user
        $strSecret = env('ASTERISK_PASSWORD');

        #specify the channel (extension) you want to receive the call requests with
        #e.g. SIP/XXX, IAX2/XXXX, ZAP/XXXX, local/1NXXNXXXXXX@from-internal, etc
        $strChannel = "local/".$voicelinkExt."@from-internal";
        //$strChannel = "local/6000@from-internal-custom";
        //$strChannel = "local/6000@from-internal-custom";
        #$strChannel = "local/".$voicelinkExt;
        #$strChannel = "SIP/109";

        //Use this for your cell phone Number;

        #specify the context to make the outgoing call from.  By default, AAH uses from-internal
        #Using from-internal will make you outgoing dialing rules apply
        $strContext = "from-internal";

        #specify the amount of time you want to try calling the specified channel before hangin up
        $strWaitTime = "30";

        #specify the priority you wish to place on making this call
        $strPriority = "1";

        #specify the maximum amount of retries
        $strMaxRetry = "2";

        #--------------------------------------------------------------------------------------------
        #Shouldn't need to edit anything below this point to make this script work
        #--------------------------------------------------------------------------------------------
        #get the phone number from the posted form

        if ( substr($m,0,1) == '9' ){
            $mobile = "0".$m;
        }

        if ( substr($m,0,2) == "63" ){
            $mobile = "+".$m;
        }

        $strExten = $m;

        $callNumber = $strExten;

        #specify the caller id for the call
        $call_id =  time();
        $strCallerId = "$call_id <$callNumber>";

        $length = strlen($strExten);

        $oSocket = fsockopen($strHost, $port, $errnum, $errdesc) or die("Connection to host failed");
        fputs($oSocket, "Action: login\r\n");
        fputs($oSocket, "Events: off\r\n");
        fputs($oSocket, "Username: $strUser\r\n");
        fputs($oSocket, "Secret: $strSecret\r\n\r\n");
        fputs($oSocket, "Action: originate\r\n");
        fputs($oSocket, "Channel: $strChannel\r\n");
        fputs($oSocket, "WaitTime: $strWaitTime\r\n");
        fputs($oSocket, "CallerId: $strCallerId\r\n");
        fputs($oSocket, "Exten: $strExten\r\n");
        fputs($oSocket, "Context: $strContext\r\n"); 
        fputs($oSocket, "Priority: 1\r\n\r\n");
        fputs($oSocket, "Action: Logoff\r\n\r\n");
        sleep(3);
        fclose($oSocket);

        // print 'call success';

         return response()->json([
            'error'  => 'false',
            'msg'    => 'Tes call successfully engage'
        ]);

    }
}
