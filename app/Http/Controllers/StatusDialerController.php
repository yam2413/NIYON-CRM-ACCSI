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

class StatusDialerController extends Controller
{
    public function status_dialer($group_id, $statuses, $date){
        $user           = Auth::user();
        $group_name     = Groups::usersGroup($group_id);

        $new_dates         = explode('|', str_replace(' ', '', $date)); 
        $start_date        = (isset($new_dates[0])) ? $new_dates[0]:date('Y-m-d');
        $end_date          = (isset($new_dates[1])) ? $new_dates[1]:date('Y-m-d');

        $date_human     = date("F d, Y", strtotime($start_date)).' to '.date("F d, Y", strtotime($end_date));
        return view('pages.agent.status_dialer', compact('group_name', 'group_id', 'statuses', 'date', 'date_human'));
        
    }

    public function view_leads_data($collector_id, $date, $statuses, $group_id, $leads_id){

        $new_dates         = explode('|', str_replace(' ', '', $date)); 
        $start_date        = (isset($new_dates[0])) ? $new_dates[0]:date('Y-m-d');
        $end_date          = (isset($new_dates[1])) ? $new_dates[1]:date('Y-m-d');

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
                'crm_leads.*',
            ]
        );
        $crm_leads->leftjoin('crm_borrowers', 'crm_borrowers.profile_id', '=', 'crm_leads.profile_id');
        $crm_leads->whereRaw('DATE(crm_leads.status_updated) BETWEEN "'.$start_date.'" AND "'.$end_date.'" ');
        $crm_leads->where('crm_leads.deleted', '=', 0);
        $crm_leads->where('crm_leads.assign_user', '=', $collector_id);
        $crm_leads->where('crm_leads.status', '=', $statuses);
        $crm_leads->where('crm_leads.assign_group', '=', $group_id);
        $crm_leads->where('crm_leads.id', '=', $leads_id);
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

            $file_id = $crm_leads->file_id;
            $dialer_view_type = 'status_dialer';

        return view('pages.agent.leads_data', compact('crm_leads', 'temp_uploads', 'temp_datas', 'sms_no_lists', 'contact_no_lists','temp_emails','email_msg', 'email_subject','sms_msg', 'file_id','file_headers','manual_numbers','dialer_view_type'));
    }

    private static function get_leads_one_with_filter($array_filters){
        $decode                     = json_decode($array_filters, true);
        $group_id                   = $decode['group_id'];
        $statuses                   = $decode['statuses'];
        $start_date                 = $decode['start_date'];
        $end_date                   = $decode['end_date'];
        $collector_id               = $decode['collector_id'];
        $today_date                 = date('Y-m-d');
        $records = CrmLeads::selectRaw(
                       'crm_borrowers.full_name,
                        crm_borrowers.home_no,
                        crm_borrowers.business_no,
                        crm_borrowers.cellphone_no,
                        crm_borrowers.other_phone_1,
                        crm_borrowers.other_phone_2,
                        crm_borrowers.other_phone_3,
                        crm_borrowers.other_phone_4,
                        crm_borrowers.other_phone_5,
                        crm_leads.*'
                    
                );
        $records->leftjoin('crm_borrowers', 'crm_borrowers.profile_id', '=', 'crm_leads.profile_id');
        
        $records->whereRaw('DATE(crm_leads.status_updated) BETWEEN "'.$start_date.'" AND "'.$end_date.'" ');
        if($statuses != 'NEW'){
            $records->whereRaw('DATE(crm_leads.updated_at) != "'.$today_date.'"');
        }
        
        $records->where('crm_leads.deleted', '=', 0);
        $records->where('crm_leads.assign_user', '=', $collector_id);
        $records->where('crm_leads.status', '=', $statuses);
        $records->where('crm_leads.assign_group', '=', $group_id);
        $records->where('crm_borrowers.full_name','!=' ,'');
        $records = $records->first();


        return $records;
    }

    public function get_leads_data_with_filter(Request $request){
        $user       = Auth::user();

        $new_dates         = explode('|', str_replace(' ', '', $request->date)); 
        $start_date        = (isset($new_dates[0])) ? $new_dates[0]:date('Y-m-d');
        $end_date          = (isset($new_dates[1])) ? $new_dates[1]:date('Y-m-d');

        $array_filters = array(
            'group_id'              => $request->group_id,
            'statuses'              => $request->statuses,
            'start_date'            => $start_date,
            'end_date'              => $end_date,
            'collector_id'          => $request->user_id,
        );
            
        $crm_leads = $this->get_leads_one_with_filter(json_encode($array_filters));
        if(!isset($crm_leads->id)){
            return response()->json([
                'dial'                   => 0,
                'leads_id'               => 0,
                'contact_no'             => 0,
                'profile_id'             => 0,
                'name'                   => 0,
                'last_call_contact_no'   => 0,
            ]);
        }
        $leads_id                 = $crm_leads->id;
        $dial                     = $crm_leads->dial;
        $last_call_contact_no     = $crm_leads->last_call_contact_no;
        $payment_date             = $crm_leads->payment_date;
        $ptp_amount               = $crm_leads->ptp_amount;

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

        $check_dial    = CrmCallLogs::select('count(*) as allcount')
                                    ->where('profile_id', '=', $crm_leads->profile_id)
                                    ->where('contact_no','=' ,$call_contact_no)
                                    ->where('leads_id','=' ,$leads_id)
                                    ->whereRaw('DATE(created_at) = "'.date('Y-m-d').'" ')
                                    ->count();
            
        
        //Check if the Promise Date is already Past
        if($payment_date != ''){
            $past_date = new DateTime($payment_date);
            $now_date  = new DateTime();
            //$day_before = date( 'Y-m-d', strtotime( $date . ' -1 day' ) );

            if($past_date == $now_date) {
                //is equal to date now
                $ptp_status = '2';
            }else if($past_date < $now_date){
                $ptp_status = '3';
                //is late date
            }else{
                $ptp_status = '1';
            }

        }else{
            $ptp_status = '0';
        }
        

        return response()->json([
            'dial'                   => ($check_dial > 0) ? 1:0,
            'leads_id'               => $leads_id,
            'contact_no'             => $call_contact_no,
            'profile_id'             => $crm_leads->profile_id,
            'name'                   => $crm_leads->full_name,
            'last_call_contact_no'   => $last_call_contact_no,
            'payment_date'           => date("F d, Y", strtotime($payment_date)),
            'ptp_amount'             => $ptp_amount,
            'ptp_status'             => $ptp_status,
        ]);
        
    }

    public function access_campaign_status(Request $request){
        $user = Auth::user();

        $status = $request->status;
        $new_dates         = explode('|', str_replace(' ', '', $request->date)); 
        $start_date        = (isset($new_dates[0])) ? $new_dates[0]:date('Y-m-d');
        $end_date          = (isset($new_dates[1])) ? $new_dates[1]:date('Y-m-d');

        $date_human     = date("F d, Y", strtotime($start_date)).' to '.date("F d, Y", strtotime($end_date));

        switch ($request->log_type) {
            case 'login':
                SystemLogs::saveLogs($user->id, 'Log-in Status Auto Dialer with '.$status.' leads between '.$date_human.' ');
                break;

            case 'logout':
                SystemLogs::saveLogs($user->id, 'Exit in Status Auto Dialer with '.$status.' leads between '.$date_human.' ');
                break;
            case 'breaktime':
                SystemLogs::saveLogs($user->id, 'Break time in Status Auto Dialer with '.$status.' leads between '.$date_human.' ');
                break;
        }


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
            'call_id'         => $call_id,
            'profile_id'      => $request->profile_id,
            'contact_no'      => $request->contact_no,
            'extension'       => $user->extension,
            'call_by'         => $user->id,
            'leads_id'        => $request->leads_id,
            'created_at'      => Carbon::now(),
            'updated_at'      => Carbon::now(),
        );
        CrmCallLogs::insert($insert_data);

        CrmLogs::saveLogs($user->id, $request->profile_id, $request->name, 'Call has been triggered');

        
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
