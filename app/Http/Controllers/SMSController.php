<?php

namespace App\Http\Controllers;

use Auth;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Groups;
use App\Models\SystemLogs;
use App\Models\TempSms;
use Illuminate\Http\Request;

class SMSController extends Controller
{
    public function index(){
    	return view('pages.settings.sms.index');
    }

    public function sms_template(){
    	$groups   = Groups::get();
    	return view('pages.sms.index', compact('groups'));
    }

    public function select_sms_templates(Request $request){
    	$user = \Auth::user();

    	$temp_sms = TempSms::where('group', $request['group'])->first();

    	 return response()->json([
                'status'  => ($temp_sms) ? 'Last update is '.$temp_sms['created_at'].' by '.User::getName($temp_sms['user']):'',
                'body'    => ($temp_sms) ? $temp_sms['body']:'',
         ]);
    }

    public function store(Request $request){
    	$user = \Auth::user();

    	$temp_sms = TempSms::select('count(*) as allcount')->where('group', $request['group'])->count();

    	if($temp_sms == 0){
    		$insert_data = array(
	            'user'     		=> $user->id,
	            'group'         => $request->group,
	            'body'      	=> $request->body,
	            'created_at'    => Carbon::now(),
	            'updated_at'    => Carbon::now(),
	        );
        	TempSms::insert($insert_data);
    	}else{
    		$post_sync = array(
	            'user'          => $user->id,
	            'group'   		=> $request->group,
	            'body'   		=> $request->body,
	            'updated_at'    => Carbon::now(),
	        );
        
        	TempSms::where('group', '=', $request->group)->update($post_sync);
    	}
    	
    	

    	 return response()->json([
                'error'  => 'false',
                'msg'    => 'SMS template successfully updated'
         ]);
    }

     public function update(Request $request){
    	$user = Auth::user();

    	$url = $request->host.'/views/api/?action=login&username='.$request->username.'&password='.$request->password;
        $ch = curl_init();
        $headers = array();
        $headers[] = "Content-Type: application/json";
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        $result = curl_exec($ch);
        $result = json_decode($result, TRUE);
        curl_close($ch);

    	if($result['response'] != '001'){
    		return response()->json([
	            'error'  => 'true',
	            'msg'    => 'Cannot Connect in the SMSMO, Please Check the authentication settings.'
	        ]);
    	}

    	$arrEnv = [
            'SMSMO_HOST' 	=> $request->host,
            'SMSMO_USERNAME' => $request->username,
            'SMSMO_PASSWORD' => $request->password,
        ];

        $this->setEnvironmentValue($arrEnv);

        SystemLogs::saveLogs($user->id, ' Updated sms configuration ');

        return response()->json([
            'error'  => 'false',
            'msg'    => 'SMS configuration successfully updated'
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
