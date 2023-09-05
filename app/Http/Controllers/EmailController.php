<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Groups;
use App\Models\TempEmails;
use App\Mail\EmailTest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class EmailController extends Controller
{
    public function index(){
    	$groups   = Groups::get();
    	return view('pages.emails.index', compact('groups'));
    }

    public function emails_settings(){
        return view('pages.settings.email.index');
    }

    public function select_email_templates(Request $request){
    	$user = \Auth::user();

    	$temp_emails = TempEmails::where('group', $request['group'])->first();

    	 return response()->json([
                'status'  => ($temp_emails) ? 'Last update is '.$temp_emails['created_at'].' by '.User::getName($temp_emails['user']):'',
                'body'    => ($temp_emails) ? $temp_emails['body']:'',
                'subject' => ($temp_emails) ? $temp_emails['subject']:''
         ]);
    }

    public function send_email(Request $request){
        $user = \Auth::user();

        try
        {
            Mail::to($request->email)->send(new EmailTest());
        }
        catch(\Exception $e)
        {
            return response()->json([
                'error'  => 'true',
                'msg'    => $e->getMessage(),
            ]);
        }


         return response()->json([
            'error'  => 'false',
            'msg'    => 'Successfully send test email'
        ]);
    }

    public function update_smtp(Request $request){
        $user = Auth::user();

        $arrEnv = [
            'MAIL_MAILER'       => $request->mailer,
            'MAIL_HOST'         => $request->host,
            'MAIL_USERNAME'     => $request->username,
            'MAIL_PASSWORD'     => $request->password,
            'MAIL_ENCRYPTION'   => $request->encryption,
            'MAIL_FROM_ADDRESS' => $request->from_address,
            'MAIL_FROM_NAME'    => $request->mail_email,
            'MAIL_PORT'         => $request->port,
        ];

        $this->setEnvironmentValue($arrEnv);

        //SystemLogs::saveLogs($user->id, ' Updated root configuration for  ');

        return response()->json([
            'error'  => 'false',
            'msg'    => 'Successfully updated smtp settings'
        ]);

    }


    public function store(Request $request){
    	$user = \Auth::user();

    	$temp_emails = TempEmails::select('count(*) as allcount')->where('group', $request['group'])->count();

    	if($temp_emails == 0){
    		$insert_data = array(
	            'user'     		=> $user->id,
	            'group'         => $request->group,
                'subject'         => $request->subject,
	            'body'      	=> $request->body,
	            'created_at'    => Carbon::now(),
	            'updated_at'    => Carbon::now(),
	        );
        	TempEmails::insert($insert_data);
    	}else{
    		$post_sync = array(
	            'user'          => $user->id,
	            'group'   		=> $request->group,
                'subject'       => $request->subject,
	            'body'   		=> $request->body,
	            'updated_at'    => Carbon::now(),
	        );
        
        	TempEmails::where('group', '=', $request->group)->update($post_sync);
    	}
    	
    	

    	 return response()->json([
                'error'  => 'false',
                'msg'    => 'Email template successfully updated'
         ]);
    }

    public static function setEnvironmentValue(array $values)
    {
        $envFile = app()->environmentFilePath();
        $str     = file_get_contents($envFile);
        if(count($values) > 0)
        {
            foreach($values as $envKey => $envValue)
            {
                $keyPosition       = strpos($str, "{$envKey}=");
                $endOfLinePosition = strpos($str, "\n", $keyPosition);
                $oldLine           = substr($str, $keyPosition, $endOfLinePosition - $keyPosition);

                // If key does not exist, add it
                if(!$keyPosition || !$endOfLinePosition || !$oldLine)
                {
                    $str .= "{$envKey}='{$envValue}'\n";
                }
                else
                {
                    $str = str_replace($oldLine, "{$envKey}='{$envValue}'", $str);
                }
            }
        }
        $str = substr($str, 0, -1);
        $str .= "\n";

        return file_put_contents($envFile, $str) ? true : false;
    }
}
