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

class NewLeadsLogsExport implements FromCollection, WithHeadings, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public $type, $file_id, $upload_type;
    public function __construct($type, $file_id, $upload_type)
    {
        $this->type  	  = $type;
        $this->file_id    = $file_id;
        $this->upload_type    = $upload_type;
    }

    public function collection()
    {
         $records = TempUploads::where('file_id','=' ,$this->file_id);
         if($this->type == 'error'){
         	$records->where('error', '=', 1);
            $records->where('header', '=', 0);
         }else{
         	$records->where('error', '=', 0);
            $records->where('success', '=', 1);
            $records->where('header', '=', 0);
         }
         $records->orderBy('created_at','DESC');
         $records = $records->get();

	     $data = [];
	     foreach ($records as $key => $record) {

	       		 $data[] = array(
	              	"error_msg"  => $record->error_msg,
	              	'data1'      => $record->data1,
	                'data2'      => $record->data2,
	                'data3'      => $record->data3,
	                'data4'      => $record->data4,
	                'data5'      => $record->data5,
	                'data6'      => $record->data6,
	                'data7'      => $record->data7,
	                'data8'      => $record->data8,
	                'data9'      => $record->data9,
	                'data10'     => $record->data10,
	                'data11'     => $record->data11,
	                'data12'     => $record->data12,
	                'data13'     => $record->data13,
	                'data14'     => $record->data14,
	                'data15'     => $record->data15,
	                'data16'     => $record->data16,
	                'data17'     => $record->data17,
	                'data18'     => $record->data18,
	                'data19'     => $record->data19,
	                'data20'     => $record->data20,
	                'data21'     => $record->data21,
	                'data22'     => $record->data22,
	                'data23'     => $record->data23,
	                'data24'     => $record->data24,
	                'data25'     => $record->data25,
	                'data26'     => $record->data26,
	                'data27'     => $record->data27,
	                'data28'     => $record->data28,
	                'data29'     => $record->data29,
	                'data30'     => $record->data30,
	                'data31'     => $record->data31,
	                'data32'     => $record->data32,
	                'data33'     => $record->data33,
	                'data34'     => $record->data34,
	                'data35'     => $record->data35,
	                'data36'     => $record->data36,
	                'data37'     => $record->data37,
	                'data38'     => $record->data38,
	                'data39'     => $record->data39,
	                'data40'     => $record->data40,
	                'data41'     => $record->data41,
	                'data42'     => $record->data42,
	                'data43'     => $record->data43,
	                'data44'     => $record->data44,
	                'data45'     => $record->data45,
	                'data46'     => $record->data46,
	                'data47'     => $record->data47,
	                'data48'     => $record->data48,
	                'data49'     => $record->data49,
	                'data50'     => $record->data50,
	                'data51'     => $record->data51,
	                'data52'     => $record->data52,
	                'data53'     => $record->data53,
	                'data54'     => $record->data54,
	                'data55'     => $record->data55,
	                'data56'     => $record->data56,
	                'data57'     => $record->data57,
	                'data58'     => $record->data58,
	                'data59'     => $record->data59,
	                'data60'     => $record->data60,
	                'data61'     => $record->data61,
	                'data62'     => $record->data62,
	                'data63'     => $record->data63,
	                'data64'     => $record->data64,
	                'data65'     => $record->data65,
	                'data66'     => $record->data66,
	                'data67'     => $record->data67,
	                'data68'     => $record->data68,
	                'data69'     => $record->data69,
	                'data70'     => $record->data70,
	                'data71'     => $record->data71,
	                'data72'     => $record->data72,
	                'data73'     => $record->data73,
	                'data74'     => $record->data74,
	                'data75'     => $record->data75,
	                'data76'     => $record->data76,
	                'data77'     => $record->data77,
	                'data78'     => $record->data78,
	                'data79'     => $record->data79,
	                'data80'     => $record->data80,
	                'data81'     => $record->data81,
	                'data82'     => $record->data82,
	                'data83'     => $record->data83,
	                'data84'     => $record->data84,
	                'data85'     => $record->data85,
	                'data86'     => $record->data86,
	                'data87'     => $record->data87,
	                'data88'     => $record->data88,
	                'data89'     => $record->data89,
	                'data90'     => $record->data90,
	            );

	     }



          return collect($data);
    }

    public function headings(): array
    {
    	$temp_uploads = TempUploads::where('upload_type','=' ,$this->upload_type)
    						->where('header','=' ,'1')
    						->where('file_id','=' ,$this->file_id)
    						->first();
    	$append = [];
    	for ($i=1; $i < 80; $i++) { 
    		$append[] = $temp_uploads['data'.$i];
    	}
    	$append_header = 'Error Message,'.implode(',', $append).'';
    	$append_header = explode(',', $append_header);

        return [$append_header];
        
    }

    public function map($data): array
    {
    	$error_msg = $data['error_msg']; 
    	$data1 = $data['data1']; 
        $data2 = $data['data2']; 
        $data3 = $data['data3'];
        $data4 = $data['data4'];
        $data5 = $data['data5'];
        $data6 = $data['data6'];
        $data7 = $data['data7'];
        $data8 = $data['data8'];
        $data9 = $data['data9'];
        $data10 = $data['data10'];
        $data11 = $data['data11'];
        $data12 = $data['data12'];
        $data13 = $data['data13'];
        $data14 = $data['data14'];
        $data15 = $data['data15'];
        $data16 = $data['data16'];
        $data17 = $data['data17'];
        $data18 = $data['data18'];
        $data19 = $data['data19'];
        $data20 = $data['data20'];
        $data21 = $data['data21'];
        $data22 = $data['data22'];
        $data23 = $data['data23'];
        $data24 = $data['data24'];
        $data25 = $data['data25'];
        $data26 = $data['data26'];
        $data27 = $data['data27'];
        $data28 = $data['data28'];
        $data29 = $data['data29'];
        $data30 = $data['data30'];
        $data31 = $data['data31'];
        $data32 = $data['data32'];
        $data33 = $data['data33'];
        $data34 = $data['data34'];
        $data35 = $data['data35'];
        $data36 = $data['data36'];
        $data37 = $data['data37'];
        $data38 = $data['data38'];
        $data39 = $data['data39'];
        $data40 = $data['data40'];
        $data41 = $data['data41'];
        $data42 = $data['data42'];
        $data43 = $data['data43'];
        $data44 = $data['data44'];
        $data45 = $data['data45'];
        $data46 = $data['data46'];
        $data47 = $data['data47'];
        $data48 = $data['data48'];
        $data49 = $data['data49'];
        $data50 = $data['data50'];
        $data51 = $data['data51'];
        $data52 = $data['data52'];
        $data53 = $data['data53'];
        $data54 = $data['data54'];
        $data55 = $data['data55'];
        $data56 = $data['data56'];
        $data57 = $data['data57'];
        $data58 = $data['data58'];
        $data59 = $data['data59'];
        $data60 = $data['data60'];
        $data61 = $data['data61'];
        $data62 = $data['data62'];
        $data63 = $data['data63'];
        $data64 = $data['data64'];
        $data65 = $data['data65'];
        $data66 = $data['data66'];
        $data67 = $data['data67'];
        $data68 = $data['data68'];
        $data69 = $data['data69'];
        $data70 = $data['data70'];
        $data71 = $data['data71'];
        $data72 = $data['data72'];
        $data73 = $data['data73'];
        $data74 = $data['data74'];
        $data75 = $data['data75'];
        $data76 = $data['data76'];
        $data77 = $data['data77'];
        $data78 = $data['data78'];
        $data79 = $data['data79'];
        $data80 = $data['data80'];

		return [$error_msg,$data1, $data2, $data3,$data4,$data5,$data6,$data7,$data8,$data9,$data10,$data11,$data12,$data13,$data14,$data15,$data16,$data17,$data18,$data19,$data20,$data21,$data22,$data23,$data24,$data25,$data26,$data27,$data28,$data29,$data30,$data31,$data32,$data33,$data34,$data35,$data36,$data37,$data38,$data39,$data40,$data41,$data42,$data43,$data44,$data45,$data46,$data47,$data48,$data49,$data50,$data51,$data52,$data53,$data54,$data55,$data56,$data57,$data58,$data59,$data60,$data61,$data62,$data63,$data64,$data65,$data66,$data67,$data68,$data69,$data70,$data71,$data72,$data73,$data74,$data75,$data76,$data77,$data78,$data79,$data80];
    	
    }
}
