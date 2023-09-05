<?php

namespace App\Imports;

use Auth;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Groups;
use App\Models\TempUploads;
use App\Models\FileUploadLogs;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Readers\LaravelExcelReader;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithStartRow;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;

class TempUploadsImport implements ToModel, WithBatchInserts, WithChunkReading, WithStartRow, WithCalculatedFormulas
{
    /**
    * @param Collection $collection
    */
    public $uniq_id, $user_id, $upload_type;

    public function __construct($uniq_id, $user_id, $upload_type)
    {
        $this->uniq_id = $uniq_id;
        $this->user_id = $user_id;
        $this->upload_type = $upload_type;
    }

    public function formatDateExcel($cell_data, $value){

    	$temp_data = TempUploads::where('file_id','=' , $this->uniq_id)->where('header','=' , 2)->first();
	    
	    switch ($temp_data[$cell_data]) {

	    	case 'amount':
	    		  return number_format($value, 2);
	    		break;

	    	case 'date':
	    			try {
				        return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value)->format('Y-m-d');
				    } catch (\ErrorException $e) {
				        return $value;
				    }
	    		break;
	    	
	    	default:
	    		return $value;
	    		break;
	    }
	   
	}

    public function model(array $row)
    {
    	$user = \Auth::user();
    	$uniq_id = $this->uniq_id;
        return new TempUploads([
            	'user' 			=> $this->user_id,
                'upload_type' 	=> $this->upload_type,
                'header' 		=> 0,
                'file_id' 		=> $uniq_id,
                'data1' 		=> (isset($row[0]) ? $this->formatDateExcel('data1', $row[0]):'--'),
                'data2' 		=> (isset($row[1]) ? $this->formatDateExcel('data2', $row[1]):'--'),
                'data3'			=> (isset($row[2]) ? $this->formatDateExcel('data3', $row[2]):'--'),
		        'data4'			=> (isset($row[3]) ? $this->formatDateExcel('data4', $row[3]):'--'),
		        'data5'			=> (isset($row[4]) ? $this->formatDateExcel('data5', $row[4]):'--'),
		        'data6'			=> (isset($row[5]) ? $this->formatDateExcel('data6', $row[5]):'--'),
		        'data7'			=> (isset($row[6]) ? $this->formatDateExcel('data7', $row[6]):'--'),
		        'data8'			=> (isset($row[7]) ? $this->formatDateExcel('data8', $row[7]):'--'),
		        'data9'			=> (isset($row[8]) ? $this->formatDateExcel('data9', $row[8]):'--'),
		        'data10'		=> (isset($row[9]) ? $this->formatDateExcel('data10', $row[9]):'--'),
		        'data11'		=> (isset($row[10]) ? $this->formatDateExcel('data11', $row[10]):'--'),
		        'data12'		=> (isset($row[11]) ? $this->formatDateExcel('data12', $row[11]):'--'),
		        'data13'		=> (isset($row[12]) ? $this->formatDateExcel('data13', $row[12]):'--'),
		        'data14'		=> (isset($row[13]) ? $this->formatDateExcel('data14', $row[13]):'--'),
		        'data15'		=> (isset($row[14]) ? $this->formatDateExcel('data15', $row[14]):'--'),
		        'data16'		=> (isset($row[15]) ? $this->formatDateExcel('data16', $row[15]):'--'),
		        'data17'		=> (isset($row[16]) ? $this->formatDateExcel('data17', $row[16]):'--'),
		        'data18'		=> (isset($row[17]) ? $this->formatDateExcel('data18', $row[17]):'--'),
		        'data19'		=> (isset($row[18]) ? $this->formatDateExcel('data19', $row[18]):'--'),
		        'data20'		=> (isset($row[19]) ? $this->formatDateExcel('data20', $row[19]):'--'),
		        'data21'		=> (isset($row[20]) ? $this->formatDateExcel('data21', $row[20]):'--'),
		        'data22'		=> (isset($row[21]) ? $this->formatDateExcel('data22', $row[21]):'--'),
		        'data23'		=> (isset($row[22]) ? $this->formatDateExcel('data23', $row[22]):'--'),
		        'data24'		=> (isset($row[23]) ? $this->formatDateExcel('data24', $row[23]):'--'),
		        'data25'		=> (isset($row[24]) ? $this->formatDateExcel('data25', $row[24]):'--'),
		        'data26'		=> (isset($row[25]) ? $this->formatDateExcel('data26', $row[25]):'--'),
		        'data27'		=> (isset($row[26]) ? $this->formatDateExcel('data27', $row[26]):'--'),
		        'data28'		=> (isset($row[27]) ? $this->formatDateExcel('data28', $row[27]):'--'),
		        'data29'		=> (isset($row[28]) ? $this->formatDateExcel('data29', $row[28]):'--'),
		        'data30'		=> (isset($row[29]) ? $this->formatDateExcel('data30', $row[29]):'--'),
		        'data31'		=> (isset($row[30]) ? $this->formatDateExcel('data31', $row[30]):'--'),
		        'data32'		=> (isset($row[31]) ? $this->formatDateExcel('data32', $row[31]):'--'),
		        'data33'		=> (isset($row[32]) ? $this->formatDateExcel('data33', $row[32]):'--'),
		        'data34'		=> (isset($row[33]) ? $this->formatDateExcel('data34', $row[33]):'--'),
		        'data35'		=> (isset($row[34]) ? $this->formatDateExcel('data35', $row[34]):'--'),
		        'data36'		=> (isset($row[35]) ? $this->formatDateExcel('data36', $row[35]):'--'),
		        'data37'		=> (isset($row[36]) ? $this->formatDateExcel('data37', $row[36]):'--'),
		        'data38'		=> (isset($row[37]) ? $this->formatDateExcel('data38', $row[37]):'--'),
		        'data39'		=> (isset($row[38]) ? $this->formatDateExcel('data39', $row[38]):'--'),
		        'data40'		=> (isset($row[39]) ? $this->formatDateExcel('data40', $row[39]):'--'),
		        'data41'		=> (isset($row[40]) ? $this->formatDateExcel('data41', $row[40]):'--'),
		        'data42'		=> (isset($row[41]) ? $this->formatDateExcel('data42', $row[41]):'--'),
		        'data43'		=> (isset($row[42]) ? $this->formatDateExcel('data43', $row[42]):'--'),
		        'data44'		=> (isset($row[43]) ? $this->formatDateExcel('data44', $row[43]):'--'),
		        'data45'		=> (isset($row[44]) ? $this->formatDateExcel('data45', $row[44]):'--'),
		        'data46'		=> (isset($row[45]) ? $this->formatDateExcel('data46', $row[45]):'--'),
		        'data47'		=> (isset($row[46]) ? $this->formatDateExcel('data47', $row[46]):'--'),
		        'data48'		=> (isset($row[47]) ? $this->formatDateExcel('data48', $row[47]):'--'),
		        'data49'		=> (isset($row[48]) ? $this->formatDateExcel('data49', $row[48]):'--'),
		        'data50'		=> (isset($row[49]) ? $this->formatDateExcel('data50', $row[49]):'--'),
		        'data51'		=> (isset($row[50]) ? $this->formatDateExcel('data51', $row[50]):'--'),
		        'data52'		=> (isset($row[51]) ? $this->formatDateExcel('data52', $row[51]):'--'),
		        'data53'		=> (isset($row[52]) ? $this->formatDateExcel('data53', $row[52]):'--'),
		        'data54'		=> (isset($row[53]) ? $this->formatDateExcel('data54', $row[53]):'--'),
		        'data55'		=> (isset($row[54]) ? $this->formatDateExcel('data55', $row[54]):'--'),
		        'data56'		=> (isset($row[55]) ? $this->formatDateExcel('data56', $row[55]):'--'),
		        'data57'		=> (isset($row[56]) ? $this->formatDateExcel('data57', $row[56]):'--'),
		        'data58'		=> (isset($row[57]) ? $this->formatDateExcel('data58', $row[57]):'--'),
		        'data59'		=> (isset($row[58]) ? $this->formatDateExcel('data59', $row[58]):'--'),
		        'data60'		=> (isset($row[59]) ? $this->formatDateExcel('data60', $row[59]):'--'),
		        'data61'		=> (isset($row[60]) ? $this->formatDateExcel('data61', $row[60]):'--'),
		        'data62'		=> (isset($row[61]) ? $this->formatDateExcel('data62', $row[61]):'--'),
		        'data63'		=> (isset($row[62]) ? $this->formatDateExcel('data63', $row[62]):'--'),
		        'data64'		=> (isset($row[63]) ? $this->formatDateExcel('data64', $row[63]):'--'),
		        'data65'		=> (isset($row[64]) ? $this->formatDateExcel('data65', $row[64]):'--'),
		        'data66'		=> (isset($row[65]) ? $this->formatDateExcel('data66', $row[65]):'--'),
		        'data67'		=> (isset($row[66]) ? $this->formatDateExcel('data67', $row[66]):'--'),
		        'data68'		=> (isset($row[67]) ? $this->formatDateExcel('data68', $row[67]):'--'),
		        'data69'		=> (isset($row[68]) ? $this->formatDateExcel('data69', $row[68]):'--'),
		        'data70'		=> (isset($row[69]) ? $this->formatDateExcel('data70', $row[69]):'--'),
		        'data71'		=> (isset($row[70]) ? $this->formatDateExcel('data71', $row[70]):'--'),
		        'data72'		=> (isset($row[71]) ? $this->formatDateExcel('data72', $row[71]):'--'),
		        'data73'		=> (isset($row[72]) ? $this->formatDateExcel('data73', $row[72]):'--'),
		        'data74'		=> (isset($row[73]) ? $this->formatDateExcel('data74', $row[73]):'--'),
		        'data75'		=> (isset($row[74]) ? $this->formatDateExcel('data75', $row[74]):'--'),
		        'data76'		=> (isset($row[75]) ? $this->formatDateExcel('data76', $row[75]):'--'),
		        'data77'		=> (isset($row[76]) ? $this->formatDateExcel('data77', $row[76]):'--'),
		        'data78'		=> (isset($row[77]) ? $this->formatDateExcel('data78', $row[77]):'--'),
		        'data79'		=> (isset($row[78]) ? $this->formatDateExcel('data79', $row[78]):'--'),
		        'data80'		=> (isset($row[79]) ? $this->formatDateExcel('data80', $row[79]):'--'),
		        'data81'		=> (isset($row[80]) ? $this->formatDateExcel('data81', $row[80]):'--'),
		        'data82'		=> (isset($row[81]) ? $this->formatDateExcel('data82', $row[81]):'--'),
		        'data83'		=> (isset($row[82]) ? $this->formatDateExcel('data83', $row[82]):'--'),
		        'data84'		=> (isset($row[83]) ? $this->formatDateExcel('data84', $row[83]):'--'),
		        'data85'		=> (isset($row[84]) ? $this->formatDateExcel('data85', $row[84]):'--'),
		        'data86'		=> (isset($row[85]) ? $this->formatDateExcel('data86', $row[85]):'--'),
		        'data87'		=> (isset($row[86]) ? $this->formatDateExcel('data87', $row[86]):'--'),
		        'data88'		=> (isset($row[87]) ? $this->formatDateExcel('data88', $row[87]):'--'),
		        'data89'		=> (isset($row[88]) ? $this->formatDateExcel('data89', $row[88]):'--'),
		        'data90'		=> (isset($row[89]) ? $this->formatDateExcel('data90', $row[89]):'--'),
        ]);



    }

    public function startRow(): int
    {
        return 2;
    }

    public function batchSize(): int
    {
        return 500;
    }

    public function chunkSize(): int
    {
        return 500;
    }
}
