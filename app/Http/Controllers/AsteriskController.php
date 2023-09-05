<?php

namespace App\Http\Controllers;

use DB;
use Auth;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Groups;
use App\Models\SystemLogs;
use Illuminate\Http\Request;

class AsteriskController extends Controller
{
    public function index(){
    ##To view the main page from the asterisk configuration
    	return view('pages.settings.asterisk.index');
    }

    public function update(Request $request){
    ##This post function is to update the asterisk configuration in the .env file
    	$user = Auth::user();

    	$arrEnv = [
            'ASTERISK_HOST' 	=> $request->host,
            'ASTERISK_PORT' 	=> $request->port,
            'ASTERISK_USERNAME' => $request->username,
            'ASTERISK_PASSWORD' => $request->password,
            'ASTERISK_VERSION' 	=> $request->version,
            'ASTERISK_PHONE' 	=> $request->phone,
            'ASTERISK_PREFIX' 	=> $request->prefix,
            'DB_USERNAME_SECOND'    => $request->db_username,
            'DB_PASSWORD_SECOND'    => $request->db_password,
        ];
        //To update the .env file from the asterisk configuration
        $this->setEnvironmentValue($arrEnv);

        //Insert a system logs
        SystemLogs::saveLogs($user->id, ' Updated asterisk configuration ');

        return response()->json([
            'error'  => 'false',
            'msg'    => 'Asterisk configuration successfully updated'
        ]);

    }

    public function test_pbx_connection(Request $request){

        //$cdr_exist = DB::connection('mysql2')->table('cdr')->select('count(*) as allcount')->count();

        try {
            DB::connection('mysql2')->getPdo();
        } catch (\Exception $e) {
            return response()->json([
                'error'  => 'true',
                'msg'    => "Could not connect to the database.  Please check your configuration. error:" . $e 
            ]);
        }

        return response()->json([
            'error'  => 'false',
            'msg'    => 'Database successfully connected'
        ]);
    }

    public function test_call(Request $request){
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

    public static function setEnvironmentValue(array $values){
    ##This statis function is to edit the .env file
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

    public function  get_asterisk_status_debug(Request $request){
            $extension  = $request->extension;
            $contact = $request->contact_no;

            $contact_no = env('ASTERISK_PREFIX').$contact;
            
            $socket = fsockopen(env('ASTERISK_HOST'),env('ASTERISK_PORT'), $errno, $errstr, 1000);
            fputs($socket, "Action: Login\r\n");
            fputs($socket, "UserName: ".env('ASTERISK_USERNAME')."\r\n");
            fputs($socket, "Secret: ".env('ASTERISK_PASSWORD')."\r\n\r\n");
            $channel='local/'.$extension.'@from-internal'; //pass channel through GET method
            //$ts = "<pre>";
            fwrite($socket, "Action: Status\r\n");
            fwrite($socket, "Command: Lists channel status ".$channel."\r\n\r\n");
            $wrets="";
            $raw="";
            $output = '';
            $call_status = '';
            $call_state = '';
            $mobs = '';
            fputs($socket, "Action: Logoff\r\n\r\n");

                                while (!feof($socket)) {
                                  //$wrets .= fread($socket, 8192).'</br>';
                                  $raw = fgets($socket, 8192);
                                  $output .= $raw;
                                  $uniq = explode(':', str_replace(' ', '', $raw));
                                  if(strtoupper($uniq[0]) == 'CALLERIDNUM'){

                                        if(trim($uniq[1]) == $extension){
                                            $mobs .= trim($uniq[1]);
                                        }else if(trim($uniq[1]) == $contact_no){
                                            $mobs .= trim($uniq[1]);
                                        }
                                    
                                        if($mobs == $contact_no){
                                            $call_status = (isset($uniq[1])) ? "Original Output: ".strlen(trim($uniq[1])).'|'.$contact_no.'|'.$extension:'';
                                            
                                        }
                                     
                                  }

                                  if(strtoupper($uniq[0]) == 'STATE'){
                                            //$call_state = (isset($uniq[1])) ? $uniq[1]:'';
                                        if($mobs == $contact_no || $mobs == $extension){
                                            $call_state = (isset($uniq[1])) ? $uniq[1]:'';
                                            
                                        }
                                     
                                  }
                                 
                                  
                                }

                                fclose($socket);


         return response()->json([
            'error'     => 'false',
            'msg'       => $output,
            'call_state'       => $call_state
        ]);

  
   }
}
