<?php

namespace App\Http\Controllers;

use Auth;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Groups;
use App\Models\SystemLogs;
use Illuminate\Http\Request;

class RootController extends Controller
{
    public function index(){
    	return view('pages.settings.root.index');
    }

    public function update(Request $request){
    	$user = Auth::user();

    	$arrEnv = [
            'DEMO_STATUS' 	=> $request->set_as_demo,
            'DEMO_DATE' 	=> $request->demo_date,
            'DEMO_MESSAGE' => $request->demo_msg,
        ];

        $this->setEnvironmentValue($arrEnv);

        //SystemLogs::saveLogs($user->id, ' Updated root configuration for  ');

        return response()->json([
            'error'  => 'false',
            'msg'    => 'Successfully updated demo settings'
        ]);

    }

    public function update_features(Request $request){
    	$user = Auth::user();

    	$arrEnv = [
            'FEATURE_AUTO_DIALER' 	=> $request->feature_auto_dialer,
            'FEATURE_EMAIL' 	=> $request->feature_email,
            'FEATURE_SMS' => $request->feature_sms,
        ];

        $this->setEnvironmentValue($arrEnv);

        //SystemLogs::saveLogs($user->id, ' Updated root configuration for  ');

        return response()->json([
            'error'  => 'false',
            'msg'    => 'Successfully updated feature settings'
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
