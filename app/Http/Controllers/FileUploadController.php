<?php

namespace App\Http\Controllers;

use DB;
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
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\HeadingRowImport;
use App\Imports\TempUploadsImport;
use App\Exports\NewLeadsLogsExport;
use App\Exports\FileTemplateExport;
use App\Jobs\NewLeadsJob;
use App\Jobs\AutomaticNewLeadsJob;
use App\Jobs\DataTypesJob;
use App\Jobs\SyncNewLeadsJob;

class FileUploadController extends Controller
{
    public function index(){
        $user = \Auth::user();
        if($user->level == 0){
            $groups = Groups::where('file_header', 1)->get();
        }else{
            $groups = Groups::where('id', $user->group)->first();
        }
        $file_headers = FileHeaders::select('count(*) as allcount')->where('group', $user->group)->count();
    	return view('pages.file_uploads.index', compact('user','groups','file_headers'));
    }

    public function view_update_leads(){
        return view('pages.file_uploads.update_leads.index');
    }

    public function view_uploaded($uniq_id){
    	return view('pages.file_uploads.view_uploaded', compact('uniq_id'));
    }
    public function export_file_template($group_id,$file_name){
        //$file_name = strtolower(str_replace(' ','_',$file_name));
        $file_names = $file_name.'_template_'.date('Y-m-d H:i:s').'.xlsx';
        return Excel::download(new FileTemplateExport($group_id), $file_names);
    }

    public function export_new_leads_logs($file_id, $type, $upload_type){

        if($upload_type == 'new_leads'){
            $file_name = $type.'_new_leads_'.date('Y-m-d H:i:s').'.xlsx';
        }else{
            $file_name = $type.'_paid_account_'.date('Y-m-d H:i:s').'.xlsx';
        }

        return Excel::download(new NewLeadsLogsExport($type, $file_id, $upload_type), $file_name);
    }

    public function check_uploads($uniq_id){
    	$user = \Auth::user();

        $file_logs = FileUploadLogs::where('file_id','=' ,$uniq_id)->first();

    	$temp_uploads = TempUploads::where('user', $user->id)
    						->where('upload_type','=' ,'new_leads')
    						->where('header','=' ,'1')
    						->where('file_id','=' ,$uniq_id)
    						->first();
    	if($temp_uploads){
    		$select_datas = array(
    			'data1'		=> $temp_uploads['data1'],
		        'data2'		=> $temp_uploads['data2'],
		        'data3'		=> $temp_uploads['data3'],
		        'data4'		=> $temp_uploads['data4'],
		        'data5'		=> $temp_uploads['data5'],
		        'data6'		=> $temp_uploads['data6'],
		        'data7'		=> $temp_uploads['data7'],
		        'data8'		=> $temp_uploads['data8'],
		        'data9'		=> $temp_uploads['data9'],
		        'data10'	=> $temp_uploads['data10'],
		        'data11'	=> $temp_uploads['data11'],
		        'data12'	=> $temp_uploads['data12'],
		        'data13'	=> $temp_uploads['data13'],
		        'data14'	=> $temp_uploads['data14'],
		        'data15'	=> $temp_uploads['data15'],
		        'data16'	=> $temp_uploads['data16'],
		        'data17'	=> $temp_uploads['data17'],
		        'data18'	=> $temp_uploads['data18'],
		        'data19'	=> $temp_uploads['data19'],
		        'data20'	=> $temp_uploads['data20'],
		        'data21'	=> $temp_uploads['data21'],
		        'data22'	=> $temp_uploads['data22'],
		        'data23'	=> $temp_uploads['data23'],
		        'data24'	=> $temp_uploads['data24'],
		        'data25'	=> $temp_uploads['data25'],
		        'data26'	=> $temp_uploads['data26'],
		        'data27'	=> $temp_uploads['data27'],
		        'data28'	=> $temp_uploads['data28'],
		        'data29'	=> $temp_uploads['data29'],
		        'data30'	=> $temp_uploads['data30'],
		        'data31'	=> $temp_uploads['data31'],
		        'data32'	=> $temp_uploads['data32'],
		        'data33'	=> $temp_uploads['data33'],
		        'data34'	=> $temp_uploads['data34'],
		        'data35'	=> $temp_uploads['data35'],
		        'data36'	=> $temp_uploads['data36'],
		        'data37'	=> $temp_uploads['data37'],
		        'data38'	=> $temp_uploads['data38'],
		        'data39'	=> $temp_uploads['data39'],
		        'data40'	=> $temp_uploads['data40'],
		        'data41'	=> $temp_uploads['data41'],
		        'data42'	=> $temp_uploads['data42'],
		        'data43'	=> $temp_uploads['data43'],
		        'data44'	=> $temp_uploads['data44'],
		        'data45'	=> $temp_uploads['data45'],
		        'data46'	=> $temp_uploads['data46'],
		        'data47'	=> $temp_uploads['data47'],
		        'data48'	=> $temp_uploads['data48'],
		        'data49'	=> $temp_uploads['data49'],
		        'data50'	=> $temp_uploads['data50'],
		        'data51'	=> $temp_uploads['data51'],
		        'data52'	=> $temp_uploads['data52'],
		        'data53'	=> $temp_uploads['data53'],
		        'data54'	=> $temp_uploads['data54'],
		        'data55'	=> $temp_uploads['data55'],
		        'data56'	=> $temp_uploads['data56'],
		        'data57'	=> $temp_uploads['data57'],
		        'data58'	=> $temp_uploads['data58'],
		        'data59'	=> $temp_uploads['data59'],
		        'data60'	=> $temp_uploads['data60'],
		        'data61'	=> $temp_uploads['data61'],
		        'data62'	=> $temp_uploads['data62'],
		        'data63'	=> $temp_uploads['data63'],
		        'data64'	=> $temp_uploads['data64'],
		        'data65'	=> $temp_uploads['data65'],
		        'data66'	=> $temp_uploads['data66'],
		        'data67'	=> $temp_uploads['data67'],
		        'data68'	=> $temp_uploads['data68'],
		        'data69'	=> $temp_uploads['data69'],
		        'data70'	=> $temp_uploads['data70'],
		        'data71'	=> $temp_uploads['data71'],
		        'data72'	=> $temp_uploads['data72'],
		        'data73'	=> $temp_uploads['data73'],
		        'data74'	=> $temp_uploads['data74'],
		        'data75'	=> $temp_uploads['data75'],
		        'data76'	=> $temp_uploads['data76'],
		        'data77'	=> $temp_uploads['data77'],
		        'data78'	=> $temp_uploads['data78'],
		        'data79'	=> $temp_uploads['data79'],
		        'data80'	=> $temp_uploads['data80'],
		        'data81'	=> $temp_uploads['data81'],
		        'data82'	=> $temp_uploads['data82'],
		        'data83'	=> $temp_uploads['data83'],
		        'data84'	=> $temp_uploads['data84'],
		        'data85'	=> $temp_uploads['data85'],
		        'data86'	=> $temp_uploads['data86'],
		        'data87'	=> $temp_uploads['data87'],
		        'data88'	=> $temp_uploads['data88'],
		        'data89'	=> $temp_uploads['data89'],
		        'data90'	=> $temp_uploads['data90'], 
    		);
    	}else{

    	}				
    	$groups   = Groups::get();
    	return view('pages.file_uploads.check_uploads', compact('uniq_id','select_datas','groups','temp_uploads','file_logs'));
    }


    public function upload_file(Request $request){
    	$user = \Auth::user();
    	$uniq_id =  substr(md5(rand(999, 999999)), 0, 3).date('YmdHis');
    	$error = "false";
    	$msg = "";
        $error_msg 	= [];
        $extension 	= $request->file->extension();
        $path 		= $request->file->storeAs('public/file_upload', $uniq_id.'.'.$extension);

        $insert_data = array(
            'upload_type'     => 'new_leads',
            'file_id'         => $uniq_id,
            'path'      	  => $path,
            'user'      	  => $user->id,
            'created_at'      => Carbon::now(),
            'updated_at'      => Carbon::now(),
        );
        FileUploadLogs::insert($insert_data);

        NewLeadsJob::dispatch($uniq_id, $extension, $user->id);
      
        SystemLogs::saveLogs($user->id, 'Upload new leads with file id '.$uniq_id);
        $msg = 'Successfully upload the file in queuing list, Please go to upload logs to check the status of your file upload.';

    	  return response()->json([
                'error'  => $error,
                'msg'    => $msg,
                'uniq_id' => $uniq_id
        ]);
    }


    public function upload_file_specify(Request $request){
        $user = \Auth::user();
        $uniq_id =  substr(md5(rand(999, 999999)), 0, 3).date('YmdHis');
        $error = "false";
        $msg = "";
        $error_msg  = [];

        $headings = (new HeadingRowImport)->toArray($request->file('file'));

        $explod_name = explode('_',$request->file('file')->getClientOriginalName());
        $file_name = $explod_name[0];
        $groups = Groups::where('name', $file_name)->first();

        if(!isset($groups->id)){
            return response()->json([
                'error'  => 'true',
                'msg'    => 'File template for this group not found',
            ]);
        }

        $records = FileHeaders::orderBy('id','ASC');
	    $records->where('group','=' ,$groups->id);
	    $records = $records->get();
	    $data_field = [];
	    // foreach ($records as $key => $record) {
	        
        //     if(ctype_digit($record->special_value)){
        //         $data_field[] = $record->special_value;
        //     }else{
        //         $data_field[] = strtolower($record->field_name);
        //     }
            
	     	

	    // }
        
        foreach ($headings[0] as $key => $value) {

            if(!in_array('coll_code', $value)){
                return response()->json([
                    'error'  => 'true',
                    'msg'    => 'Coll Code column is missing',
                ]);
            }

            if(!in_array('assign_group', $value)){
                return response()->json([
                    'error'  => 'true',
                    'msg'    => 'Assign Group is missing',
                ]);
            }

            foreach ($records as $record) {
	     
                //$data_fields = strtolower($record->field_name);

                if(ctype_digit($record->special_value)){
                    $data_fields = $record->special_value;
                }else{
                    $data_fields = strtolower($record->field_name);
                }
                if(!in_array($data_fields,$value)){
                    return response()->json([
                        'error'  => 'true',
                        'msg'    => $data_fields.' is missing',
                    ]);
                }
    
            }
            
            
        }

        // return response()->json([
        //     'error'  => 'true',
        //     'msg'    => $file_name,
        // ]);

        $extension   = $request->file->extension();
        $path        = $request->file->storeAs('public/file_upload', $uniq_id.'.'.$extension);

        $insert_data = array(
            'upload_type'     => 'new_leads',
            'file_id'         => $uniq_id,
            'path'            => $path,
            'user'            => $user->id,
            'created_at'      => Carbon::now(),
            'updated_at'      => Carbon::now(),
        );
        FileUploadLogs::insert($insert_data);

        AutomaticNewLeadsJob::dispatch($uniq_id, $extension, $user->id, $groups->id);
      
        SystemLogs::saveLogs($user->id, 'Upload new leads with file id '.$uniq_id);
        $msg = 'Successfully upload the file in queuing list, Please go to upload logs to check the status of your file upload.';

          return response()->json([
                'error'  => $error,
                'msg'    => $msg,
                'uniq_id' => $uniq_id
        ]);
    }

    public function sync_leads(Request $request){
    	$user = \Auth::user();
    	$error = "false";
    	$msg = "";
        $error_msg 	= [];

        $file_id          = $request->file_id;
        $groups           = $request->groups;
        
        $array_data_fields = array(
            'coll_code'        => $request->coll_code,
            'account_no'       => $request->account_no,
            'full_name'        => $request->full_name,
            'cycle_day'        => $request->cycle_day,
            'address'          => $request->address,
            'due_date'         => $request->due_date,
            'outstanding_bal'  => $request->outstanding_bal,
            'loan_amount'      => $request->loan_amount,
            'endo_date'        => $request->endo_date,
            'email'            => $request->email,
            'home_no'          => $request->home_no,
            'business_no'      => $request->business_no,
            'cellphone_no'     => $request->cellphone_no,
            'other_phone_no_1' => $request->other_phone_no_1,
            'other_phone_no_2' => $request->other_phone_no_2,
            'other_phone_no_3' => $request->other_phone_no_3,
            'other_phone_no_4' => $request->other_phone_no_4,
            'other_phone_no_5' => $request->other_phone_no_5,
        );

        $check_if_already_sync = FileUploadLogs::select('count(*) as allcount')
                                    ->where('data_type', '=', 0)
                                    ->where('file_id', '=', $file_id)
                                    ->count();
        if($check_if_already_sync > 0){
            return response()->json([
                'error'   => 'true',
                'msg'     => 'Please wait while the file is being validate. This will take longer if the leads more than thousand.',
                'uniq_id' => $file_id
            ]);
        }

        $post_sync_status = array(
          'status'         => 4,
          'updated_at'     => Carbon::now(),
        );
                    
        FileUploadLogs::where('file_id', '=', $file_id)->update($post_sync_status);

        $job_id = SyncNewLeadsJob::dispatch($file_id, $groups, json_encode($array_data_fields));

        $msg = 'The upload file is now syncing in the lead list.';

    	return response()->json([
            'error'   => $error,
            'msg'     => $msg,
            'uniq_id' => $file_id
        ]);
    }

    public function cancel_upload_file(Request $request){
    	$user = \Auth::user();
    	$uniq_id =  $request->file_id;
    	$error = "false";
    	$msg = "";
        $error_msg 	= [];

        $post_sync = array(
            'status'     => 3,
            'updated_at' => Carbon::now(),
        );
                    
        FileUploadLogs::where('file_id', '=', $uniq_id)->update($post_sync);
      
        SystemLogs::saveLogs($user->id, 'Cancelled uploaded leads with file id '.$uniq_id);
        $msg = 'Successfully cancelled the upload leads.';

    	return response()->json([
                'error'  => $error,
                'msg'    => $msg,
        ]);
    }

    public function data_type_upload_file(Request $request){
        $user = \Auth::user();
        $uniq_id =  $request->file_id;
        $error = "false";
        $msg = "";
        $error_msg  = [];

        $array_data_types = array(
            'data1' => $request->data1,
            'data2' => $request->data2,
            'data3' => $request->data3,
            'data4' => $request->data4,
            'data5' => $request->data5,
            'data6' => $request->data6,
            'data7' => $request->data7,
            'data8' => $request->data8,
            'data9' => $request->data9,
            'data10' => $request->data10,
            'data11' => $request->data11,
            'data12' => $request->data12,
            'data13' => $request->data13,
            'data14' => $request->data14,
            'data15' => $request->data15,
            'data16' => $request->data16,
            'data17' => $request->data17,
            'data18' => $request->data18,
            'data19' => $request->data19,
            'data20' => $request->data20,
            'data21' => $request->data21,
            'data22' => $request->data22,
            'data23' => $request->data23,
            'data24' => $request->data24,
            'data25' => $request->data25,
            'data26' => $request->data26,
            'data27' => $request->data27,
            'data28' => $request->data28,
            'data29' => $request->data29,
            'data30' => $request->data30,
            'data31' => $request->data31,
            'data32' => $request->data32,
            'data33' => $request->data33,
            'data34' => $request->data34,
            'data35' => $request->data35,
            'data36' => $request->data36,
            'data37' => $request->data37,
            'data38' => $request->data38,
            'data39' => $request->data39,
            'data40' => $request->data40,
            'data41' => $request->data41,
            'data42' => $request->data42,
            'data43' => $request->data43,
            'data44' => $request->data44,
            'data45' => $request->data45,
            'data46' => $request->data46,
            'data47' => $request->data47,
            'data48' => $request->data48,
            'data49' => $request->data49,
            'data50' => $request->data50,
            'data51' => $request->data51,
            'data52' => $request->data52,
            'data53' => $request->data53,
            'data54' => $request->data54,
            'data55' => $request->data55,
            'data56' => $request->data56,
            'data57' => $request->data57,
            'data58' => $request->data58,
            'data59' => $request->data59,
            'data60' => $request->data60,
            'data61' => $request->data61,
            'data62' => $request->data62,
            'data63' => $request->data63,
            'data64' => $request->data64,
            'data65' => $request->data65,
            'data66' => $request->data66,
            'data67' => $request->data67,
            'data68' => $request->data68,
            'data69' => $request->data69,
            'data70' => $request->data70,
            'data71' => $request->data71,
            'data72' => $request->data72,
            'data73' => $request->data73,
            'data74' => $request->data74,
            'data75' => $request->data75,
            'data76' => $request->data76,
            'data77' => $request->data77,
            'data78' => $request->data78,
            'data79' => $request->data79,
            'data80' => $request->data80
        );


        DataTypesJob::dispatch($uniq_id, json_encode($array_data_types));
      
        SystemLogs::saveLogs($user->id, 'Updated data types of uploaded leads with file id '.$uniq_id);
        $msg = 'Successfully updated data type of the upload leads.';

        return response()->json([
                'error'  => $error,
                'msg'    => $msg,
        ]);
    }

    public function undo_upload_file(Request $request){
    	$user = \Auth::user();
    	$file_id =  $request->file_id;
    	$error = "false";
    	$msg = "";
        $error_msg 	= [];
        
        // CrmLeads::selectRaw('crm_leads,crm_borrowers')
        // ->leftjoin('crm_borrowers', 'crm_borrowers.profile_id', '=', 'crm_leads.profile_id')
        // ->where('crm_leads.file_id','=' ,$file_id)->delete();

        $q = 'DELETE crm_leads, crm_borrowers FROM crm_leads LEFT JOIN crm_borrowers ON crm_borrowers.profile_id = crm_leads.profile_id where crm_leads.file_id = ?';        
		$status = \DB::delete($q, array($file_id));

        $post_sync = array(
            'status'     => 3,
            'updated_at' => Carbon::now(),
        );
                    
        FileUploadLogs::where('file_id', '=', $file_id)->update($post_sync);
      
        SystemLogs::saveLogs($user->id, 'Undo uploaded new leads with file id '.$file_id);
        $msg = 'Successfully cancelled the upload leads.';

    	return response()->json([
                'error'  => $error,
                'msg'    => $msg,
        ]);
    }

     public function getUploadedLeads(Request $request){

     ## Read value
     $user = Auth::user();

     $draw = $request->get('draw');
     $start = $request->get("start");
     $rowperpage = $request->get("length"); // Rows display per page

     $columnIndex_arr = $request->get('order');
     $columnName_arr = $request->get('columns');
     $order_arr = $request->get('order');
     $search_arr = $request->get('search');

     $columnIndex = $columnIndex_arr[0]['column']; // Column index
     $columnName = $columnName_arr[$columnIndex]['data']; // Column name
     $columnSortOrder = $order_arr[0]['dir']; // asc or desc
     $searchValue = $search_arr['value']; // Search value

     // Total records
     //$totalRecords = Groups::select('count(*) as allcount')->count();
     $totalRecordswithFilter = TempUploads::select('count(*) as allcount')
     ->where(function($query) use ($searchValue)  {
              //$query->where('name', 'like', '%' .$searchValue . '%');
        })
     ->where('header','=','0')
     ->where('file_id','=',$request->uniq_id)
     ->count();

     // Fetch records
     $records = TempUploads::orderBy('id','DESC')
        ->where(function($query) use ($searchValue)  {
              //$query->where('name', 'like', '%' .$searchValue . '%');
        })
        ->where('header','=','0')
     	->where('file_id','=',$request->uniq_id)
       ->skip($start)
       ->take($rowperpage)
       ->get();

     $data_arr = array();

     foreach($records as $record){
        
        $coll_code 			= $request->coll_code;
        $account_no 		= $request->account_no;
        $full_name 			= $request->full_name;
        $cycle_day          = $request->cycle_day;
        $address 			= $request->address;
        $due_date 			= $request->due_date;
        $outstanding_bal 	= $request->outstanding_bal;
        $loan_amount        = $request->loan_amount;

        $endo_date 			= ($request->endo_date != '') ? $record[$request->endo_date]:'--';
        $email              = ($request->email != '') ? $record[$request->email]:'--';
        $home_no 			= ($request->home_no != '') ? $record[$request->home_no]:'--';
        $business_no 		= ($request->business_no != '') ? $record[$request->business_no]:'--';
        $cellphone_no 		= ($request->cellphone_no != '') ? $record[$request->cellphone_no]:'--';
        $other_phone_no_1 	= ($request->other_phone_no_1 != '') ? $record[$request->other_phone_no_1]:'--';
        $other_phone_no_2 	= ($request->other_phone_no_2 != '') ? $record[$request->other_phone_no_2]:'--';
        $other_phone_no_3 	= ($request->other_phone_no_3 != '') ? $record[$request->other_phone_no_3]:'--';
        $other_phone_no_4 	= ($request->other_phone_no_4 != '') ? $record[$request->other_phone_no_4]:'--';
        $other_phone_no_5 	= ($request->other_phone_no_5 != '') ? $record[$request->other_phone_no_5]:'--';

        $data_arr[] = array(
          "coll_code" 				=> $record[$coll_code],
          "account_no" 				=> $record[$account_no],
          "full_name" 				=> $record[$full_name],
          "cycle_day"               => $record[$cycle_day],
          "address" 				=> $record[$address],
          "due_date" 				=> $record[$due_date],
          "outstanding_bal" 		=> $record[$outstanding_bal],
          "loan_amount"             => $record[$loan_amount],
          "endo_date" 				=> $endo_date,
          "email"                   => $email,
          "home_no" 				=> $home_no,
          "business_no" 			=> $business_no,
          "cellphone_no" 			=> $cellphone_no,
          "other_phone_no_1" 		=> $other_phone_no_1,
          "other_phone_no_2" 		=> $other_phone_no_2,
          "other_phone_no_3" 		=> $other_phone_no_3,
          "other_phone_no_4" 		=> $other_phone_no_4,
          "other_phone_no_5" 		=> $other_phone_no_5,
        );
     }

     $response = array(
        "draw" => intval($draw),
        "iTotalRecords" => $totalRecordswithFilter,
        "iTotalDisplayRecords" => $totalRecordswithFilter,
        "aaData" => $data_arr
     );

     echo json_encode($response);
     exit;
   }

   public function getUploadLogs(Request $request){

     ## Read value
     $user = Auth::user();

     $draw = $request->get('draw');
     $start = $request->get("start");
     $rowperpage = $request->get("length"); // Rows display per page

     $columnIndex_arr = $request->get('order');
     $columnName_arr = $request->get('columns');
     $order_arr = $request->get('order');
     $search_arr = $request->get('search');

     $columnIndex = $columnIndex_arr[0]['column']; // Column index
     $columnName = $columnName_arr[$columnIndex]['data']; // Column name
     $columnSortOrder = $order_arr[0]['dir']; // asc or desc
     $searchValue = $search_arr['value']; // Search value

     // Total records
     //$totalRecords = Groups::select('count(*) as allcount')->count();
     $totalRecordswithFilter = FileUploadLogs::select('count(*) as allcount')
     ->where(function($query) use ($searchValue)  {
              $query->where('file_id', 'like', '%' .$searchValue . '%');
        })
     ->where('upload_type', '=', 'new_leads')
     ->count();

     // Fetch records
     $records = FileUploadLogs::orderBy($columnName,$columnSortOrder)
        ->where(function($query) use ($searchValue)  {
              $query->where('file_id', 'like', '%' .$searchValue . '%');
        })
       ->where('upload_type', '=', 'new_leads')
       ->skip($start)
       ->take($rowperpage)
       ->get();

     $data_arr = array();

     foreach($records as $record){
        $id = $record->id;

        $file_id 			= $record->file_id;
        $upload_type        = $record->upload_type;
        $status 			= $record->status;
        $create_by 			= User::getName($record->user);
        $created_at 		= $record->created_at->diffForHumans();


        switch ($status) {
        	case '1':
        		$action_btn   = '<a href="'.route('pages.file_uploads.check_uploads', ['uniq_id' => $file_id]).'" class="btn btn-icon btn-outline-success btn-circle btn-sm mr-2">
								<i class="flaticon2-poll-symbol"></i>
							</a>';
        		$status_label = '<center><span class="label label-xl label-primary label-inline mr-2">Validation</span></center>';
        		break;

        	case '2':
                    $date_final = strtotime($record->created_at);
                    $date_findal = date('Y-m-d',$date_final);

                    $date = new DateTime($date_findal);
                    $now = new DateTime();

                    if($date < $now) {
                        $action_btn = '';
                       
                    }else{
                        $action_btn   = '<a href="#" id="'.$file_id.'" class="btn btn-icon btn-outline-success btn-circle btn-sm mr-2 btn_undo_upload" title="Undo">
                                <i class="fas fa-undo"></i>
                            </a>';
                    }
        		
        		$status_label = '<center><span class="label label-xl label-success label-inline mr-2">Success</span></center>';
        		break;

        	case '3':
        		$action_btn   = '';
        		$status_label = '<center><span class="label label-xl label-danger label-inline mr-2">Cancelled</span></center>';
        		break;

        	case '4':
        		$action_btn   = '';
        		$status_label = '<center><span class="label label-xl label-secondary label-inline mr-2">Synching</span></center>';
        		break;
        	
        	default:
        		$status_label = '<center><span class="label label-xl label-warning label-inline mr-2">In Progress</span></center>';
        		$action_btn   = '';
        		break;
        }

        $total_upload = TempUploads::select('count(*) as allcount')
                              ->where('file_id', '=', $file_id)
                              ->where('header', '=', 0)
                              ->count();

        $total_success = TempUploads::select('count(*) as allcount')
                              ->where('file_id', '=', $file_id)
                              ->where('error', '=', 0)
                              ->where('success', '=', 1)
                              ->where('header', '=', 0)
                              ->count();
        $total_error = TempUploads::select('count(*) as allcount')
                              ->where('file_id', '=', $file_id)
                              ->where('error', '=', 1)
                              ->where('header', '=', 0)
                              ->count();
       
        $data_arr[] = array(
          "file_id" 		=> $file_id,
          "status" 			=> $status_label,
          "upload_type"     => $upload_type,
          "user" 			=> $create_by,
          "total_upload" 	=> $total_upload,
          "total_success" 	=> '<a href="'.route('pages.file_uploads.export_new_leads_logs', ['file_id' => $file_id, 'type' => 'success', 'upload_type' => 'new_leads']).'" class="btn btn-success btn-sm mr-3"><i class="fas fa-file-download" title="Download Success"></i> '.$total_success.'</a>',
          "total_error" 	=> '<a href="'.route('pages.file_uploads.export_new_leads_logs', ['file_id' => $file_id, 'type' => 'error', 'upload_type' => 'new_leads']).'" class="btn btn-danger btn-sm mr-3"><i class="fas fa-file-download" title="Download Error"></i> '.$total_error.'</a>',
          "created_at" 		=> $created_at,
          "action" 			=> $action_btn
        );
     }

     $response = array(
        "draw" => intval($draw),
        "iTotalRecords" => $totalRecordswithFilter,
        "iTotalDisplayRecords" => $totalRecordswithFilter,
        "aaData" => $data_arr
     );

     echo json_encode($response);
     exit;
   }


}
