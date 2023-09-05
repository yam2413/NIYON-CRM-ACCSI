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
use App\Jobs\PaidUploadJob;
use App\Jobs\PaidDataTypesJob;
use App\Jobs\SyncPaidsJob;
use Illuminate\Http\Request;

class PaidController extends Controller
{
    public function index(){
    	return view('pages.file_uploads.paids.index');
    }

    public function view_uploaded($uniq_id){
    	return view('pages.file_uploads.paids.view_uploaded', compact('uniq_id'));
    }

    public function sync_paids_leads(Request $request){
    	$user = \Auth::user();
    	$error = "false";
    	$msg = "";
        $error_msg 	= [];

        $file_id          = $request->file_id;
        $groups           = $request->groups;
        $account_no       = $request->account_no;
        $paid_amount      = $request->paid_amount;
        $paid_date        = $request->paid_date;

        $post_sync_status = array(
          'status'         => 4,
          'updated_at'     => Carbon::now(),
        );
                    
        FileUploadLogs::where('file_id', '=', $file_id)->update($post_sync_status);
        $job_id = SyncPaidsJob::dispatch($file_id, $groups, $account_no, $paid_amount, $paid_date);
      

         

        $msg = 'The upload file is now syncing in the lead list.';

    	return response()->json([
            'error'   => $error,
            'msg'     => $msg,
            'uniq_id' => $file_id
        ]);
    }

    public function check_uploads($uniq_id){
    	$user = \Auth::user();

        $file_logs = FileUploadLogs::where('file_id','=' ,$uniq_id)->first();

    	$temp_uploads = TempUploads::where('user', $user->id)
    						->where('upload_type','=' ,'paids')
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
    	return view('pages.file_uploads.paids.check_uploads', compact('uniq_id','select_datas','groups','temp_uploads','file_logs'));
    }

    public function data_type_upload_file(Request $request){
        $user = \Auth::user();
        $uniq_id =  $request->file_id;
        $error = "false";
        $msg = "";
        $error_msg  = [];



        PaidDataTypesJob::dispatch($uniq_id, 
            $request->data1,
            $request->data2,
            $request->data3,
            $request->data4,
            $request->data5,
            $request->data6,
            $request->data7,
            $request->data8,
            $request->data9,
            $request->data10,
            $request->data11,
            $request->data12,
            $request->data13,
            $request->data14,
            $request->data15,
            $request->data16,
            $request->data17,
            $request->data18,
            $request->data19,
            $request->data20,
            $request->data21,
            $request->data22,
            $request->data23,
            $request->data24,
            $request->data25,
            $request->data26,
            $request->data27,
            $request->data28,
            $request->data29,
            $request->data30,
            $request->data31,
            $request->data32,
            $request->data33,
            $request->data34,
            $request->data35,
            $request->data36,
            $request->data37,
            $request->data38,
            $request->data39,
            $request->data40,
            $request->data41,
            $request->data42,
            $request->data43,
            $request->data44,
            $request->data45,
            $request->data46,
            $request->data47,
            $request->data48,
            $request->data49,
            $request->data50,
            $request->data51,
            $request->data52,
            $request->data53,
            $request->data54,
            $request->data55,
            $request->data56,
            $request->data57,
            $request->data58,
            $request->data59,
            $request->data60,
            $request->data61,
            $request->data62,
            $request->data63,
            $request->data64,
            $request->data65,
            $request->data66,
            $request->data67,
            $request->data68,
            $request->data69,
            $request->data70,
            $request->data71,
            $request->data72,
            $request->data73,
            $request->data74,
            $request->data75,
            $request->data76,
            $request->data77,
            $request->data78,
            $request->data79,
            $request->data80
        );
      
        SystemLogs::saveLogs($user->id, 'Updated data types of uploaded paid accounts with file id '.$uniq_id);
        $msg = 'Successfully updated data type of the upload paid accounts.';

        return response()->json([
                'error'  => $error,
                'msg'    => $msg,
        ]);
    }

    public function upload_paid_file(Request $request){
    	$user = \Auth::user();
    	$uniq_id =  substr(md5(rand(999, 999999)), 0, 3).date('YmdHis');
    	$error = "false";
    	$msg = "";
        $error_msg 	= [];
        $extension 	= $request->file->extension();
        $path 		= $request->file->storeAs('public/file_upload', $uniq_id.'.'.$extension);

        $insert_data = array(
            'upload_type'     => 'paids',
            'file_id'         => $uniq_id,
            'path'      	  => $path,
            'user'      	  => $user->id,
            'created_at'      => Carbon::now(),
            'updated_at'      => Carbon::now(),
        );
        FileUploadLogs::insert($insert_data);

        PaidUploadJob::dispatch($uniq_id, $extension, $user->id);
      
        SystemLogs::saveLogs($user->id, 'Upload paid accounts with file id '.$uniq_id);
        $msg = 'Successfully upload the file in queuing list, Please go to upload logs to check the status of your file upload.';

    	  return response()->json([
                'error'  => $error,
                'msg'    => $msg,
                'uniq_id' => $uniq_id
        ]);
    }

    public function getUploadedPaidLeads(Request $request){

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
     ->where('upload_type', '=', 'paids')
     ->count();

     // Fetch records
     $records = TempUploads::orderBy('id','DESC')
        ->where(function($query) use ($searchValue)  {
              //$query->where('name', 'like', '%' .$searchValue . '%');
        })
        ->where('header','=','0')
     	->where('file_id','=',$request->uniq_id)
     	->where('upload_type', '=', 'paids')
       ->skip($start)
       ->take($rowperpage)
       ->get();

     $data_arr = array();

     foreach($records as $record){
        
        $account_no 		= $request->account_no;
        $paid_amount 		= $request->paid_amount;
        $paid_date          = $request->paid_date;

        $data_arr[] = array(
          "account_no" 				=> $record[$account_no],
          "paid_amount" 			=> $record[$paid_amount],
          "paid_date"               => $record[$paid_date],
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

    public function getPaidLogs(Request $request){

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
     ->where('upload_type', '=', 'paids')
     ->count();

     // Fetch records
     $records = FileUploadLogs::orderBy($columnName,$columnSortOrder)
        ->where(function($query) use ($searchValue)  {
              $query->where('file_id', 'like', '%' .$searchValue . '%');
        })
       ->where('upload_type', '=', 'paids')
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
        		$action_btn   = '<a href="'.route('pages.file_uploads.paids.check_uploads', ['uniq_id' => $file_id]).'" class="btn btn-icon btn-outline-success btn-circle btn-sm mr-2">
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
          "total_success" 	=> '<a href="'.route('pages.file_uploads.export_new_leads_logs', ['file_id' => $file_id, 'type' => 'success', 'upload_type' => 'paids']).'" class="btn btn-success btn-sm mr-3"><i class="fas fa-file-download" title="Download Success"></i> '.$total_success.'</a>',
          "total_error" 	=> '<a href="'.route('pages.file_uploads.export_new_leads_logs', ['file_id' => $file_id, 'type' => 'error', 'upload_type' => 'paids']).'" class="btn btn-danger btn-sm mr-3"><i class="fas fa-file-download" title="Download Error"></i> '.$total_error.'</a>',
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
