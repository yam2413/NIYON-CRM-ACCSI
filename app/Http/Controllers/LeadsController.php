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
use App\Mail\LeadsEmail;
use App\Jobs\SendEmailJob;
use App\Jobs\SendSMSJob;
use App\Models\Campaigns;
use App\Models\AddedCampaignsLeads;
use App\Models\AddedCampaignsAgents;
use App\Models\FileHeaders;
use App\Models\ManualNumbers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class LeadsController extends Controller
{
     public function index(){
        $user = \Auth::user();
     	$groups   = Groups::get();
        $filter_status = array(
            'Priority' => 'Priority',
            '0' => 'New Leads',
            '1' => 'Promise to Pay',
            '2' => 'Below Promise to Pay',
            '3' => 'Broken Promise',
            '4' => 'Paid',
            'Answered' => 'Answered Call',
            'Unanswered' => 'Unanswered Call',
        );

        $last_activitys = CrmLogs::select(
            [
                'crm_logs.*',
                'crm_borrowers.full_name',
            ]
        );
        $last_activitys->orderBy('crm_logs.created_at','DESC');
        $last_activitys->join('crm_borrowers', 'crm_borrowers.profile_id', '=', 'crm_logs.profile_id');
        $last_activitys->where('actions','!=' ,'Account has been touched');
        $last_activitys->where('user','=' ,$user->id);
        $last_activitys->skip(0);
        $last_activitys->take(5);
        $last_activitys = $last_activitys->get();


    	return view('pages.leads.index', compact('groups','filter_status','last_activitys'));
    }

    public function profile($profile_id){
        $user = \Auth::user();

    	$crm_leads = CrmLeads::select(
            [
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
	                'crm_leads.endo_date',
	                'crm_leads.due_date',
                    'crm_leads.payment_date',
                    'crm_leads.ptp_amount',
                    'crm_leads.remarks',
                    'crm_leads.best_time_to_call',
                    'crm_leads.status_updated',
	            ]
	        )
	     	->leftjoin('crm_borrowers', 'crm_borrowers.profile_id', '=', 'crm_leads.profile_id')
	     	->where('crm_leads.profile_id', '=', $profile_id)
	     	->where('crm_borrowers.profile_id', '=', $profile_id)
            ->where('crm_leads.deleted', '=', 0)
	        ->first();

            if(!$crm_leads){
                return redirect()->route('pages.leads.index');
            }

            $file_headers = FileHeaders::where('group','=' ,$crm_leads->assign_group)
    						->where('assign_field','=' ,'require_field')
    						->get();

	        $temp_uploads = TempUploads::where('upload_type','=' ,'new_leads')
    						->where('header','=' ,'1')
    						->where('file_id','=' ,$crm_leads->file_id)
    						->first();
                            
    		$temp_datas = TempUploads::where('upload_type','=' ,'new_leads')
    						->where('header','=' ,'0')
    						->where('profile_id','=' ,$profile_id)
    						->where('file_id','=' ,$crm_leads->file_id)
    						->first();

            $users   = User::where('group','=' ,$crm_leads->assign_group)->where('id','!=' ,$crm_leads->assign_user)->get();

    		$contact_no_lists = array(
    			$crm_leads->home_no,
    			$crm_leads->business_no,
    			$crm_leads->cellphone_no,
    			$crm_leads->other_phone_1,
    			$crm_leads->other_phone_2, 
    			$crm_leads->other_phone_3,
    			$crm_leads->other_phone_4,
    			$crm_leads->other_phone_5,
    		);

            $sms_no_lists = array(
                $crm_leads->cellphone_no,
                $crm_leads->other_phone_1,
                $crm_leads->other_phone_2, 
                $crm_leads->other_phone_3,
                $crm_leads->other_phone_4,
                $crm_leads->other_phone_5,
            );

            
            $manual_numbers = ManualNumbers::where('profile_id','=' ,$crm_leads->profile_id)->get();
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

        $check_access = CrmLogs::select('count(*) as allcount')
                            ->where('profile_id','=' ,$profile_id)
                            ->where('actions','=' ,'Account has been touched')
                            ->whereRaw('DATE(created_at) = "'.date('Y-m-d').'" ')
                            ->count();
        if($check_access == 0){
            CrmLogs::saveLogs($user->id, $profile_id, $crm_leads->full_name, 'Account has been touched');
        }
            CrmLogs::saveLogs($user->id, $profile_id, $crm_leads->full_name, 'View the account');
        

    	return view('pages.leads.profile', compact('crm_leads','temp_uploads','temp_datas','sms_no_lists', 'contact_no_lists','users','temp_emails','email_msg', 'email_subject','sms_msg', 'file_headers','manual_numbers'));
    }

    public function send_email(Request $request){
        $user = \Auth::user();
        $cron_id = time();
        $insert_data = array(
            'profile_id'          => $request->profile_id,
            'cron_id'             => $cron_id,
            'type'                => 'email',
            'to'                  => $request->borrower_email,
            'body'                => $request->email_body,
            'user'                => $user->id,
            'created_at'          => Carbon::now(),
            'updated_at'          => Carbon::now(),
        );
        CronActivities::insert($insert_data);

        SendEmailJob::dispatch($cron_id,$request->borrower_email,$request->email_body, $request->email_subject);

        CrmLogs::saveLogs($user->id, $request->profile_id, $request->name, 'Send Email');
         return response()->json([
            'error'  => 'false',
            'msg'    => 'Successfully send email'
        ]);
    }

    public function get_group_status(Request $request){
        $user = Auth::user();

        $statuses   = Statuses::where('group','=' ,$request->group);
        $statuses->orderByRaw('status_name <> "NEW",status_name <> "BEST TIME TO CALL"');

        if(isset($request->except)){
            if($request->except == 'NEW'){
                $statuses->where('status_name','!=' , 'NEW');
            }else if($request->except == 'BEST TIME TO CALL'){
                $statuses->where('status_name','!=' , 'BEST TIME TO CALL');
            }
        }
        
        
        $statuses = $statuses->get();
        
        $get_data = [];

        foreach ($statuses as $key => $status) {
           $get_data[] = array(
                'id'    => $status->id,
                'name'  => $status->status_name
           );
        }

        return response()->json([
            'error'  => 'false',
            'get_data'   => $get_data
        ]);

    }

    public function send_sms(Request $request){
        $user = \Auth::user();
        $cron_id = time();
        $insert_data = array(
            'profile_id'          => $request->profile_id,
            'cron_id'             => $cron_id,
            'type'                => 'sms',
            'to'                  => $request->sms_mobile,
            'body'                => $request->sms_body,
            'user'                => $user->id,
            'created_at'          => Carbon::now(),
            'updated_at'          => Carbon::now(),
        );
        CronActivities::insert($insert_data);

        SendSMSJob::dispatch($cron_id, $request->sms_mobile, $request->sms_body);

         CrmLogs::saveLogs($user->id, $request->profile_id, $request->name, 'Send SMS');
         return response()->json([
            'error'  => 'false',
            'msg'    => 'Successfully send sms'
        ]);
    }

    public static function send_sms_auto($profile_id, $mobile){
        $user = \Auth::user();

        $crm_leads = CrmLeads::select(
            [
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
                    'crm_leads.endo_date',
                    'crm_leads.due_date',
                    'crm_leads.payment_date',
                    'crm_leads.ptp_amount',
                ]
            )
            ->leftjoin('crm_borrowers', 'crm_borrowers.profile_id', '=', 'crm_leads.profile_id')
            ->where('crm_leads.profile_id', '=', $profile_id)
            ->where('crm_borrowers.profile_id', '=', $profile_id)
            ->where('crm_leads.deleted', '=', 0)
            ->first();

        $temp_sms = TempSms::where('group', $crm_leads->assign_group)->first();

        if($temp_sms->body){
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

        $cron_id = time();
        $insert_data = array(
            'profile_id'          => $profile_id,
            'cron_id'             => $cron_id,
            'type'                => 'sms',
            'to'                  => $mobile,
            'body'                => $sms_msg,
            'user'                => $user->id,
            'created_at'          => Carbon::now(),
            'updated_at'          => Carbon::now(),
        );
        CronActivities::insert($insert_data);

        SendSMSJob::dispatch($cron_id, $mobile, $sms_msg);

        CrmLogs::saveLogs($user->id, $profile_id, $crm_leads->full_name, 'Send SMS');
    }

    public static function send_email_auto($profile_id){
        $user = \Auth::user();

        $crm_leads = CrmLeads::select(
            [
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
                    'crm_leads.endo_date',
                    'crm_leads.due_date',
                    'crm_leads.payment_date',
                    'crm_leads.ptp_amount',
                ]
            )
            ->leftjoin('crm_borrowers', 'crm_borrowers.profile_id', '=', 'crm_leads.profile_id')
            ->where('crm_leads.profile_id', '=', $profile_id)
            ->where('crm_borrowers.profile_id', '=', $profile_id)
            ->where('crm_leads.deleted', '=', 0)
            ->first();

            $temp_emails = TempEmails::where('group', $crm_leads->assign_group)->first();

            if($temp_emails->body){
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

            if($temp_emails->subject){
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

        $cron_id = time();
        $insert_data = array(
            'profile_id'          => $profile_id,
            'cron_id'             => $cron_id,
            'type'                => 'email',
            'to'                  => $crm_leads->email,
            'body'                => $email_msg,
            'user'                => $user->id,
            'created_at'          => Carbon::now(),
            'updated_at'          => Carbon::now(),
        );
        CronActivities::insert($insert_data);

        SendEmailJob::dispatch($cron_id,$crm_leads->email,$email_msg, $email_subject);

        CrmLogs::saveLogs($user->id, $profile_id, $crm_leads->full_name, 'Send Email');
    }

    public function store_ptp(Request $request){
        $user = Auth::user();

        if($request->payment_date){
            $ptp_expode = explode('/', $request->payment_date);
            $ptp_date = $ptp_expode[2].'-'.$ptp_expode[0].'-'.$ptp_expode[1];
        }

        // if(isset($request->status)){
        //     if($request->status != $request->leads_status){
        //         $status_date = date('Y-m-d H:i');
        //     }else{
        //         $status_date = $request->status_updated;
        //     }

        // }else{
        //     $status_date = $request->status_updated;
        // }

        $status_date = date('Y-m-d H:i');

        $post_sync = array(
            'status'              => (isset($request->status)) ? $request->status:$request->leads_status,
            'status_updated'      => $status_date,
            'payment_date'        => (isset($request->payment_date)) ? $ptp_date:'',
            'ptp_amount'          => (isset($request->payment_amount)) ? $request->payment_amount:'',
            'remarks'             => (isset($request->remarks)) ? $request->remarks:'',
            'best_time_to_call'   => (isset($request->best_time)) ? date("Y-m-d H:i", strtotime($request->best_time)):'',
            'updated_at'          => Carbon::now(),
        );
        
        CrmLeads::where('id', '=', $request->leads_id)->update($post_sync);

        $insert_data = array(
            'payment_no'          => time(),
            'profile_id'          => $request->profile_id,
            'leads_id'            => $request->leads_id,
            'assign_group'        => $request->group,
            'status'              => (isset($request->status)) ? $request->status:$request->leads_status,
            'payment_date'        => (isset($request->payment_date)) ? $ptp_date:'',
            'payment_amount'      => (isset($request->payment_amount)) ? $request->payment_amount:'',
            'remarks'             => $request->remarks,
            'attempt'             => 'outgoing',
            'call_status'         => $request->call_status,
            'place_call'          => (isset($request->place_call)) ? $request->place_call:'--',
            'contact_type'        => (isset($request->contact_type)) ? $request->contact_type:'--',
            'created_by'          => $user->id,
            'process_time'        => $request->time_spent,
            'created_at'          => Carbon::now(),
            'updated_at'          => Carbon::now(),
        );
        CrmPtpHistories::insert($insert_data);

        if($request->send_email_ptp == 'true'){
            $this->send_email_auto($request->profile_id);
        }

        if($request->send_sms_ptp == 'true'){
            $this->send_sms_auto($request->profile_id, $request->mobile);
        }

        if(isset($request->audo_dialer)){

            switch ($request->audo_dialer) {
                case 'campaign_dialer':
                    $update_agent_status = array(
                        'current_account' => 0,
                        'account_status'  => 0,
                        'contact_no'      => '',
                        'updated_at' => Carbon::now(),
                    );
                            
                    AddedCampaignsAgents::where('collector_id', '=', $user->id)
                                        ->where('file_id', '=', $request->file_id)
                                        ->update($update_agent_status);

                    $update_leads_status = array(
                        'process'       => 1,
                        'process_time'  => $request->time_spent,
                        'process_date'  => Carbon::now(),
                        'updated_at'    => Carbon::now(),
                    );
                            
                    AddedCampaignsLeads::where('leads_id', '=', $request->leads_id)
                                    ->where('file_id', '=', $request->file_id)
                                    ->update($update_leads_status);
                    break;
                
                case 'status_dialer':
                    $update_agent_status = array(
                        'current_account' => 0,
                        'account_status'  => 0,
                        'contact_no'      => '',
                        'updated_at' => Carbon::now(),
                    );
                            
                    AddedCampaignsAgents::where('collector_id', '=', $user->id)
                                        ->update($update_agent_status);

                    $update_leads_status = array(
                        'process'       => 1,
                        'process_time'  => $request->time_spent,
                        'process_date'  => Carbon::now(),
                        'updated_at'    => Carbon::now(),
                    );
                            
                    AddedCampaignsLeads::where('leads_id', '=', $request->leads_id)
                                    ->update($update_leads_status);
                    break;
            }
            
        }
        
        CrmLogs::saveLogs($user->id, $request->profile_id, $request->name, 'New Entry Added');
        return response()->json([
                'error'  => 'false',
                'msg'    => 'New Entry successfully saved'
            ]);
    }

    public function update_personal_details(Request $request){
        $user = Auth::user();

        $post_sync = array(
            'address'       => $request->pd_address,
            'email'         => $request->pd_email,
            'home_no'       => $request->pd_home_no,
            'business_no'   => $request->pd_business_no,
            'cellphone_no'  => $request->pd_cp_no,
            'other_phone_1' => $request->pd_other_no1,
            'other_phone_2' => $request->pd_other_no2,
            'other_phone_3' => $request->pd_other_no3,
            'other_phone_4' => $request->pd_other_no4,
            'other_phone_5' => $request->pd_other_no5,
            'updated_at'    => Carbon::now(),
        );
        
        CrmBorrowers::where('profile_id', '=', $request->profile_id)->update($post_sync);
        
        CrmLogs::saveLogs($user->id, $request->profile_id, $request->name, 'Updated personal details');
        return response()->json([
                'error'  => 'false',
                'msg'    => 'Successfully updated personal details'
            ]);
    }


    public function set_as_priority(Request $request){
        $user = Auth::user();

        $post_sync = array(
            'priority'      => $request->flag,
            'updated_at'    => Carbon::now(),
        );
        
        CrmLeads::where('profile_id', '=', $request->profile_id)->where('id', '=', $request->leads_id)->update($post_sync);
        
        CrmLogs::saveLogs($user->id, $request->profile_id, $request->name, 'Account successfully set a priority');
        return response()->json([
                'error'  => 'false',
                'msg'    => 'Account successfully set a priority'
            ]);
    }

    public function update_reassign(Request $request){

        $post_sync = array(
            'assign_user'   => $request->reassign,
            'updated_at'    => Carbon::now(),
        );
        
        $users = User::find($request->reassign);
        CrmLeads::where('profile_id', '=', $request->profile_id)->where('id', '=', $request->leads_id)->update($post_sync);
        CrmLogs::saveLogs($user->id, $request->profile_id, $request->name, 'Account successfully re-assign to '.$users->name.'.');
        
        return response()->json([
                'error'  => 'false',
                'msg'    => 'Account successfully re-assign.'
            ]);
    }

    public function get_contact_no(Request $request){
        $manual_numbers = ManualNumbers::where('profile_id','=' ,$request->profile_id)->get();
        $crm_borrowers = CrmBorrowers::where('profile_id','=' ,$request->profile_id)->first();

        $get_data = [];

        $contact_no_lists = array(
                'Cellphone No.' => $crm_borrowers->cellphone_no,
                'Other Phone 1' => $crm_borrowers->other_phone_1,
                'Other Phone 1' => $crm_borrowers->other_phone_2, 
                'Other Phone 3' => $crm_borrowers->other_phone_3,
                'Other Phone 4' => $crm_borrowers->other_phone_4,
                'Other Phone 5' => $crm_borrowers->other_phone_5,
                'Home No.'      => $crm_borrowers->home_no,
                'Business No.'  => $crm_borrowers->business_no,
                
            );
        foreach ($contact_no_lists as $key => $contact_no_list) {
            if($contact_no_list == ''){
                continue;
            }
           $get_data[] = array(
                'id'    => $contact_no_list,
                'name'  => $key.'('.$contact_no_list.')'
           );
        }
            

        foreach ($manual_numbers as $key => $manual_number) {
           $get_data[] = array(
                'id'    => $manual_number->contact_no,
                'name'  => $manual_number->field_name.' ('.$manual_number->contact_no.')'
           );
        }

        return response()->json([
            'error'  => 'false',
            'get_data'   => $get_data
        ]);

    }

    public function store_manual_number(Request $request){
        $user = Auth::user();
        $array = [];

        $data1 = json_encode($request->manual_numbers);
        $data = json_decode($data1);
        foreach ($data as $key => $value) {
            if($value->field_name == null){
                return response()->json([
                    'error'  => 'true',
                    'msg'    => 'There is a empty contact type field'
             ]);
                
            }
            if($value->contact_no == null){
                return response()->json([
                    'error'  => 'true',
                    'msg'    => 'There is a empty contact no'
             ]);
                 
            }

        }

        $deleted = ManualNumbers::where('profile_id', $request->profile_id)->delete();
        if($deleted){
            foreach ($data as $key => $value) {

                $insert_data = array(
                    'profile_id'        => $request->profile_id,
                    'field_name'        => $value->field_name,
                    'contact_no'        => $value->contact_no,
                    'added_by'          => $user->id,
                    'created_at'        => Carbon::now(),
                    'updated_at'        => Carbon::now(),
                );
                ManualNumbers::insert($insert_data);
               
            }
        }

        return response()->json([
            'error'  => 'false',
            'msg'    => 'Update Contact No.'
        ]);

    }

    public function get_best_time_to_call(Request $request){

        $get_data = [];

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
                        crm_leads.best_time_to_call'
                    
        );
        $records->leftjoin('crm_leads', 'crm_leads.id', '=', 'added_campaigns_leads.leads_id');
        $records->leftjoin('crm_borrowers', 'crm_borrowers.profile_id', '=', 'crm_leads.profile_id');
        $records->where('added_campaigns_leads.file_id', '=', $request->file_id);
        $records->where('added_campaigns_leads.collector_id', '=', $request->collector_id);
        $records->where('crm_leads.deleted', '=', 0);
        $records->where('crm_leads.status', '=', 'BEST TIME TO CALL');
        $records->whereRaw('crm_leads.best_time_to_call BETWEEN NOW() AND DATE_ADD(NOW(), INTERVAL 30 MINUTE)');
        $records = $records->get();

        foreach ($records as $key => $value) {

            $datetime_1 = date('Y-m-d H:i'); 
            $datetime_2 = $value->best_time_to_call;
             
            $start_datetime = new DateTime($datetime_1); 
            $diff = $start_datetime->diff(new DateTime($datetime_2));

            $before = date("g:i A", strtotime($value->best_time_to_call)); 

            $get_data[] = array(
                'id'            => $value->id,
                'full_name'     => $value->full_name,
                'time'          => $diff->i.' minutes before '.$before
           );
        }

        return response()->json([
            'error'  => 'false',
            'total'  => count($records),
            'get_data'   => $get_data
        ]);
    }

    public function manual_call(Request $request){
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

		// print 'call success';

		return response()->json([
                'error'  => 'false',
                'msg'    => 'call success',
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
             if($request->collector != '0' && $request->collector != ''){
                $totalRecordswithFilter->where('crm_leads.assign_user','=' ,$request->collector);
             }
             break;
         
         default:
             $totalRecordswithFilter->where('assign_group','=' ,$user->group);
             $totalRecordswithFilter->where('crm_leads.assign_user','=' ,$user->id);
             break;
     }
     
     $totalRecordswithFilter->where('crm_leads.deleted','=' ,0);

        if($request->status != ''){
            switch ($request->status) {
                case '0':
                    $totalRecordswithFilter->where('crm_leads.status','=' ,'0');
                    break;

                case 'Priority':
                    $totalRecordswithFilter->where('crm_leads.priority','=' ,'1');
                    break;
                
                default:
                    $totalRecordswithFilter->where('crm_leads.status','=' ,$request->status);
                    $totalRecordswithFilter->whereRaw('DATE(crm_leads.payment_date) <= "'.$request->date.'" ');
                    break;
            }

        }
        
     
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
             if($request->collector != '0' && $request->collector != ''){
                $records->where('crm_leads.assign_user','=' ,$request->collector);
             }
             break;
         
         default:
             $records->where('assign_group','=' ,$user->group);
             $records->where('crm_leads.assign_user','=' ,$user->id);
             break;
     }
     $records->where('crm_leads.deleted','=' ,0);

        if($request->status != ''){
            switch ($request->status) {
                case '0':
                    $records->where('crm_leads.status','=' ,'0');
                    break;

                case 'NEW':
                    $records->where('crm_leads.status','=' ,'0');
                    break;

                case 'Priority':
                    $records->where('crm_leads.priority','=' ,'1');
                    break;
                
                default:
                    $records->where('crm_leads.status','=' ,$request->status);
                    $records->whereRaw('DATE(crm_leads.payment_date) <= "'.$request->date.'" ');
                    break;
            }

        }
        
        
     
     
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

        // switch ($status) {
        // 	case '1':
        // 		$status_label = '<span class="label label-xl label-warning label-inline mr-2">PTP</span>';
        // 		break;

        // 	case '2':
        // 		$status_label = '<span class="label label-xl label-danger label-inline mr-2">Below PTP</span>';
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
          "ptp_amount"      => $ptp_amount,
          "payment_date"    => $payment_date,
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

   public function getPTPHistories(Request $request){

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
     $totalRecordswithFilter = CrmPtpHistories::select('count(*) as allcount')
     ->where(function($query) use ($searchValue)  {
              $query->where('payment_date', 'like', '%' .$searchValue . '%')
              ->orWhere('payment_amount', 'like', '%' .$searchValue . '%');
        })
     ->where('leads_id','=' ,$request->leads_id)
     ->where('profile_id','=' ,$request->profile_id)
     ->count();

     // Fetch records
     $records = CrmPtpHistories::orderBy($columnName,$columnSortOrder)
        ->where(function($query) use ($searchValue)  {
              $query->where('payment_date', 'like', '%' .$searchValue . '%')
              ->orWhere('payment_amount', 'like', '%' .$searchValue . '%');
        })
       ->where('leads_id','=' ,$request->leads_id)
       ->where('profile_id','=' ,$request->profile_id)
       ->skip($start)
       ->take($rowperpage)
       ->get();

     $data_arr = array();

     foreach($records as $record){
        $id 			= $record->id;
        $profile_id 	= $record->profile_id;
        $created_by 	= User::getName($record->created_by);
        $assign_group 	= Groups::usersGroup($record->assign_group);
        $status 		= $record->status;
        $payment_date 		= $record->payment_date;
        $payment_amount 		= $record->payment_amount;
        $remarks 		= $record->remarks;
        $attempt 		= $record->attempt;
        $created_at 	= $record->created_at->diffForHumans();

        // switch ($status) {
        //   case '1':
        //     $status_label = '<span class="label label-xl label-warning label-inline mr-2">PTP</span>';
        //     break;

        //   case '2':
        //     $status_label = '<span class="label label-xl label-danger label-inline mr-2">Below PTP</span>';
        //     break;

        //   case '3':
        //     $status_label = '<span class="label label-xl label-secondary label-inline mr-2">BP</span>';
        //     break;

        //   case '4':
        //     $status_label = '<span class="label label-xl label-primary label-inline mr-2">Paid</span>';
        //     break;
          
        //   default:
        //     $status_label = '<span class="label label-xl label-success label-inline mr-2">New</span>';
        //     break;
        // }

        if($status == '0'){
            $status_label =  'New';
        }else{
            $status_label = $status;
        }


        $data_arr[] = array(
          "created_by" 		=> $created_by,
          "status" 			=> '<center>'.$status_label.'</center>',
          "payment_date" 	=> $payment_date,
          "payment_amount" 	=> $payment_amount,
          "remarks" 		=> $remarks,
          "created_at" 		=> $created_at,
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

   public function getCallLogs(Request $request){

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
     $totalRecordswithFilter = CrmCallLogs::select('count(*) as allcount')
     ->where(function($query) use ($searchValue)  {
              $query->where('contact_no', 'like', '%' .$searchValue . '%')
              ->orWhere('extension', 'like', '%' .$searchValue . '%');
        })
     // ->where('leads_id','=' ,$request->leads_id)
       ->where('profile_id','=' ,$request->profile_id)
     ->count();

     // Fetch records
     $records = CrmCallLogs::orderBy($columnName,$columnSortOrder)
        ->where(function($query) use ($searchValue)  {
              $query->where('contact_no', 'like', '%' .$searchValue . '%')
              ->orWhere('extension', 'like', '%' .$searchValue . '%');
        })
        // ->where('leads_id','=' ,$request->leads_id)
       ->where('profile_id','=' ,$request->profile_id)
       ->skip($start)
       ->take($rowperpage)
       ->get();

     $data_arr = array();

     foreach($records as $record){
        $id 			= $record->id;
        $profile_id 	= $record->profile_id;
        $call_by 		= User::getName($record->call_by);
        $contact_no 	= $record->contact_no;
        $extension 		= $record->extension;
        $call_id 		= $record->call_id;
        $created_at 	= $record->created_at->diffForHumans();




        $data_arr[] = array(
          "contact_no" 		=> $contact_no,
          "call_by" 		=> $call_by,
          "extension" 		=> $extension,
          "created_at" 		=> $created_at,
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


   public function getLoanHistory(Request $request){

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
     $totalRecordswithFilter = CrmLeads::select('count(*) as allcount')
     ->where(function($query) use ($searchValue)  {
              // $query->where('contact_no', 'like', '%' .$searchValue . '%')
              // ->orWhere('extension', 'like', '%' .$searchValue . '%');
        })
     ->where('id','!=' ,$request->leads_id)
     ->where('deleted','=' ,1)
     ->where('profile_id','=' ,$request->profile_id)
     ->where('account_number','=' ,$request->account_number)
     ->count();

     // Fetch records
     $records = CrmLeads::orderBy($columnName,$columnSortOrder)
        ->where(function($query) use ($searchValue)  {
              // $query->where('contact_no', 'like', '%' .$searchValue . '%')
              // ->orWhere('extension', 'like', '%' .$searchValue . '%');
        })
       ->where('id','!=' ,$request->leads_id)
       ->where('deleted','=' ,1)
       ->where('profile_id','=' ,$request->profile_id)
       ->where('account_number','=' ,$request->account_number)
       ->skip($start)
       ->take($rowperpage)
       ->get();

     $data_arr = array();

     foreach($records as $record){
        $id                     = $record->id;
        $profile_id             = $record->profile_id;
        $assign_user            = User::getName($record->assign_user);
        $loan_amount            = $record->loan_amount;
        $outstanding_balance    = $record->outstanding_balance;
        $due_date               = $record->due_date;
        $status                 = $record->status;
        $assign_group           = Groups::usersGroup($record->assign_group);
        $created_at             = $record->created_at->diffForHumans();
        $ptp_amount            = $record->ptp_amount;
        $payment_date            = $record->payment_date;

        // switch ($status) {
        //     case '1':
        //         $status_label = '<span class="label label-xl label-warning label-inline mr-2">PTP</span>';
        //         break;

        //     case '2':
        //         $status_label = '<span class="label label-xl label-danger label-inline mr-2">Below PTP</span>';
        //         break;

        //     case '3':
        //         $status_label = '<span class="label label-xl label-secondary label-inline mr-2">BP</span>';
        //         break;

        //   case '4':
        //     $status_label = '<span class="label label-xl label-primary label-inline mr-2">Paid</span>';
        //     break;
            
        //     default:
        //         $status_label = '<span class="label label-xl label-success label-inline mr-2">New</span>';
        //         break;
        // }

        if($status == '0'){
            $status_label =  'New';
        }else{
            $status_label = $status;
        }


        $data_arr[] = array(
          "loan_amount"             => $loan_amount,
          "status"                  => $status_label,
          "outstanding_balance"     => $outstanding_balance,
          "ptp_amount"              => $ptp_amount,
          "payment_date"            => $payment_date,
          "assign_user"             => $assign_user,
          "assign_group"            => $assign_group,
          "created_at"              => $created_at,
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
