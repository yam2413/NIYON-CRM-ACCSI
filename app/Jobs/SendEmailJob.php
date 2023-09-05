<?php

namespace App\Jobs;

use DB;
use Auth;
use Carbon\Carbon;
use App\Models\User;
use App\Models\CronActivities;
use App\Mail\LeadsEmail;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $cron_id, $email, $body, $subject;
    public function __construct($cron_id, $email, $body, $subject)
    {
        $this->email = $email;
        $this->cron_id = $cron_id;
        $this->body = $body;
        $this->subject = $subject;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try
        {
            Mail::to($this->email)->send(new LeadsEmail($this->body, $this->subject));
            $post_sync = array(
                'status'        => '1',
                'updated_at'    => Carbon::now(),
            );
            
            CronActivities::where('cron_id', '=', $this->cron_id)->update($post_sync);
        }
        catch(\Exception $e)
        {

            $post_sync = array(
                'status'        => '2',
                'error_msg'     => $e->getMessage(),
                'updated_at'    => Carbon::now(),
            );
            
            CronActivities::where('cron_id', '=', $this->cron_id)->update($post_sync);
        }
    }
}
