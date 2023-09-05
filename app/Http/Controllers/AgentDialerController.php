<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use AppHelper;
use DateTime;
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
use App\Models\FileHeaders;
use App\Models\ManualNumbers;
use Illuminate\Http\Request;

class AgentDialerController extends Controller
{
    public function index($file_id){
        $user       = Auth::user();

        // if($user->level != '3'){
        //     return view('error.404');
        // }
    	$campaigns 					= Campaigns::where('file_id', $file_id)->first();
    	

        if($campaigns){
            $group_name                 = Groups::usersGroup($campaigns->group);
            return view('pages.agent.auto_dialer', compact('campaigns', 'group_name', 'file_id'));
        }else{
            return view('error.404');
        }
    	
    }

    public function previous_data($file_id, $leads_id){
        $user       = Auth::user();

        // if($user->level != '3'){
        //     return view('error.404');
        // }
        $campaigns                  = Campaigns::where('file_id', $file_id)->first();
        

        if($campaigns){
            $group_name                 = Groups::usersGroup($campaigns->group);
            return view('pages.agent.previous', compact('campaigns', 'group_name', 'file_id', 'leads_id'));
        }else{
            return view('error.404');
        }
        
    }

    public function search_account_data(Request $request){

        $user = \Auth::user();
        $q = $request->get('q');
        $collector_id = $request->collector_id;

        $records = AddedCampaignsLeads::selectRaw('added_campaigns_leads.leads_id, crm_borrowers.full_name, crm_leads.id');
        $records->leftjoin('crm_leads', 'crm_leads.id', '=', 'added_campaigns_leads.leads_id');
        $records->leftjoin('crm_borrowers', 'crm_borrowers.profile_id', '=', 'crm_leads.profile_id');
        $records->where('added_campaigns_leads.file_id', '=', $request->file_id);
        //$records->where('added_campaigns_leads.collector_id', '=', $request->collector_id);
        $records->where(function($query) use ($collector_id)  {
              $query->where('added_campaigns_leads.collector_id', '=', $collector_id)
              ->orWhere('crm_leads.assign_user', '=', $collector_id);
        });
        //$records->where('added_campaigns_leads.process', '=', 1);
        $records->where('crm_leads.deleted', '=', 0);
        $records->where(function($query) use ($q)  {
              $query->where('crm_borrowers.full_name', 'like', '%' .$q . '%')
              ->orWhere('crm_leads.account_number', 'like', '%' .$q . '%');
        });
        $records->where('crm_borrowers.full_name','!=' ,'');
        $records = $records->get();
        
        
 
        $data = [];
        foreach ($records as $key => $record) {
           
            $data[] = array(
                'id'       => $record['leads_id'],
                'text'     => $record['full_name'],
            );
           
        }
         
        return response()->json([
             'error'  => 'false',
             'items'    => $data
        ]);
 
     }

    public function view_leads_data($file_id, $user_id, $leads_id){

    	$crm_leads = AddedCampaignsLeads::select(
            [
                'added_campaigns_leads.file_id',
                'added_campaigns_leads.collector_id',
                'added_campaigns_leads.leads_id',
                'crm_leads.id',
	            'crm_leads.profile_id',
	            'crm_leads.file_id',
	            'crm_leads.status',
	            'crm_leads.priority',
	            'crm_leads.idle',
	            'crm_leads.assign_user',
	            'crm_leads.assign_group',
	            'crm_leads.account_number',
	            'crm_leads.created_at',
	            'crm_leads.outstanding_balance',
                'crm_leads.loan_amount',
                'crm_leads.cycle_day',
	            'crm_leads.endo_date',
	            'crm_leads.due_date',
                'crm_leads.payment_date',
                'crm_leads.ptp_amount',
                'crm_leads.remarks',
                'crm_leads.last_call_contact_no',
                'crm_leads.best_time_to_call',
                'crm_leads.status_updated',
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
     	$crm_leads->leftjoin('crm_leads', 'crm_leads.id', '=', 'added_campaigns_leads.leads_id');
     	$crm_leads->leftjoin('crm_borrowers', 'crm_borrowers.profile_id', '=', 'crm_leads.profile_id');
     	$crm_leads->where('added_campaigns_leads.file_id','=' ,$file_id);
     	//$crm_leads->where('added_campaigns_leads.collector_id','=' ,$user_id);
     	$crm_leads->where('added_campaigns_leads.leads_id','=' ,$leads_id);
        $crm_leads->where(function($query) use ($user_id)  {
              $query->where('added_campaigns_leads.collector_id', '=', $user_id)
              ->orWhere('crm_leads.assign_user', '=', $user_id);
        });
        $crm_leads->where('crm_borrowers.full_name','!=' ,'');
     	$crm_leads = $crm_leads->first();

         $file_headers = FileHeaders::where('group','=' ,$crm_leads->assign_group)
         ->where('assign_field','=' ,'require_field')
         ->get();

        $manual_numbers = ManualNumbers::where('profile_id','=' ,$crm_leads->profile_id)->get();

     	$temp_uploads = TempUploads::where('upload_type','=' ,'new_leads')
    						->where('header','=' ,'1')
    						->where('file_id','=' ,$crm_leads->file_id)
    						->first();
    	$temp_datas = TempUploads::where('upload_type','=' ,'new_leads')
    						->where('header','=' ,'0')
    						->where('profile_id','=' ,$crm_leads->profile_id)
    						->where('file_id','=' ,$crm_leads->file_id)
    						->first();

    	$contact_no_lists = array(
                'Cellphone No.' => $crm_leads->cellphone_no,
    			'Other Phone 1' => $crm_leads->other_phone_1,
    			'Other Phone 1' => $crm_leads->other_phone_2, 
    			'Other Phone 3' => $crm_leads->other_phone_3,
    			'Other Phone 4' => $crm_leads->other_phone_4,
    			'Other Phone 5' => $crm_leads->other_phone_5,
                'Home No.'      => $crm_leads->home_no,
                'Business No.'  => $crm_leads->business_no,
                
    		);

            $sms_no_lists = array(
                $crm_leads->cellphone_no,
                $crm_leads->other_phone_1,
                $crm_leads->other_phone_2, 
                $crm_leads->other_phone_3,
                $crm_leads->other_phone_4,
                $crm_leads->other_phone_5,
            );


            $temp_emails = TempEmails::where('group', $crm_leads->assign_group)->first();
            $temp_sms = TempSms::where('group', $crm_leads->assign_group)->first();

            if(isset($temp_emails->body)){
                $decodee1           = json_encode($temp_emails->body,true);
                $json_content1      = json_decode($decodee1);

                $email_msg = $json_content1[0];               
                $email_msg = $temp_emails->body;
                $email_msg = str_replace('{[FULL_NAME]}', $crm_leads->full_name, $email_msg);
                $email_msg = str_replace('{[ADDRESS]}', $crm_leads->address, $email_msg);
                $email_msg = str_replace('{[OUTSTANDING_BALANCE]}', $crm_leads->outstanding_balance, $email_msg);
                $email_msg = str_replace('{[DUE_DATE]}', $crm_leads->due_date, $email_msg);
                $email_msg = str_replace('{[ENDO_DATE]}', $crm_leads->due_date, $email_msg);
                $email_msg = str_replace('{[ACCOUNT_NUMER]}', $crm_leads->account_number, $email_msg);
                $email_msg = str_replace('{[PTP_DATE]}', $crm_leads->payment_date, $email_msg);
                $email_msg = str_replace('{[PTP_AMOUNT]}', $crm_leads->ptp_amount, $email_msg);
            }else{
                $email_msg = '';
            }

            if(isset($temp_emails->subject)){
                $decodee_subject           = json_encode($temp_emails->subject,true);
                $json_content_subject      = json_decode($decodee_subject);

                $email_subject = $json_content_subject[0];               
                $email_subject = $temp_emails->subject;
                $email_subject = str_replace('{[FULL_NAME]}', $crm_leads->full_name, $email_subject);
                $email_subject = str_replace('{[ADDRESS]}', $crm_leads->address, $email_subject);
                $email_subject = str_replace('{[OUTSTANDING_BALANCE]}', $crm_leads->outstanding_balance, $email_subject);
                $email_subject = str_replace('{[DUE_DATE]}', $crm_leads->due_date, $email_subject);
                $email_subject = str_replace('{[ENDO_DATE]}', $crm_leads->due_date, $email_subject);
                $email_subject = str_replace('{[ACCOUNT_NUMER]}', $crm_leads->account_number, $email_subject);
                $email_subject = str_replace('{[PTP_DATE]}', $crm_leads->payment_date, $email_subject);
                $email_subject = str_replace('{[PTP_AMOUNT]}', $crm_leads->ptp_amount, $email_subject);
            }else{
                $email_subject = '';
            }

            if(isset($temp_sms->body)){
                $decodee_sms           = json_encode($temp_sms->body,true);
                $json_content_sms      = json_decode($decodee_sms);

                $sms_msg = $json_content_sms[0];               
                $sms_msg = $temp_sms->body;
                $sms_msg = str_replace('{[FULL_NAME]}', $crm_leads->full_name, $sms_msg);
                $sms_msg = str_replace('{[ADDRESS]}', $crm_leads->address, $sms_msg);
                $sms_msg = str_replace('{[OUTSTANDING_BALANCE]}', $crm_leads->outstanding_balance, $sms_msg);
                $sms_msg = str_replace('{[DUE_DATE]}', $crm_leads->due_date, $sms_msg);
                $sms_msg = str_replace('{[ENDO_DATE]}', $crm_leads->due_date, $sms_msg);
                $sms_msg = str_replace('{[ACCOUNT_NUMER]}', $crm_leads->account_number, $sms_msg);
                $sms_msg = str_replace('{[PTP_DATE]}', $crm_leads->payment_date, $sms_msg);
                $sms_msg = str_replace('{[PTP_AMOUNT]}', $crm_leads->ptp_amount, $sms_msg);
            }else{
                $sms_msg = '';
            }

        $dialer_view_type = 'campaign_dialer';

    	return view('pages.agent.leads_data', compact('crm_leads', 'temp_uploads', 'temp_datas', 'sms_no_lists', 'contact_no_lists','temp_emails','email_msg', 'email_subject','sms_msg', 'file_id','file_headers','manual_numbers','dialer_view_type'));
    }

    private static function get_leads_one($file_id, $array_filters){
        $decode                     = json_decode($array_filters, true);
        $prioritize_new_leads       = $decode['prioritize_new_leads'];
        $one_day_before             = $decode['one_day_before'];
        $filter_cycle_day           = $decode['filter_cycle_day'];
        $filter_outstanding_balance = $decode['filter_outstanding_balance'];
        $filter_endo_date           = $decode['filter_endo_date'];
        $collector_id               = $decode['collector_id'];
        $current_account            = $decode['current_account'];
        $leads_id                   = $decode['leads_id'];

        $records = AddedCampaignsLeads::selectRaw(
                        'added_campaigns_leads.leads_id,
                        added_campaigns_leads.file_id,
                        added_campaigns_leads.dial,
                        added_campaigns_leads.process,
                        added_campaigns_leads.collector_id,    
                        crm_borrowers.full_name,
                        crm_borrowers.home_no,
                        crm_borrowers.business_no,
                        crm_borrowers.cellphone_no,
                        crm_borrowers.other_phone_1,
                        crm_borrowers.other_phone_2,
                        crm_borrowers.other_phone_3,
                        crm_borrowers.other_phone_4,
                        crm_borrowers.other_phone_5,
                        crm_leads.id,
                        crm_leads.status,
                        crm_leads.profile_id,
                        crm_leads.cycle_day,
                        crm_leads.loan_amount,
                        crm_leads.outstanding_balance,
                        crm_leads.last_call_contact_no,
                        REPLACE(endo_date, "ENDO XD-", "") as t2'
                    
                );
        $records->leftjoin('crm_leads', 'crm_leads.id', '=', 'added_campaigns_leads.leads_id');
        $records->leftjoin('crm_borrowers', 'crm_borrowers.profile_id', '=', 'crm_leads.profile_id');

        if($current_account != 0){
            $records->where('added_campaigns_leads.leads_id', '=', $current_account);
        }else if($leads_id != 0){
            $records->where('added_campaigns_leads.leads_id', '=', $leads_id);
        }

        $records->where('added_campaigns_leads.file_id', '=', $file_id);
        //$records->where('added_campaigns_leads.collector_id', '=', $collector_id);
        $records->where(function($query) use ($collector_id)  {
              $query->where('added_campaigns_leads.collector_id', '=', $collector_id)
              ->orWhere('crm_leads.assign_user', '=', $collector_id);
        });
        $records->where('crm_borrowers.full_name','!=' ,'');

        if($leads_id == 0){
            $records->where('added_campaigns_leads.process', '=', 0);
        }else{
            //$records->where('added_campaigns_leads.process', '=', 1);
        }
        
        $records->where('crm_leads.deleted', '=', 0);

        if($prioritize_new_leads == 1){
            $records->orderByRaw('crm_leads.status <> 0');
        }

        switch ($filter_endo_date) {
            case '2':
                $records->orderByRaw('substring(t2,3) DESC');
                break;
            
            default:
                $records->orderByRaw('substring(t2,3) ASC');
                break;
        }

        switch ($filter_cycle_day) {
            case '2':
                $records->orderByRaw('cast(crm_leads.cycle_day as unsigned) ASC');
                break;
            
            default:
                $records->orderByRaw('cast(crm_leads.cycle_day as unsigned) DESC');
                break;
        }

        switch ($filter_outstanding_balance) {
            case '2':
                $records->orderByRaw('cast(crm_leads.outstanding_balance as unsigned) ASC');
                break;
            
            default:
                $records->orderByRaw('cast(crm_leads.outstanding_balance as unsigned) DESC');
                break;
        }

        $records = $records->first();

        if($current_account == 0){
            $update_current_leads = array(
                'current_account'  => $records->leads_id,
                'updated_at' => Carbon::now(),
            );
                        
            AddedCampaignsAgents::where('collector_id', '=', $collector_id)
                ->where('file_id', '=', $file_id)
                ->update($update_current_leads);
        }

        return $records;
    }

    public function get_call_status_view(Request $request){

        $asterisk_status = trim(AppHelper::get_asterisk_status($request->extension, $request->contact_no));

        if($asterisk_status != ''){
            $call_flag = 1;
                switch ($asterisk_status) {
                    case 'Ringing':
                        $status_label = '<button type="button" class="btn btn-warning spinner spinner-white spinner-right">Ringing</button>';
                        $status = "Ringing";
                        break;

                    case 'Down':
                        $status_label = '<button type="button" class="btn btn-info spinner spinner-white spinner-right">Connecting</button>';
                        $status = "Connecting";
                        break;

                    case 'Up':
                        
                        $status_label = '<button type="button" class="btn btn-success spinner spinner-white spinner-right">On Call</button>';
                        $status = "On Call";
                        break;

                    default:
                       $status_label = '<button type="button" class="btn btn-secondary spinner spinner-dark spinner-right">'.$asterisk_status.'</button>';
                       $status = $asterisk_status;
                       break;
                }
                    
        }else{
            $call_flag = 0;
            $status = 0;
            $status_label = '<a href="#" class="btn btn-secondary font-weight-bolder font-size-sm" >
                                My Extension No: '.$request->extension.'
                            </a>';
        }

        return response()->json([
            'error'     => 'false',
            'call_flag' => $call_flag,
            'status'    => $status,
            'asterisk_status'    => $asterisk_status,
            'msg'       => $status_label
        ]);
    }

    public function isBetween($start_time, $end_time, $input) {

        $start_format  = date("H:i", strtotime($start_time));
        $end_format    = date("H:i", strtotime($end_time));
        $input_format    = date("H:i", strtotime($input));

        $currentTime = new DateTime($input_format);
        $startTime   = new DateTime($start_format);
        $endTime     = new DateTime($end_format);

        if ($currentTime >= $startTime && $currentTime <= $endTime) {
            return 1;
        }else{
            return 0;
        }
    }

    public function get_leads_data(Request $request){
    	$user       = Auth::user();
        $campaigns  = Campaigns::where('file_id', $request->file_id)->first();
        $start_time = $campaigns->start_time;
        $end_time   = $campaigns->end_time;
        $today_time = date('h:i A');

        

        $start_format  = date("H", strtotime($start_time));
        $end_format  = date("H", strtotime($end_time));

        if($campaigns->active_dialer == '2'){
        //the active status is equal to 2. the campaign should be pause
            return response()->json([
                'dial'                      => 0,
                'leads_id'                  => 0,
                'file_id'                   => $request->file_id,
                'contact_no'                => 0,
                'profile_id'                => 0,
                'name'                      => 0,
                'account_status'            => 0,
                'sched_time'                => 0,
                'pause_time'                => 'true',
                'active_campaign'           => 'true',
                'last_call_contact_no'      => 0,
            ]);
        }

        if($campaigns->active_dialer == '0'){
        //the active status is equal to 2. the campaign should be pause
            return response()->json([
                'dial'                      => 0,
                'leads_id'                  => 0,
                'file_id'                   => $request->file_id,
                'contact_no'                => 0,
                'profile_id'                => 0,
                'name'                      => 0,
                'account_status'            => 0,
                'sched_time'                => 0,
                'pause_time'                => 'false',
                'active_campaign'           => 'false',
                'last_call_contact_no'      => 0,
            ]);
        }

        if($this->isBetween($start_time, $end_time, $today_time) == 0){
            //Check if the schedule of campaign is equal in the 24hour format time.
            //Close the auto dialer.
            return response()->json([
                'dial'                      => 0,
                'leads_id'                  => 0,
                'file_id'                   => $request->file_id,
                'contact_no'                => 0,
                'profile_id'                => 0,
                'name'                      => 0,
                'account_status'            => 0,
                'sched_time'                => 'out',
                'pause_time'                => 'false',
                'active_campaign'           => 'true',
                'last_call_contact_no'      => 0,
            ]);

        }

        
        $total_current_access    = AddedCampaignsAgents::select('count(*) as allcount')
                                    ->where('file_id', '=', $request->file_id)
                                    ->where('collector_id','=' ,$request->user_id)
                                    ->where('current_account','!=' ,0)
                                    ->count();

        $total_current_leads    = AddedCampaignsLeads::select('count(*) as allcount')
                                    ->leftjoin('crm_leads', 'crm_leads.id', '=', 'added_campaigns_leads.leads_id')
                                    ->where('added_campaigns_leads.file_id', '=', $request->file_id)
                                    ->where('added_campaigns_leads.collector_id','=' ,$request->user_id)
                                    ->where('added_campaigns_leads.process','=' ,0)
                                    ->where('crm_leads.deleted','=' ,0)
                                    ->count();

        if($total_current_leads == 0){
            //Check if the collector has no available leads.
            //If true the auto dialer will stop.
            return response()->json([
                'dial'                      => 0,
                'leads_id'                  => 0,
                'file_id'                   => $request->file_id,
                'contact_no'                => 0,
                'profile_id'                => 0,
                'name'                      => 0,
                'account_status'            => 0,
                'sched_time'                => 'in',
                'pause_time'                => 'false',
                'active_campaign'           => 'true',
                'last_call_contact_no'      => 0,
            ]);
        }

        $get_campaigns_agents = AddedCampaignsAgents::where('file_id', $request->file_id)->where('collector_id','=' ,$request->user_id)->first();

            if($total_current_access == 0){
            //Check if the collector has current accounts that need to be updated.
            //if Zero total current access. the auto dialer will  trigger another call.
                $array_filters = array(
                    'prioritize_new_leads'          => $campaigns->prioritize_new_leads,
                    'one_day_before'                => $campaigns->one_day_before,
                    'filter_cycle_day'              => $campaigns->filter_cycle_day,
                    'filter_outstanding_balance'    => $campaigns->filter_outstanding_balance,
                    'filter_endo_date'              => $campaigns->filter_endo_date,
                    'collector_id'                  => $request->user_id,
                    'current_account'               => 0,
                    'leads_id'                      => 0,
                );

            }else{
            //Get the last touched accounts.
                $array_filters = array(
                    'prioritize_new_leads'          => $campaigns->prioritize_new_leads,
                    'one_day_before'                => $campaigns->one_day_before,
                    'filter_cycle_day'              => $campaigns->filter_cycle_day,
                    'filter_outstanding_balance'    => $campaigns->filter_outstanding_balance,
                    'filter_endo_date'              => $campaigns->filter_endo_date,
                    'collector_id'                  => $request->user_id,
                    'current_account'               => $get_campaigns_agents->current_account,
                    'leads_id'                      => 0,
                );

                

            }

            $crm_leads = $this->get_leads_one($request->file_id, json_encode($array_filters));

            

            if(isset($crm_leads->leads_id)){
                $leads_id           = $crm_leads->leads_id;
            }else{
                $array_filters = array(
                    'prioritize_new_leads'          => $campaigns->prioritize_new_leads,
                    'one_day_before'                => $campaigns->one_day_before,
                    'filter_cycle_day'              => $campaigns->filter_cycle_day,
                    'filter_outstanding_balance'    => $campaigns->filter_outstanding_balance,
                    'filter_endo_date'              => $campaigns->filter_endo_date,
                    'collector_id'                  => $request->user_id,
                    'current_account'               => 0,
                    'leads_id'                      => 0,
                );
                $crm_leads = $this->get_leads_one($request->file_id, json_encode($array_filters));
                $leads_id           = $crm_leads->leads_id;
            }
            $file_id                  = $request->file_id;
            $dial                     = $crm_leads->dial;
            $account_status           = $get_campaigns_agents->account_status;
            $last_call_contact_no     = $crm_leads->last_call_contact_no;

            

            //Get the list of contact no. of the accounts
            $contact_no_lists = array(
                (string)$crm_leads->cellphone_no,
                (string)$crm_leads->other_phone_1,
                (string)$crm_leads->other_phone_2, 
                (string)$crm_leads->other_phone_3,
                (string)$crm_leads->other_phone_4,
                (string)$crm_leads->other_phone_5,
                (string)$crm_leads->home_no,
                (string)$crm_leads->business_no,
            );
            $array_mobile_clean = [];
            foreach ($contact_no_lists as $key => $value) {
                if(!empty($value)){
                    $array_mobile_clean[] = (string)$value;
                }
            }
           
            if(count($array_mobile_clean) == 0){
                //if happen that accounts has no contact no. it will return 0
                $call_contact_no    = 0;
            }else if(count($array_mobile_clean) == 1){
                //return 1 contact no.
                $call_contact_no    = $array_mobile_clean[0];
            }else{
                //Random select contact no.
                $random_keys        =   array_rand($array_mobile_clean,count($array_mobile_clean));
                $call_contact_no    = $array_mobile_clean[$random_keys[0]];
            }
            

            return response()->json([
                'dial'                   => $dial,
                'leads_id'               => $leads_id,
                'file_id'                => $file_id,
                'contact_no'             => $call_contact_no,
                'profile_id'             => $crm_leads->profile_id,
                'name'                   => $crm_leads->full_name,
                'account_status'         => $account_status,
                'sched_time'             => 'in',
                'pause_time'             => 'false',
                'active_campaign'        => 'true',
                'last_call_contact_no'   => $last_call_contact_no,
            ]);
    	
    }

    public function get_previous_leads_data(Request $request){
        $user       = Auth::user();
        $campaigns  = Campaigns::where('file_id', $request->file_id)->first();
        $start_time = $campaigns->start_time;
        $end_time   = $campaigns->end_time;
        $today_time = date('H');
        $leads_id   = $request->leads_id;
        

        $start_format   = date("H", strtotime($start_time));
        $end_format     = date("H", strtotime($end_time));

        if($campaigns->active_dialer == '2'){
        //the active status is equal to 2. the campaign should be pause
            return response()->json([
                'dial'                      => 0,
                'leads_id'                  => 0,
                'file_id'                   => $request->file_id,
                'contact_no'                => 0,
                'profile_id'                => 0,
                'name'                      => 0,
                'account_status'            => 0,
                'sched_time'                => 0,
                'pause_time'                => 'true',
                'active_campaign'           => 'true',
                'last_call_contact_no'      => 0,
            ]);
        }

        if($campaigns->active_dialer == '0'){
        //the active status is equal to 2. the campaign should be pause
            return response()->json([
                'dial'                   => 0,
                'leads_id'               => 0,
                'file_id'                => $request->file_id,
                'contact_no'             => 0,
                'profile_id'             => 0,
                'name'                   => 0,
                'account_status'         => 0,
                'sched_time'             => 0,
                'pause_time'             => 'false',
                'active_campaign'        => 'false',
                'last_call_contact_no'   => 0,
            ]);
        }

        if((int)$today_time <= (int)$start_format && (int)$today_time >= (int)$end_format){
            //Check if the schedule of campaign is equal in the 24hour format time.
            //Close the auto dialer.
            return response()->json([
                'dial'                   => 0,
                'leads_id'               => 0,
                'file_id'                => $request->file_id,
                'contact_no'             => 0,
                'profile_id'             => 0,
                'name'                   => 0,
                'account_status'         => 0,
                'sched_time'             => 'out',
                'pause_time'             => 'false',
                'active_campaign'        => 'true',
                'last_call_contact_no'   => 0,
            ]);

        }

            $get_campaigns_agents = AddedCampaignsAgents::where('file_id', $request->file_id)->where('collector_id','=' ,$request->user_id)->first();

            $array_filters = array(
                'prioritize_new_leads'          => $campaigns->prioritize_new_leads,
                'one_day_before'                => $campaigns->one_day_before,
                'filter_cycle_day'              => $campaigns->filter_cycle_day,
                'filter_outstanding_balance'    => $campaigns->filter_outstanding_balance,
                'filter_endo_date'              => $campaigns->filter_endo_date,
                'collector_id'                  => $request->user_id,
                'current_account'               => 0,
                'leads_id'                      => $leads_id,
            );

            $crm_leads = $this->get_leads_one($request->file_id, json_encode($array_filters));

            $leads_id                 = $crm_leads->leads_id;
            $file_id                  = $request->file_id;
            $dial                     = $crm_leads->dial;
            $account_status           = $get_campaigns_agents->account_status;
            $last_call_contact_no     = $crm_leads->last_call_contact_no;

            

            //Get the list of contact no. of the accounts
            $contact_no_lists = array(
                (string)$crm_leads->cellphone_no,
                (string)$crm_leads->other_phone_1,
                (string)$crm_leads->other_phone_2, 
                (string)$crm_leads->other_phone_3,
                (string)$crm_leads->other_phone_4,
                (string)$crm_leads->other_phone_5,
                (string)$crm_leads->home_no,
                (string)$crm_leads->business_no,
            );
            $array_mobile_clean = [];
            foreach ($contact_no_lists as $key => $value) {
                if(!empty($value)){
                    $array_mobile_clean[] = (string)$value;
                }
            }
           
            if(count($array_mobile_clean) == 0){
                //if happen that accounts has no contact no. it will return 0
                $call_contact_no    = 0;
            }else if(count($array_mobile_clean) == 1){
                //return 1 contact no.
                $call_contact_no    = $array_mobile_clean[0];
            }else{
                //Random select contact no.
                $random_keys        =   array_rand($array_mobile_clean,count($array_mobile_clean));
                $call_contact_no    = $array_mobile_clean[$random_keys[0]];
            }
            

            return response()->json([
                'dial'                   => $dial,
                'leads_id'               => $leads_id,
                'file_id'                => $file_id,
                'contact_no'             => $call_contact_no,
                'profile_id'             => $crm_leads->profile_id,
                'name'                   => $crm_leads->full_name,
                'account_status'         => $account_status,
                'sched_time'             => 'in',
                'pause_time'             => 'false',
                'active_campaign'        => 'true',
                'last_call_contact_no'   => $last_call_contact_no,
            ]);

        
    }

    public function logged_status(Request $request){
    	$user = Auth::user();

    	$update_login = array(
            'logged_in'  	  => $request->status,
            'account_status'  => 0,
            'updated_at' 	  => Carbon::now(),
        );
                
        AddedCampaignsAgents::where('collector_id', '=', $request->id)
        		->where('file_id', '=', $request->file_id)
        		->update($update_login);

        $update_login_user = array(
            'dialer_loggin'  => $request->status,
        );
                
        User::where('id', '=', $request->id)->update($update_login_user);

        CampaignLogs::saveLogs($user->id, $request->file_id, 0, (($request->status == '1') ? 'logged_in':'logged_out' ), '');

        return response()->json([
            'error'  => 'false',
            'msg'    => 'ok'
        ]);
    }

    public function check_campaign_login(Request $request){
    	$user = Auth::user();

    	$get_added_campaigns_agents    = AddedCampaignsAgents::where('collector_id', '=', $request->id)
    										->where('logged_in', '=', 1)
    										->first();

    	if(isset($get_added_campaigns_agents['file_id'])){
    		$loggin = 1;
    		$file_id = $get_added_campaigns_agents['file_id'];
    	}else{
    		$loggin = 0;
    		$file_id = 0;
    	}

        return response()->json([
            'error'  	=> 'false',
            'file_id'   => $file_id,
            'loggin'    => $loggin,
        ]);
    }

    public function pause_status(Request $request){
    	$user = Auth::user();

    	$update_login = array(
            'account_status'  => $request->status,
            'updated_at' 	  => Carbon::now(),
        );
                
        AddedCampaignsAgents::where('collector_id', '=', $request->id)
        					->where('file_id', '=', $request->file_id)
        					->update($update_login);

        CampaignLogs::saveLogs($user->id, $request->file_id, 0, (($request->status == '2') ? 'pause':'unpaused' ), '');

        return response()->json([
            'error'  => 'false',
            'msg'    => 'ok'
        ]);
    }

    public function break_time_status(Request $request){
    	$user = Auth::user();

    	$update_login = array(
            'account_status'  => $request->status,
            'updated_at' 	  => Carbon::now(),
        );
                
        AddedCampaignsAgents::where('collector_id', '=', $request->id)
        					->where('file_id', '=', $request->file_id)
        					->update($update_login);

        CampaignLogs::saveLogs($user->id, $request->file_id, 0, (($request->status == '3') ? 'break-time':'break-out' ), '');

        return response()->json([
            'error'  => 'false',
            'msg'    => 'ok'
        ]);
    }

    public function agent_call(Request $request){
    	$user = Auth::user();
    	//get post variables
		// $m = '09158352413';
        $m = env('ASTERISK_PREFIX').$request->contact_no;
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
        //$strChannel = "local/5000@from-internal-custom";
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

		$insert_data = array(
            'call_id'     	  => $call_id,
            'profile_id'      => $request->profile_id,
            'contact_no'      => $request->contact_no,
            'extension'       => $user->extension,
            'call_by'      	  => $user->id,
            'leads_id'        => $request->leads_id,
            'created_at'      => Carbon::now(),
            'updated_at'      => Carbon::now(),
        );
        CrmCallLogs::insert($insert_data);

        CrmLogs::saveLogs($user->id, $request->profile_id, $request->name, 'Call has been triggered');

        $update_account_status = array(
            'account_status'  => 1,
            'contact_no'      => $request->contact_no,
            'updated_at' 	  => Carbon::now(),
        );
                
        AddedCampaignsAgents::where('collector_id', '=', $user->id)
        					->where('file_id', '=', $request->file_id)
        					->update($update_account_status);

        $update_leads_status = array(
            'dial'  		=> 1,
            'dial_date'     => Carbon::now(),
            'updated_at' 	=> Carbon::now(),
        );
                
        AddedCampaignsLeads::where('leads_id', '=', $request->leads_id)
        				->where('file_id', '=', $request->file_id)
        				->update($update_leads_status);

        
        $update_last_contact = array(
            'last_call_contact_no'      => $request->contact_no,
            'updated_at'                => Carbon::now(),
        );
                
        CrmLeads::where('id', '=', $request->leads_id)
                        ->update($update_last_contact);
		// print 'call success';

		return response()->json([
                'error'  => 'false',
                'msg'    => 'call success',
        ]);
    }
}
