<?php
namespace App\Helpers;

use App\Models\User;
use App\Models\Groups;

class AppHelper
{
      public function getColorPalettes($group_id)
      {
        if($group_id == '0'){
            return '';
        }
        $groups = Groups::find($group_id);
        
        if($groups->color_palette == 'null'){
            return '';
        }
        return 'background-color: '.$groups->color_palette.' !important ';
      }

      public static function instance()
     {
         return new AppHelper();
     }

     public static function  get_asterisk_status($extension, $contact_no){
            $contact_no = env('ASTERISK_PREFIX').$contact_no;
            
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
        return $call_state;
   }
     
}