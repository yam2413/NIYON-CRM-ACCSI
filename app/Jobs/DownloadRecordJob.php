<?php

namespace App\Jobs;


use Auth;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Groups;
use App\Models\SystemLogs;
use App\Models\CrmBorrowers;
use App\Models\CrmLeads;
use App\Models\CrmPtpHistories;
use App\Models\CrmCallLogs;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class DownloadRecordJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $id;
    public function __construct($id)
    {
        $this->id = $id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    private static function curl( $url=NULL, $options=NULL, $headers=false ){
        $cacert='c:/wwwroot/cacert.pem';    #EDIT THIS TO SUIT
        $vbh = fopen('php://temp', 'w+');

        session_write_close();

        $curl=curl_init();
        if( parse_url( $url,PHP_URL_SCHEME )=='https' ){
            curl_setopt( $curl, CURLOPT_SSL_VERIFYPEER, true );
            curl_setopt( $curl, CURLOPT_SSL_VERIFYHOST, 2 );
            // curl_setopt( $curl, CURLOPT_CAINFO, $cacert );
        }
        curl_setopt( $curl, CURLOPT_URL,trim( $url ) );
        curl_setopt( $curl, CURLOPT_AUTOREFERER, true );
        curl_setopt( $curl, CURLOPT_FOLLOWLOCATION, true );
        curl_setopt( $curl, CURLOPT_FAILONERROR, true );
        curl_setopt( $curl, CURLOPT_HEADER, false );
        curl_setopt( $curl, CURLINFO_HEADER_OUT, false );
        curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
        curl_setopt( $curl, CURLOPT_BINARYTRANSFER, true );
        curl_setopt( $curl, CURLOPT_CONNECTTIMEOUT, 20 );
        curl_setopt( $curl, CURLOPT_TIMEOUT, 60 );
        curl_setopt( $curl, CURLOPT_USERAGENT, 'Mozilla/5.0' );
        curl_setopt( $curl, CURLOPT_MAXREDIRS, 10 );
        curl_setopt( $curl, CURLOPT_ENCODING, '' );
        curl_setopt( $curl, CURLOPT_VERBOSE, true );
        curl_setopt( $curl, CURLOPT_NOPROGRESS, true );
        curl_setopt( $curl, CURLOPT_STDERR, $vbh );

        if( isset( $options ) && is_array( $options ) ){
            foreach( $options as $param => $value ) curl_setopt( $curl, $param, $value );
        }
        if( $headers && is_array( $headers ) ){
            curl_setopt( $curl, CURLOPT_HTTPHEADER, $headers );
        }
        $res=(object)array(
            'response'  =>  curl_exec( $curl ),
            'info'      =>  (object)curl_getinfo( $curl ),
            'errors'    =>  curl_error( $curl )
        );
        rewind( $vbh );
        $res->verbose=stream_get_contents( $vbh );
        fclose( $vbh );
        curl_close( $curl );

        return $res;
    }
    public function handle()
    {
        $crm_calls = CrmCallLogs::where('id', $this->id)->first();

        $call_id        = $crm_calls->call_id;
        $call_date = explode(' ', $crm_calls->created_at);

        $recordings_link = 'http://'.env('ASTERISK_HOST').'/api/api.php?type=get_recording&password='.env('ASTERISK_PASSWORD').'&dbname=asteriskcdrdb&uniqueid='.$call_id.'&date='.$call_date[0].'&version='.env('ASTERISK_VERSION');

        // set_time_limit(0);
        // //This is the file where we save the    information
        // $saveto = storage_path('app/public').'/test.wav';
        // $fp = fopen ($saveto, 'w');
        // //Here is the file we are downloading, replace spaces with %20
        // $ch = curl_init();
        // // make sure to set timeout to a high enough value
        // // if this is too low the download will be interrupted
        // curl_setopt($ch, CURLOPT_URL,$recordings_link);
        // curl_setopt($ch, CURLOPT_TIMEOUT, 600);
        // // write curl response to file
        // curl_setopt($ch, CURLOPT_FILE, $fp); 
        // curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        // // get curl response
        // curl_exec($ch); 
        // curl_close($ch);
        // fclose($fp);

        // $url = $recordings_link;
        // $ch = curl_init();
        // $headers = array();
        // $headers[] = "Content-Type: application/json";
        // curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        // curl_setopt($ch, CURLOPT_URL, $url);
        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        // curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
        // $result = curl_exec($ch);
        // $result = json_decode($result, TRUE);
        // curl_close($ch);

        $saveto = storage_path('app/public').'/test.wav';

        $fp=fopen( $saveto, 'w' );
        $options=array( CURLOPT_FILE => $fp );
        $res= $this->curl( $recordings_link, $options );
        SystemLogs::saveLogs(0, json_encode($res));
        fclose( $fp );
    }
}
