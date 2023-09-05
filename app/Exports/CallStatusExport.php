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
use App\Models\FileHeaders;
use App\Models\ManualNumbers;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\FromCollection;

class CallStatusExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public $date, $groups, $collector, $status;
    public function __construct($date, $groups, $collector, $status)
    {
        $this->date  		= $date;
        $this->groups    	= $groups;
        $this->collector    = $collector;
        $this->status       = $status;
    }

    public function collection()
    {
    	$new_dates         = explode('|', str_replace(' ', '', $this->date)); 
     	$start_date        = (isset($new_dates[0])) ? $new_dates[0]:date('Y-m-d');
     	$end_date          = (isset($new_dates[1])) ? $new_dates[1]:date('Y-m-d');

        $records = CrmLeads::select(
            [
                'crm_borrowers.full_name',
                'crm_leads.*',
                //'crm_ptp_histories.call_status',
                'temp_uploads.data2',
                'temp_uploads.data3',
                'temp_uploads.data4',
                'temp_uploads.data5',
                'temp_uploads.data6',
                'temp_uploads.data7',
                'temp_uploads.data8',
                'temp_uploads.data9',
                'temp_uploads.data10',
                'temp_uploads.data11',
                'temp_uploads.data12',
                'temp_uploads.data13',
                'temp_uploads.data14',
                'temp_uploads.data15',
                'temp_uploads.data16',
                'temp_uploads.data17',
                'temp_uploads.data18',
                'temp_uploads.data19',
                'temp_uploads.data20',
                'temp_uploads.data21',
                'temp_uploads.data22',
                'temp_uploads.data23',
                'temp_uploads.data24',
                'temp_uploads.data25',
                'temp_uploads.data26',
                'temp_uploads.data27',
                'temp_uploads.data28',
                'temp_uploads.data29',
                'temp_uploads.data30',
                'temp_uploads.data31',
                'temp_uploads.data32',
                'temp_uploads.data33',
                'temp_uploads.data34',
                'temp_uploads.data35',
                'temp_uploads.data36',
                'temp_uploads.data37',
                'temp_uploads.data38',
                'temp_uploads.data39',
                'temp_uploads.data40',
                'temp_uploads.data41',
                'temp_uploads.data42',
                'temp_uploads.data43',
                'temp_uploads.data44',
                'temp_uploads.data45',
                'temp_uploads.data46',
                'temp_uploads.data47',
                'temp_uploads.data48',
                'temp_uploads.data49',
                'temp_uploads.data50',
                'temp_uploads.data51',
                'temp_uploads.data52',
                'temp_uploads.data53',
                'temp_uploads.data54',
                'temp_uploads.data55',
                'temp_uploads.data56',
                'temp_uploads.data57',
                'temp_uploads.data58',
                'temp_uploads.data59',
                'temp_uploads.data60',
                'temp_uploads.data61',
                'temp_uploads.data62',
                'temp_uploads.data63',
                'temp_uploads.data64',
                'temp_uploads.data65',
                'temp_uploads.data66',
                'temp_uploads.data67',
                'temp_uploads.data68',
                'temp_uploads.data69',
                'temp_uploads.data70',
                'temp_uploads.data71',
                'temp_uploads.data72',
                'temp_uploads.data73',
                'temp_uploads.data74',
                'temp_uploads.data75',
                'temp_uploads.data76',
                'temp_uploads.data77',
                'temp_uploads.data78',
                'temp_uploads.data79',
                'temp_uploads.data80',
                'temp_uploads.data81',
                'temp_uploads.data82',
                'temp_uploads.data83',
                'temp_uploads.data84',
                'temp_uploads.data85',
                'temp_uploads.data86',
                'temp_uploads.data87',
                'temp_uploads.data88',
                'temp_uploads.data89',
                'temp_uploads.data90',

            ]
        );
        $records->leftjoin('crm_borrowers', 'crm_borrowers.profile_id', '=', 'crm_leads.profile_id');
        $records->leftjoin('temp_uploads', function($join){
            $join->on('temp_uploads.profile_id', '=', 'crm_leads.profile_id');
            $join->on('temp_uploads.file_id', '=', 'crm_leads.file_id');
        });
        $records->whereRaw('DATE(crm_leads.status_updated) BETWEEN "'.$start_date.'" AND "'.$end_date.'" ');
        if($this->status != '0'){
            $records->where('crm_leads.status','=' , $this->status);
        }
        if($this->groups != '0'){
            $records->where('crm_leads.assign_group','=' , $this->groups);
        }
        if($this->collector != '0'){
            $records->where('crm_leads.assign_user','=' , $this->collector);
        }
        
        $records = $records->get();

        $data = [];
        foreach ($records as $key => $record) {
            $id                         = $record->id;
            $profile_id                 = $record->profile_id;
            $full_name                  = $record->full_name;
            $status                     = $record->status;
            $ptp_amount                 = $record->ptp_amount;
            $payment_date               = $record->payment_date;
            $assign_user                = User::getName($record->assign_user);
            $account_number             = $record->account_number;
            $assign_group               = Groups::usersGroup($record->assign_group);
            $created_at                 = $record->created_at->diffForHumans();
            $priority                   = $record->priority;
            $remarks                    = $record->remarks;
            $outstanding_balance        = $record->outstanding_balance;
            $loan_amount                = $record->loan_amount;
            //$call_status              = $record->call_status;

            switch ($status) {
                case '0':
                    $status_label = 'New';
                    break;
                
                default:
                    $status_label = $status;
                    break;
            }

            $data[] = array(
              "full_name"                   => $full_name,
              "status"                      => $status_label,
              "assign_user"                 => $assign_user,
              "account_number"              => $account_number,
              "assign_group"                => $assign_group,
              "created_at"                  => $created_at,
              "ptp_amount"                  => $ptp_amount,
              "payment_date"                => $payment_date,
              "remarks"                     => $remarks,
              "outstanding_balance"         => $outstanding_balance,
              "loan_amount"                 => $loan_amount,
              //"call_status"                   => $call_status,
              "id"                          => $id,
              "data2"                       => $record->data2,
              "data3"                       => $record->data3,
              "data4"                       => $record->data4,
              "data5"                       => $record->data5,
              "data6"                       => $record->data6,
              "data7"                       => $record->data7,
              "data8"                       => $record->data8,
              "data9"                       => $record->data9,
              "data10"                      => $record->data10,
              "data11"                      => $record->data11,
              "data12"                      => $record->data12,
              "data13"                      => $record->data13,
              "data14"                      => $record->data14,
              "data15"                      => $record->data15,
              "data16"                      => $record->data16,
              "data17"                      => $record->data17,
              "data18"                      => $record->data18,
              "data19"                      => $record->data19,
              "data20"                      => $record->data20,
              "data21"                      => $record->data21,
              "data22"                      => $record->data22,
              "data23"                      => $record->data23,
              "data24"                      => $record->data24,
              "data25"                      => $record->data25,
              "data26"                      => $record->data26,
              "data27"                      => $record->data27,
              "data28"                      => $record->data28,
              "data29"                      => $record->data29,
              "data30"                      => $record->data30,
              "data31"                      => $record->data31,
              "data32"                      => $record->data32,
              "data33"                      => $record->data33,
              "data34"                      => $record->data34,
              "data35"                      => $record->data35,
              "data36"                      => $record->data36,
              "data37"                      => $record->data37,
              "data38"                      => $record->data38,
              "data39"                      => $record->data39,
              "data40"                      => $record->data40,
              "data41"                      => $record->data41,
              "data42"                      => $record->data42,
              "data43"                      => $record->data43,
              "data44"                      => $record->data44,
              "data45"                      => $record->data45,
              "data46"                      => $record->data46,
              "data47"                      => $record->data47,
              "data48"                      => $record->data48,
              "data49"                      => $record->data49,
              "data50"                      => $record->data50,
              "data51"                      => $record->data51,
              "data52"                      => $record->data52,
              "data53"                      => $record->data53,
              "data54"                      => $record->data54,
              "data55"                      => $record->data55,
              "data56"                      => $record->data56,
              "data57"                      => $record->data57,
              "data58"                      => $record->data58,
              "data59"                      => $record->data59,
              "data60"                      => $record->data60,
              "data61"                      => $record->data61,
              "data62"                      => $record->data62,
              "data63"                      => $record->data63,
              "data64"                      => $record->data64,
              "data65"                      => $record->data65,
              "data66"                      => $record->data66,
              "data67"                      => $record->data67,
              "data68"                      => $record->data68,
              "data69"                      => $record->data69,
              "data70"                      => $record->data70,
              "data71"                      => $record->data71,
              "data72"                      => $record->data72,
              "data73"                      => $record->data73,
              "data74"                      => $record->data74,
              "data75"                      => $record->data75,
              "data76"                      => $record->data76,
              "data77"                      => $record->data77,
              "data78"                      => $record->data78,
              "data79"                      => $record->data79,
              "data80"                      => $record->data80,
              "data81"                      => $record->data81,
              "data82"                      => $record->data82,
              "data83"                      => $record->data83,
              "data84"                      => $record->data84,
              "data85"                      => $record->data85,
              "data86"                      => $record->data86,
              "data87"                      => $record->data87,
              "data88"                      => $record->data88,
              "data89"                      => $record->data89,
              "data90"                      => $record->data90,
            );

        }

        return collect($data);
    }

    public function headings(): array
    {
        $records = FileHeaders::orderBy('id','ASC');
        $records->where('group','=' ,$this->groups);
        $records = $records->get();

        $data_field = [];

        $data_field[] = 'Account Status';
        $data_field[] = 'Collector';
        $data_field[] = 'Coll Code';
        $data_field[] = 'PTP Amount';
        $data_field[] = 'PTP Date';
        $data_field[] = 'Remarks';

        foreach ($records as $key => $record) {
         
            $data_field[] = $record->field_name;
            

        }
        return [$data_field];
        
    }

    public function map($data): array
    {
    	$status                    = $data['status'];
        //$call_status              = $data['call_status']; 
        $assign_user                = $data['assign_user']; 
        $account_number             = $data['account_number']; 
        $full_name                  = $data['full_name'];
        $assign_group               = $data['assign_group'];
        $ptp_amount                 = $data['ptp_amount'];
        $payment_date               = $data['payment_date'];
        $outstanding_balance        = $data['outstanding_balance'];
        $loan_amount                = $data['loan_amount']; 
        $remarks                    = $data['remarks']; 
        $created_at                 = $data['created_at'];
        $coll_code                  = $data['data2'];

        $data_field = [];

        $data_field[] = $status;
        $data_field[] = $assign_user;
        $data_field[] = $coll_code;
        $data_field[] = $ptp_amount;
        $data_field[] = $payment_date;
        $data_field[] = $remarks;

        for ($i=3; $i < 87; $i++) { 
            $data_field[] = $data['data'.$i];
        }

        return [$data_field];
    	
    }
}
