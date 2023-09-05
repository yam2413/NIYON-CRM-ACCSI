<?php

namespace App\Imports;

use Auth;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Groups;
use App\Models\TempUploads;
use App\Models\FileUploadLogs;
use App\Models\CrmBorrowers;
use App\Models\CrmLeads;
use App\Models\CrmLogs;
use App\Models\FileHeaders;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithBatchInserts;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithCalculatedFormulas;

class AutomaticNewLeadsImport implements ToModel, WithBatchInserts, WithChunkReading, WithHeadingRow, WithCalculatedFormulas
{
    /**
    * @param Collection $collection
    */
    public $file_id, $user_id, $upload_type, $group_id;

    public function __construct($file_id, $user_id, $upload_type, $group_id)
    {
        $this->file_id = $file_id;
        $this->user_id = $user_id;
        $this->upload_type = $upload_type;
        $this->group_id = $group_id;
    }

    protected function formatContact($data){ 

        $value = preg_replace("/[^0-9]/", "", $data);

        $total_char = strlen($value);


        switch ($total_char) {
            case '12':

                if(substr($value,0,2) == '63'){
                    return '0'.substr($value,2,strlen($value));
                }else{
                    return '';
                }

                break;

            case '11':

                if( substr($value,0,2) == '09' ){
                    return $value;
                }else{
                    return '';
                }

                break;

            case '10':
                    if( substr($value,0,2) == '02' ){
                        return $value;
                    }else{
                        return '0'.$value;
                    }
                    
                break;

            case '8':
                    return '02'.$value;
                break;
            
            default:
                return '';
                break;
        }

    }

    protected function checkformatContact($data){
        if($data == ''){
            return true;
        } 

        $value = preg_replace("/[^0-9]/", "", $data);

        $total_char = strlen($value);
        


        switch ($total_char) {
            case '12':

                if(substr($value,0,2) == '63'){
                    return true;
                }else{
                    return false;
                }

                break;

            case '11':

                if( substr($value,0,2) == '09' ){
                    return true;
                }else{
                    return false;
                }

                break;

            case '10':
                    if( substr($value,0,2) == '02' ){
                        return true;
                    }else{
                        if(substr($value,0,1) == '9'){
                            return true;
                        }
                        return false;
                    }
               
                break;

            case '8':
                    return true;
                break;
            
            default:
                    return false;
                break;
        }

    }

    public function formatDateExcel($type, $value){
   
	    switch ($type) {

	    	case 'amount':
                
               if($value != ''){
                   return str_replace(array(',',' '), '', $value); 
               }else{
                    return 0;
               }

	    		break;

	    	case 'date':
                    if($value != ''){
                        try {
                            return \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($value)->format('d-M-Y');
                        } catch (\ErrorException $e) {
                            return $value;
                        }
                    }
	    			return '';
	    		break;
	    	
	    	default:
	    		return $value;
	    		break;
	    }
	   
	}

    public function cfield($assign_field){
        $records = FileHeaders::orderBy('id','ASC');
	    $records->where('group','=' ,$this->group_id);
        $records->where('assign_field','=' ,$assign_field);
	    $records = $records->first();
        if(isset($records->field_name)){
            
            return $this->formatDateExcel($records->data_type, $records->field_name);
        }
        return null;
    }

    public function cdata($assign_field, $data){
        $records = FileHeaders::orderBy('id','ASC');
	    $records->where('group','=' ,$this->group_id);
        $records->where('assign_field','=' ,$assign_field);
	    $records = $records->first();
        if(isset($records->field_name)){
            
            return $this->formatDateExcel($records->data_type, $data);
        }
        return null;
    }

    public function model(array $row)
    {
        $user = \Auth::user();
    	$file_id          = $this->file_id;

        $records = FileHeaders::orderBy('id','ASC');
	    $records->where('group','=' ,$this->group_id);
	    $records = $records->get();
        $array_data     = [];
        $arr_data_type  = [];
        foreach ($records as $key => $record) {
            if(ctype_digit($record->special_value)){
                $array_data[] = $record->special_value;
            }else{
                $array_data[] = $record->field_name;
            }
            
            $arr_data_type[] = $record->data_type;
        }
        ####################FIEL_NAME#############################
        $data_implode = implode(',', $array_data);
		$data = explode(',', $data_implode);
        ####################DATA_TYPE#############################
        $data_type_implode = implode(',', $arr_data_type);
		$datatype = explode(',', $data_type_implode);
      
       
        
        //$array_l = array('name' => 'ch_name');
        $full_name        = ($this->cfield('ch_name') != null) ? $this->cdata('ch_name', $row[$this->cfield('ch_name')]):'';
        $account_no       = ($this->cfield('account_no') != null) ? $this->cdata('account_no', $row[$this->cfield('account_no')]):'';

        $birthday         = '';
        
        $cycle_day        = '';
        $address          = '';
        $email            = '';
        $due_date         = '';
        $outstanding_bal  = ($this->cfield('outstanding_amount') != null) ? $this->cdata('outstanding_amount', $row[$this->cfield('outstanding_amount')]):'';
        $loan_amount      = '';
        $endo_date        = '';

        
        $home_no          = ($this->cfield('home_no') != null) ? $this->cdata('home_no', $row[$this->cfield('home_no')]):'';
        $business_no      = ($this->cfield('office_no') != null) ? $this->cdata('office_no', $row[$this->cfield('office_no')]):'';
        $cellphone_no     = ($this->cfield('mobile_no') != null) ? $this->cdata('mobile_no', $row[$this->cfield('mobile_no')]):'';
        $other_phone_no_1 = ($this->cfield('other_contact_1') != null) ? $this->cdata('other_contact_1', $row[$this->cfield('other_contact_1')]):'';
        $other_phone_no_2 = ($this->cfield('other_contact_2') != null) ? $this->cdata('other_contact_2', $row[$this->cfield('other_contact_2')]):'';
        $other_phone_no_3 = ($this->cfield('other_contact_3') != null) ? $this->cdata('other_contact_3', $row[$this->cfield('other_contact_3')]):'';
        $other_phone_no_4 = ($this->cfield('other_contact_4') != null) ? $this->cdata('other_contact_4', $row[$this->cfield('other_contact_4')]):'';
        $other_phone_no_5 = ($this->cfield('other_contact_5') != null) ? $this->cdata('other_contact_5', $row[$this->cfield('other_contact_5')]):'';


        $assign_group     = $row['assign_group'];
    	$leads_no 		  =  'LEADS-'.substr(md5(rand(999, 999999)), 0, 3).'-'.$account_no.'-'.date('siH');
    	$coll_code        = $row['coll_code'];
        


        

        $error = 0;
        $success = 0;
        $error_msg = "";

        $check_coll_codes   = User::select('count(*) as allcount')
                              ->where('coll', '=', $coll_code)
                              ->count();

        $check_group_exist   = Groups::select('count(*) as allcount')
                              ->where('name', '=', $assign_group)
                              ->count();
                              

        //Check if the coll codes exist or not
        if($check_coll_codes == 0){
            $error = 1;
        	$error_msg = "Coll Code not found";
        }else if($check_group_exist == 0){
        	$error = 1;
        	$error_msg = "Group Name not found";
        }else if($this->checkformatContact($home_no) === false){
        	$error = 1;
        	$error_msg = "Invalid format for Home No.";
        }else if($this->checkformatContact($business_no) === false){
        	$error = 1;
        	$error_msg = "Invalid format for Office/Business No.";
        }else if($this->checkformatContact($cellphone_no) === false){
        	$error = 1;
        	$error_msg = "Invalid format for Cellhphone/Mobile No.";
        }else if($this->checkformatContact($other_phone_no_1) === false){
        	$error = 1;
        	$error_msg = "Invalid format for Other Contact No.";
        }else if($this->checkformatContact($other_phone_no_2) === false){
        	$error = 1;
        	$error_msg = "Invalid format for Other Contact No.";
        }else if($this->checkformatContact($other_phone_no_3) === false){
        	$error = 1;
        	$error_msg = "Invalid format for Other Contact No.";
        }else if($this->checkformatContact($other_phone_no_4) === false){
        	$error = 1;
        	$error_msg = "Invalid format for Other Contact No.";
        }else if($this->checkformatContact($other_phone_no_5) === false){
        	$error = 1;
        	$error_msg = "Invalid format for Other Contact No.";
        }else{
        	$success 			= 1;
        	$user_id 			= User::where('coll', '=', $coll_code)->first();
        	$groups 			= Groups::where('name', '=', $assign_group)->first();
        	$check_borrowers   	= CrmBorrowers::select('count(*) as allcount')
                              		->where('full_name', '=', $full_name)
                              		//->where('birthday', '=', $birthday)
                              		->count();

            switch ($check_borrowers) {
            	case 0:
            		
                    $check_leads_no    = CrmBorrowers::select('count(*) as allcount')
                                    ->where('profile_id', '=', $leads_no)
                                    ->count();

                    if($check_leads_no > 0){
                        $leads_no         =  'LEADS-'.substr(md5(rand(999, 999999)), 0, 3).date('siHdmY');
                    }

            		$insert_CrmBorrowers = array(
	                    'profile_id'      => $leads_no,
	                    'full_name'       => $full_name,
	                    'birthday'        => $birthday,
	                    'address'         => $address,
	                    'email'           => $email,
	                    'home_no'         => $this->formatContact($home_no),
	                    'business_no'     => $this->formatContact($business_no),
	                    'cellphone_no'    => $this->formatContact($cellphone_no),
	                    'other_phone_1'   => $this->formatContact($other_phone_no_1),
	                    'other_phone_2'   => $this->formatContact($other_phone_no_2),
	                    'other_phone_3'   => $this->formatContact($other_phone_no_3),
	                    'other_phone_4'   => $this->formatContact($other_phone_no_4),
	                    'other_phone_5'   => $this->formatContact($other_phone_no_5),
	                    'created_at'      => Carbon::now(),
	                    'updated_at'      => Carbon::now(),
	                );
	                CrmBorrowers::insert($insert_CrmBorrowers);

	                $log_txt = 'New account added in leads';

	                $insert_CrmLeads = array(
	                    'profile_id'            => $leads_no,
	                    'file_id'               => $file_id,
	                    'assign_user'           => (isset($user_id->id)) ? $user_id->id:0,
	                    'assign_group'          => (isset($groups->id)) ? $groups->id:0,
	                    'account_number'        => $account_no,
	                    'endo_date'             => $endo_date,
	                    'due_date'              => $due_date,
	                    'outstanding_balance'   => $outstanding_bal,
	                    'loan_amount'           => $loan_amount,
	                    'cycle_day'             => $cycle_day,
                        'status'                => 'NEW',
                        'status_updated'        => date('Y-m-d H:i'),
	                    'created_at'            => Carbon::now(),
	                    'updated_at'            => Carbon::now(),
	                );
	                CrmLeads::insert($insert_CrmLeads);

            		break;
            	
            	default:
            		$crm_borrowers   = CrmBorrowers::where('full_name', '=', $full_name)
                              			//->where('birthday', '=', $birthday)
                              			->first();

                    $leads_no = $crm_borrowers['profile_id'];

                    $check_leads_no    = CrmBorrowers::select('count(*) as allcount')
                                    ->where('profile_id', '=', $leads_no)
                                    ->where('full_name', '!=', $full_name)
                                    ->count();
                    if($check_leads_no > 0){
                        $leads_no         =  'LEADS-'.substr(md5(rand(999, 999999)), 0, 3).'-'.$account_no.'-'.date('siH');
                        
                    }

                    $update_leads = array(
                        'profile_id'    => $leads_no,
                        'deleted'       => 1,
                        'updated_at'    => Carbon::now(),
                     );

                    
                                
                    CrmLeads::where('account_number', '=', $account_no)->where('deleted', '=', 0)->update($update_leads);

                    $update_borrowers = array(
                        'full_name'       => $full_name,
                        'birthday'        => $birthday,
                        'address'         => $address,
                        'email'           => $email,
                        'home_no'         => $this->formatContact($home_no),
                        'business_no'     => $this->formatContact($business_no),
                        'cellphone_no'    => $this->formatContact($cellphone_no),
                        'other_phone_1'   => $this->formatContact($other_phone_no_1),
                        'other_phone_2'   => $this->formatContact($other_phone_no_2),
                        'other_phone_3'   => $this->formatContact($other_phone_no_3),
                        'other_phone_4'   => $this->formatContact($other_phone_no_4),
                        'other_phone_5'   => $this->formatContact($other_phone_no_5),
                        'profile_id'      => $leads_no,
                        'updated_at'      => Carbon::now(),
                    );
                                
                    CrmBorrowers::where('profile_id', '=', $crm_borrowers['profile_id'])->update($update_borrowers);

                     $insert_CrmLeads = array(
                        'profile_id'            => $leads_no,
                        'file_id'               => $file_id,
	                    'assign_user'           => (isset($user_id->id)) ? $user_id->id:0,
	                    'assign_group'          => (isset($groups->id)) ? $groups->id:0,
	                    'account_number'        => $account_no,
	                    'endo_date'             => $endo_date,
	                    'due_date'              => $due_date,
	                    'outstanding_balance'   => $outstanding_bal,
	                    'loan_amount'           => $loan_amount,
	                    'cycle_day'             => $cycle_day,
                        'status'                => 'NEW',
                        'status_updated'        => date('Y-m-d H:i'),
                        'created_at'            => Carbon::now(),
                        'updated_at'            => Carbon::now(),
                    );
                    CrmLeads::insert($insert_CrmLeads);

                    $log_txt = 'Updated account in leads';
            		break;
            }


            CrmLogs::saveLogs(0, $leads_no, $full_name, $log_txt);
            CrmLogs::saveLogs(0, $leads_no, $full_name, 'Account has been assigned to '.$user_id->name);

        }


        

        return new TempUploads([
            	'user' 			=> $this->user_id,
                'upload_type' 	=> $this->upload_type,
                'header' 		=> 0,
                'file_id' 		=> $file_id,
                'profile_id' 	=> $leads_no,
                'error' 		=> $error,
                'error_msg' 	=> $error_msg,
                'success' 		=> $success,
                'groups' 		=> (isset($groups->id)) ? $groups->id:0,
                'data1' 		=> $row['assign_group'],
                'data2' 		=> $row['coll_code'],

                'data3' 		=> (isset($data[0])) ? $this->formatDateExcel($datatype[0], $row[$data[0]]):'',
                'data4' 		=> (isset($data[1])) ? $this->formatDateExcel($datatype[1], $row[$data[1]]):'',
                'data5'			=> (isset($data[2])) ? $this->formatDateExcel($datatype[2], $row[$data[2]]):'',
		        'data6'			=> (isset($data[3])) ? $this->formatDateExcel($datatype[3], $row[$data[3]]):'',
		        'data7'			=> (isset($data[4])) ? $this->formatDateExcel($datatype[4], $row[$data[4]]):'',
		        'data8'			=> (isset($data[5])) ? $this->formatDateExcel($datatype[5], $row[$data[5]]):'',
		        'data9'			=> (isset($data[6])) ? $this->formatDateExcel($datatype[6], $row[$data[6]]):'',
		        'data10'		=> (isset($data[7])) ? $this->formatDateExcel($datatype[7], $row[$data[7]]):'',
		        'data11'		=> (isset($data[8])) ? $this->formatDateExcel($datatype[8], $row[$data[8]]):'',
		        'data12'		=> (isset($data[9])) ? $this->formatDateExcel($datatype[9], $row[$data[9]]):'',
		        'data13'		=> (isset($data[10])) ? $this->formatDateExcel($datatype[10], $row[$data[10]]):'',
		        'data14'		=> (isset($data[11])) ? $this->formatDateExcel($datatype[11], $row[$data[11]]):'',
		        'data15'		=> (isset($data[12])) ? $this->formatDateExcel($datatype[12], $row[$data[12]]):'',
		        'data16'		=> (isset($data[13])) ? $this->formatDateExcel($datatype[13], $row[$data[13]]):'',
		        'data17'		=> (isset($data[14])) ? $this->formatDateExcel($datatype[14], $row[$data[14]]):'',
		        'data18'		=> (isset($data[15])) ? $this->formatDateExcel($datatype[15], $row[$data[15]]):'',
		        'data19'		=> (isset($data[16])) ? $this->formatDateExcel($datatype[16], $row[$data[16]]):'',
		        'data20'		=> (isset($data[17])) ? $this->formatDateExcel($datatype[17], $row[$data[17]]):'',
		        'data21'		=> (isset($data[18])) ? $this->formatDateExcel($datatype[18], $row[$data[18]]):'',
		        'data22'		=> (isset($data[19])) ? $this->formatDateExcel($datatype[19], $row[$data[19]]):'',
		        'data23'		=> (isset($data[20])) ? $this->formatDateExcel($datatype[20], $row[$data[20]]):'',
		        'data24'		=> (isset($data[21])) ? $this->formatDateExcel($datatype[21], $row[$data[21]]):'',
		        'data25'		=> (isset($data[22])) ? $this->formatDateExcel($datatype[22], $row[$data[22]]):'',
		        'data26'		=> (isset($data[23])) ? $this->formatDateExcel($datatype[23], $row[$data[23]]):'',
		        'data27'		=> (isset($data[24])) ? $this->formatDateExcel($datatype[24], $row[$data[24]]):'',
                'data28'        => (isset($data[25])) ? $this->formatDateExcel($datatype[25], $row[$data[25]]):'',
                'data29'        => (isset($data[26])) ? $this->formatDateExcel($datatype[26], $row[$data[26]]):'',
                'data30'        => (isset($data[27])) ? $this->formatDateExcel($datatype[27], $row[$data[27]]):'',
                'data31'        => (isset($data[28])) ? $this->formatDateExcel($datatype[28], $row[$data[28]]):'',
                'data32'        => (isset($data[29])) ? $this->formatDateExcel($datatype[20], $row[$data[29]]):'',
                'data33'        => (isset($data[30])) ? $this->formatDateExcel($datatype[30], $row[$data[30]]):'',

                'data34'        => (isset($data[31])) ? $this->formatDateExcel($datatype[31], $row[$data[31]]):'',
                'data35'        => (isset($data[32])) ? $this->formatDateExcel($datatype[32], $row[$data[32]]):'',
                'data36'        => (isset($data[33])) ? $this->formatDateExcel($datatype[33], $row[$data[33]]):'',
                'data37'        => (isset($data[34])) ? $this->formatDateExcel($datatype[34], $row[$data[34]]):'',
                'data38'        => (isset($data[35])) ? $this->formatDateExcel($datatype[35], $row[$data[35]]):'',
                'data39'        => (isset($data[36])) ? $this->formatDateExcel($datatype[36], $row[$data[36]]):'',
                'data40'        => (isset($data[37])) ? $this->formatDateExcel($datatype[37], $row[$data[37]]):'',
                'data41'        => (isset($data[38])) ? $this->formatDateExcel($datatype[38], $row[$data[38]]):'',
                'data42'        => (isset($data[39])) ? $this->formatDateExcel($datatype[39], $row[$data[39]]):'',
                'data43'        => (isset($data[40])) ? $this->formatDateExcel($datatype[40], $row[$data[40]]):'',
                'data44'        => (isset($data[41])) ? $this->formatDateExcel($datatype[41], $row[$data[41]]):'',
                'data45'        => (isset($data[42])) ? $this->formatDateExcel($datatype[42], $row[$data[42]]):'',
                'data46'        => (isset($data[43])) ? $this->formatDateExcel($datatype[43], $row[$data[43]]):'',
                'data47'        => (isset($data[44])) ? $this->formatDateExcel($datatype[44], $row[$data[44]]):'',
                'data48'        => (isset($data[45])) ? $this->formatDateExcel($datatype[45], $row[$data[45]]):'',
                'data49'        => (isset($data[46])) ? $this->formatDateExcel($datatype[46], $row[$data[46]]):'',
                'data50'        => (isset($data[47])) ? $this->formatDateExcel($datatype[47], $row[$data[47]]):'',
                'data51'        => (isset($data[48])) ? $this->formatDateExcel($datatype[48], $row[$data[48]]):'',
                'data52'        => (isset($data[49])) ? $this->formatDateExcel($datatype[49], $row[$data[49]]):'',
                'data53'        => (isset($data[50])) ? $this->formatDateExcel($datatype[50], $row[$data[50]]):'',

                'data54'        => (isset($data[51])) ? $this->formatDateExcel($datatype[51], $row[$data[51]]):'',
                'data55'        => (isset($data[52])) ? $this->formatDateExcel($datatype[52], $row[$data[52]]):'',
                'data56'        => (isset($data[53])) ? $this->formatDateExcel($datatype[53], $row[$data[53]]):'',
                'data57'        => (isset($data[54])) ? $this->formatDateExcel($datatype[54], $row[$data[54]]):'',
                'data58'        => (isset($data[55])) ? $this->formatDateExcel($datatype[55], $row[$data[55]]):'',
                'data59'        => (isset($data[56])) ? $this->formatDateExcel($datatype[56], $row[$data[56]]):'',
                'data60'        => (isset($data[57])) ? $this->formatDateExcel($datatype[57], $row[$data[57]]):'',
                'data61'        => (isset($data[58])) ? $this->formatDateExcel($datatype[58], $row[$data[58]]):'',
                'data62'        => (isset($data[59])) ? $this->formatDateExcel($datatype[59], $row[$data[59]]):'',
                'data63'        => (isset($data[60])) ? $this->formatDateExcel($datatype[60], $row[$data[60]]):'',
                'data64'        => (isset($data[61])) ? $this->formatDateExcel($datatype[61], $row[$data[61]]):'',
                'data65'        => (isset($data[62])) ? $this->formatDateExcel($datatype[62], $row[$data[62]]):'',
                'data66'        => (isset($data[63])) ? $this->formatDateExcel($datatype[63], $row[$data[63]]):'',
                'data67'        => (isset($data[64])) ? $this->formatDateExcel($datatype[64], $row[$data[64]]):'',
                'data68'        => (isset($data[65])) ? $this->formatDateExcel($datatype[65], $row[$data[65]]):'',
                'data69'        => (isset($data[66])) ? $this->formatDateExcel($datatype[66], $row[$data[66]]):'',
                'data70'        => (isset($data[67])) ? $this->formatDateExcel($datatype[67], $row[$data[67]]):'',
                'data71'        => (isset($data[68])) ? $this->formatDateExcel($datatype[68], $row[$data[68]]):'',
                'data72'        => (isset($data[69])) ? $this->formatDateExcel($datatype[69], $row[$data[69]]):'',

                'data73'        => (isset($data[70])) ? $this->formatDateExcel($datatype[70], $row[$data[70]]):'',
                'data74'        => (isset($data[71])) ? $this->formatDateExcel($datatype[71], $row[$data[71]]):'',
                'data75'        => (isset($data[72])) ? $this->formatDateExcel($datatype[72], $row[$data[72]]):'',
                'data76'        => (isset($data[73])) ? $this->formatDateExcel($datatype[73], $row[$data[73]]):'',
                'data77'        => (isset($data[74])) ? $this->formatDateExcel($datatype[74], $row[$data[74]]):'',
                'data78'        => (isset($data[75])) ? $this->formatDateExcel($datatype[75], $row[$data[75]]):'',
                'data79'        => (isset($data[76])) ? $this->formatDateExcel($datatype[76], $row[$data[76]]):'',
                'data80'        => (isset($data[77])) ? $this->formatDateExcel($datatype[77], $row[$data[77]]):'',
                'data81'        => (isset($data[78])) ? $this->formatDateExcel($datatype[78], $row[$data[78]]):'',
                'data82'        => (isset($data[79])) ? $this->formatDateExcel($datatype[79], $row[$data[79]]):'',
                'data83'        => (isset($data[80])) ? $this->formatDateExcel($datatype[80], $row[$data[80]]):'',
        ]);





    }

    public function headingRow(): int
    {
        return 1;
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
