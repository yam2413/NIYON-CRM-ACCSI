<?php

namespace App\Jobs;

use DB;
use Auth;
use Carbon\Carbon;
use App\Models\User;
use App\Models\CronActivities;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendSMSJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $cron_id, $mobile, $message;
    public function __construct($cron_id, $mobile, $message)
    {
        $this->cron_id = $cron_id;
        $this->mobile = $mobile;
        $this->message = $message;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $mobile = $this->mobile;
        $message = urlencode($this->message);

        $url = env('SMSMO_HOST').'/views/api/?action=bulk-sms&username='.env('SMSMO_USERNAME').'&password='.env('SMSMO_PASSWORD').'&mobile='.$mobile.'&port=0&message='.$message;
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

        if(isset($result['response'])){

            if($result['response'] != '100'){
                $post_sync = array(
                    'status'        => '2',
                    'error_msg'     => $result['response'].' | SMS Failed to send',
                    'updated_at'    => Carbon::now(),
                );
                
                CronActivities::where('cron_id', '=', $this->cron_id)->update($post_sync);
            }else{
                $post_sync = array(
                    'status'        => '1',
                    'updated_at'    => Carbon::now(),
                );
                
                CronActivities::where('cron_id', '=', $this->cron_id)->update($post_sync);
            }

        }else{
            $post_sync = array(
                'status'        => '2',
                'error_msg'     => json_encode($result).' | SMS Failed to send',
                'updated_at'    => Carbon::now(),
            );
                
            CronActivities::where('cron_id', '=', $this->cron_id)->update($post_sync);

        }

        
    }
}
