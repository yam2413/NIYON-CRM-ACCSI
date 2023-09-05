<?php

namespace App\Exports;

use Auth;
use DateTime;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Groups;
use App\Models\TempUploads;
use App\Models\CronActivities;
use App\Models\CrmBorrowers;
use App\Models\CrmLeads;
use App\Models\SystemLogs;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\FromCollection;

class SMSLogsExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public $date;
    public function __construct($date)
    {
        $this->date  		= $date;
    }

    public function collection()
    {
    	$new_dates         = explode('|', str_replace(' ', '', $this->date)); 
     	$start_date        = (isset($new_dates[0])) ? $new_dates[0].' 00:00:00':date('Y-m-d').' 00:00:00';
     	$end_date          = (isset($new_dates[1])) ? $new_dates[1].' 23:00:00':date('Y-m-d').' 23:00:00';

      $records = CronActivities::select(
            [
                'cron_activities.*',
                'crm_borrowers.full_name',
            ]
     );
     $records->orderBy('cron_activities.created_at','DESC');
     $records->leftjoin('crm_borrowers', 'crm_borrowers.profile_id', '=', 'cron_activities.profile_id');
     $records->whereBetween('cron_activities.created_at', [$start_date, $end_date]);
     $records->where('cron_activities.type','=' ,'sms');
     $records = $records->get();

	    $data = [];
	    foreach ($records as $key => $record) {
	     	$id             = $record->id;
	        $full_name      = $record->full_name;
	        $to             = $record->to;
	        $body           = $record->body;
	        $status         = $record->status;
	        $error_msg      = $record->error_msg;
	        $created_at     = $record->created_at->diffForHumans();
	        $user           = User::getName($record->user);

	        switch ($status) {
	          case '1':
	            $status_label = "Added Queuing List";
	            break;
	          
	          case '2':
	            $status_label = "Failed to add in queue list";
	            break;
	          
	          default:
	            $status_label = "Processing";
	            break;
	        }

	     	$data[] = array(
	     	  "full_name"     => $full_name,
	          "to"            => $to,
	          "body"          => $body,
	          "status"        => $status_label,
	          "user"          => $user,
	          "created_at"    => $created_at,
	     	);

	    }

	    return collect($data);
    }

    public function headings(): array
    {
        return ['Full Name', 'To', 'Body', 'Status', 'Added By', 'Created At'];
        
    }

    public function map($data): array
    {
    	$full_name 			= $data['full_name']; 
        $to 				= $data['to']; 
        $body 				= $data['body']; 
        $status_label 		= $data['status'];
        $user 				= $data['user']; 
        $created_at 		= $data['created_at'];
		return [$full_name, $to, $body, $status_label, $user, $created_at];
    	
    }
}
