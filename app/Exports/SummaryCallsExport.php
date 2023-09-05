<?php

namespace App\Exports;

use Auth;
use DateTime;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Groups;
use App\Models\TempUploads;
use App\Models\FileUploadLogs;
use App\Models\CrmBorrowers;
use App\Models\CrmLeads;
use App\Models\SystemLogs;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\FromCollection;

class SummaryCallsExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public $date, $groups, $collector;
    public function __construct($date, $groups, $collector)
    {
        $this->date  		= $date;
        $this->groups    	= $groups;
        $this->collector    = $collector;
    }

    public function collection()
    {
    	$new_dates         = explode('|', str_replace(' ', '', $this->date)); 
     	$start_date        = (isset($new_dates[0])) ? $new_dates[0]:date('Y-m-d');
     	$end_date          = (isset($new_dates[1])) ? $new_dates[1]:date('Y-m-d');

        $records = User::selectRaw('users.*, (select count(*) from crm_call_logs t where t.call_by = users.id AND DATE(t.created_at) between "'.$start_date.'" AND "'.$end_date.'") as no_calls, (select count(*) from crm_ptp_histories x where x.created_by = users.id AND DATE(x.created_at) between "'.$start_date.'" AND "'.$end_date.'") as no_ptp, (select SUM(REPLACE(y.payment_amount, ",", "")) from crm_ptp_histories y where y.created_by = users.id AND DATE(y.created_at) between "'.$start_date.'" AND "'.$end_date.'") as total_ptp_amount');
	     $records->orderByRaw('no_calls DESC,no_ptp DESC,total_ptp_amount DESC');
	     if($this->groups != '0'){
	        $records->where('users.group','=' ,$this->groups);
	     }

	     if($this->collector != '0'){
	        $records->where('users.id','=' ,$this->collector);
	     }
	     $records = $records->get();

	     $data = [];
	     foreach ($records as $key => $record) {
	     	$id 			= $record->id;
	        $name 			= $record->name;
	        $group 			= Groups::usersGroup($record->group);

	     	$data[] = array(
	     		"no_calls" 			=> number_format($record->no_calls, 0),
          		"no_ptp" 			=> number_format($record->no_ptp, 0),
          		"total_ptp_amount" 	=> number_format($record->total_ptp_amount, 2),
          		"name" 				=> $name,
          		"assign_group" 		=> $group,
	     	);

	     }

	    return collect($data);
    }

    public function headings(): array
    {
        return ['No. of Calls', 'No. of PTP', 'Total PTP Amount', 'Collector', 'Group'];
        
    }

    public function map($data): array
    {
    	$no_calls 			= $data['no_calls']; 
        $no_ptp 			= $data['no_ptp']; 
        $total_ptp_amount 	= $data['total_ptp_amount'];
        $name 				= $data['name'];
        $assign_group 		= $data['assign_group'];

		return [$no_calls, $no_ptp, $total_ptp_amount, $name, $assign_group];
    	
    }
}
