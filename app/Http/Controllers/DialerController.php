<?php

namespace App\Http\Controllers;

use Auth;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Groups;
use App\Models\SystemLogs;
use Illuminate\Http\Request;

class DialerController extends Controller
{
    public function index(){
    	return view('pages.settings.dialer.index');
    }

    public function update(Request $request){
    	$user = Auth::user();

    	$arrEnv = [
            'DIALER_CONFIRM_CALL' 		=> $request->dialer_confirm_call,
            'DIALER_COLL_PAUSE' 		=> $request->dialer_coll_pause,
            'DIALER_M_ALLOW_CAMPAIGN' 	=> $request->dialer_m_allow_campaign,
            'DIALER_M_ACCESS_VOICE_M' 	=> $request->dialer_m_access_voice_m,
            'DIALER_TIMECALL_ENGAGE' 	=> $request->dialer_timecall_engage,
        ];

        $this->setEnvironmentValue($arrEnv);

        SystemLogs::saveLogs($user->id, ' Updated auto dialer configuration from settings  ');

        return response()->json([
            'error'  => 'false',
            'msg'    => 'Successfully updated auto dialer settings'
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
