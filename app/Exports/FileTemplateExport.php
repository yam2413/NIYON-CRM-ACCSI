<?php

namespace App\Exports;

use Auth;
use DateTime;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Groups;
use App\Models\SystemLogs;
use App\Models\FileHeaders;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\FromCollection;

class FileTemplateExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public $group_id;
    public function __construct($group_id)
    {
        $this->group_id  = $group_id;
    }

    public function collection()
    {

        $records = FileHeaders::orderBy('id','ASC');
	     $records->where('group','=' ,$this->group_id);
	     $records = $records->get();

	    $data_field = [];
	    foreach ($records as $key => $record) {
	     
            $data_field[] = "--";
	     	

	    }
        $append_header = implode(',', $data_field);
        $append_header = explode(',', $append_header);
        return collect($append_header);
    }

    public function headings(): array
    {
         $records = FileHeaders::orderBy('id','ASC');
	     $records->where('group','=' ,$this->group_id);
	     $records = $records->get();

	    $data_field = [];
	    foreach ($records as $key => $record) {
	     
            $data_field[] = $record->field_name;
	     	

	    }
        $append_header = 'Assign Group,Coll Code,'.implode(',', $data_field);
        $append_header = explode(',', $append_header);
        return [$append_header];
        
    }

    public function map($data): array
    {

		return [''];
    	
    }
}
