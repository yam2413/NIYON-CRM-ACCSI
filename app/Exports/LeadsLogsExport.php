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
use App\Models\CrmLogs;
use App\Models\SystemLogs;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\FromCollection;

class LeadsLogsExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public $date, $collector;
    public function __construct($date, $collector)
    {
        $this->date  		= $date;
        $this->collector    = $collector;
    }

    public function collection()
    {
    	$new_dates         = explode('|', str_replace(' ', '', $this->date)); 
     	$start_date        = (isset($new_dates[0])) ? $new_dates[0]:date('Y-m-d');
     	$end_date          = (isset($new_dates[1])) ? $new_dates[1]:date('Y-m-d');

        $records = CrmLogs::select(
            [
                'crm_logs.*',
                'users.name',
                'crm_borrowers.full_name',
            ]
        );
	     $records->orderBy('crm_borrowers.full_name','DESC');
	     $records->leftjoin('crm_borrowers', 'crm_borrowers.profile_id', '=', 'crm_logs.profile_id');
	     $records->join('users', 'users.id', '=', 'crm_logs.user');
	     if($this->collector != '0' && $this->collector != ''){
	        $records->where('crm_logs.user','=' ,$this->collector);
	     }
	     $records->whereRaw('DATE(crm_logs.created_at) BETWEEN "'.$start_date.'" AND "'.$end_date.'" ');
	     $records = $records->get();

	    $data = [];
	    foreach ($records as $key => $record) {
	     	$id             = $record->id;
	        $name           = $record->name;
	        $actions        = $record->actions;
	        $created_at     = $record->created_at->diffForHumans();

	     	$data[] = array(
	     	  "name"          => $name,
	          "actions"       => $actions,
	          "created_at"    => $created_at,
	     	);

	    }

	    return collect($data);
    }

    public function headings(): array
    {
        return ['Name', 'Actions', 'Created At'];
        
    }

    public function map($data): array
    {
    	$name 			= $data['name']; 
        $actions 		= $data['actions']; 
        $created_at 	= $data['created_at'];
		return [$name, $actions, $created_at];
    	
    }
}
