<?php

namespace App\Http\Controllers;

use Auth;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Groups;
use App\Models\SystemLogs;
use App\Models\CrmLeads;
use App\Models\FileHeaders;
use App\Models\Statuses;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\HeadingRowImport;
use Illuminate\Http\Request;

class GroupsController extends Controller
{
    public function index(){
    	return view('pages.groups.index');
    }

    public function create(){
    	return view('pages.groups.create');
    }

    public function edit($id){
      $groups = Groups::find($id);
      $users = User::where('group', $id)->get();
      return view('pages.groups.edit', compact('groups','users'));
    }

    public function create_file_header($id){
        $groups = Groups::find($id);
        $users = User::where('group', $id)->get();
        $file_headers = FileHeaders::where('group', $id)->get();
        if($groups->file_header == 1){
            return view('pages.groups.validate', compact('file_headers', 'groups','users','id'));
        }
        return view('pages.groups.create_file_header', compact('file_headers', 'groups','users','id'));
    }

    public function validate_file($id){
        $groups = Groups::find($id);
        $users = User::where('group', $id)->get();
        $file_headers = FileHeaders::where('group', $id)->get();
        return view('pages.groups.validate', compact('file_headers', 'groups','users','id'));
    }

    public function update(Request $request){
      $user = \Auth::user();
        $post_sync = array(
            'name'          => $request->name,
            'description'   => $request->description,
            'color_palette'  => $request->color_palette,
            'updated_at'    => Carbon::now(),
        );
        
        Groups::where('id', '=', $request->id)->update($post_sync);

        $statuses_val = array(
            'NEW' => 'Status for the new uploaded account',
            'BEST TIME TO CALL' => 'This status is request for the client to call for specific date & time'
        );


        foreach ($statuses_val as $key => $value) {
            $totalRecords = Statuses::select('count(*) as allcount')
                            ->where('status_name', '=', $key)
                            ->where('group', '=', $request->id)
                            ->count();
            if($totalRecords == 0){
                $insert_data = array(
                    'value'             => 0,
                    'status_name'       => $key,
                    'group'             => $request->id,
                    'description'       => $value,
                    'added_by'          => $user->id,
                    'created_at'        => Carbon::now(),
                    'updated_at'        => Carbon::now(),
                );
                Statuses::insert($insert_data);
            }
            
        }

        if(isset($request->users)){
          foreach ($request->users as $key => $value) {
            $post_sync = array(
                'group'         => $request->id,
                'updated_at'    => Carbon::now(),
            );
            
            User::where('id', '=', $value)->update($post_sync);

            // $users = User::where('id',  $value)->first();

            // SystemLogs::saveLogs($user->id, $users->name.' added to '.$request->name.' group. ');
          }
        }

        SystemLogs::saveLogs($user->id, $request->name.' group has been updated ');
        return response()->json([
                'error'  => 'false',
                'msg'    => 'Group updated successfully'
            ]);
    }

    public function delete(Request $request){
        $agent = \Auth::user();
        $group  = Groups::find($request->id);

        $totalRecords = CrmLeads::select('count(*) as allcount')->where('assign_group', $request->id)->count();
        if($totalRecords > 0){
            return response()->json([
                'error'  => 'true',
                'msg'    => 'Sorry, '.$group->name.' group cannot be delete, because this group has already leads account.'
            ]);
        }
        SystemLogs::saveLogs($agent->id, $group->name.' group has been deleted ');
        $group->delete();

        $post_sync = array(
            'group'         => 0,
            'updated_at'    => Carbon::now(),
        );
            
        User::where('group', '=', $request->id)->update($post_sync);


        return response()->json([
            'error'  => 'false',
            'msg'    => 'Group '.$group->name.' Successfully deleted.'
        ]);
    }

    public function delete_file_header(Request $request){
        $agent = \Auth::user();
        $totalRecords = CrmLeads::select('count(*) as allcount')->where('assign_group', $request->id)->count();
        if($totalRecords > 0){
            return response()->json([
                'error'  => 'true',
                'msg'    => 'This file header cannot be deleted due to current assigned leads on this group'
            ]);

        }
        $deleted = FileHeaders::where('group', $request->id)->delete();
        $post_sync = array(
            'file_header'   => 0,
            'updated_at'    => Carbon::now(),
        );
        
        Groups::where('id', '=', $request->id)->update($post_sync);

        return response()->json([
            'error'  => 'false',
            'msg'    => 'File header Successfully deleted.'
        ]);
    }


    public function store(Request $request){
    	$user = \Auth::user();
    	$groups_name = Groups::where('name', $request['name'])->first();
    	if($groups_name){
            return response()->json([
                'error'  => 'true',
                'msg'    => 'Group name is already used.'
            ]);
        }

        $groups = new Groups;
        $groups->name           = $request->name;
    	$groups->description    = $request->description;
        $groups->create_by      = $user->id;
        $groups->create_by      = $user->id;
        $groups->color_palette   = $request->color_palette;
        $groups->save();

        $statuses_val = array(
            'NEW' => 'Status for the new uploaded account',
            'BEST TIME TO CALL' => 'This status is request for the client to call for specific date & time'
        );

        foreach ($statuses_val as $key => $value) {
            $insert_data = array(
                'value'             => 0,
                'status_name'       => $key,
                'group'             => $groups->id,
                'description'       => $value,
                'added_by'          => $user->id,
                'created_at'        => Carbon::now(),
                'updated_at'        => Carbon::now(),
            );
            Statuses::insert($insert_data);
        }
        

        if(isset($request->users)){
        	foreach ($request->users as $key => $value) {
	        	$post_sync = array(
		            'group'       	=> $groups->id,
		            'updated_at'    => Carbon::now(),
		        );
		        
		        User::where('id', '=', $value)->update($post_sync);
	        }
        }
        

      SystemLogs::saveLogs($user->id, $request->name.' group has been created ');

    	 return response()->json([
                'error'  => 'false',
                'msg'    => 'Group successfully created'
         ]);
    }

    public function upload_file(Request $request){
    	$user = \Auth::user();
    	$uniq_id =  substr(md5(rand(999, 999999)), 0, 3).date('YmdHis');
    	$error = "false";
    	$msg = "";
        $error_msg 	= [];
        $extension 	= $request->file->extension();
        $path 		= $request->file->storeAs('public/file_upload', $uniq_id.'.'.$extension);

        $headings = (new HeadingRowImport)->toArray($request->file('file'));
        $deleted = FileHeaders::where('group', $request->group_id)->delete();
        $array_data = [];
        
        $encode_header = json_encode($headings[0]);
        $exploded_head = explode(',', $encode_header);
        foreach ($exploded_head as $key => $value) {
            $array_data[] = $value;
            $field_name_filter = str_replace(array('[',']','"',"'"),'',$value);
            if(ctype_digit($field_name_filter)){
                try {
                    $special_value  = $field_name_filter;
                    $field_name = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($field_name_filter)->format('m/d/y');
                } catch (\ErrorException $e) {
                     return response()->json([
                            'error'  => 'true',
                            'msg'    => $field_name_filter.' Field Name has incorrect value',
                            'uniq_id' => $uniq_id
                    ]);
                }
                
            }else{
                $field_name     = $field_name_filter;
                $special_value  = '';
            }
            $insert_data = array(
                'user'                    => $user->id,
                'group'       		      => $request->group_id,
                'field_name'      	      => $field_name,
                'special_value'           => $special_value,
                'created_at'              => Carbon::now(),
                'updated_at'              => Carbon::now(),
            );
            FileHeaders::insert($insert_data);
        }

        $post_sync = array(
            'file_header'   => 1,
            'updated_at'    => Carbon::now(),
        );
        
        Groups::where('id', '=', $request->group_id)->update($post_sync);
      
        SystemLogs::saveLogs($user->id, 'Upload new leads with file id '.$uniq_id);
        $msg = 'Successfully upload the file header';

    	  return response()->json([
                'error'  => $error,
                'msg'    => $msg,
                'uniq_id' => $uniq_id
        ]);
    }

    public function store_file_header(Request $request){
    	$user = \Auth::user();
        $array = [];

        $data1 = json_encode($request->group_a);
        $data = json_decode($data1);
        foreach ($data as $key => $value) {
            // return response()->json([
            //         'error'  => 'true',
            //         'msg'    => $value[0]['header_name']
            //  ]);
            if($value->header_name == null){
                return response()->json([
                    'error'  => 'true',
                    'msg'    => 'There is a empty header name'
             ]);
                
            }
            if($value->data_type == null){
                return response()->json([
                    'error'  => 'true',
                    'msg'    => 'There is a empty data type'
             ]);
                 
            }

            
            $array[] = $value->assign_field;

        }
        $array_fields = ['account_no'];
        if(!in_array('account_no', $array)){
                return response()->json([
                    'error'  => 'true',
                    'msg'    => 'Assign Field Account No column is missing',
                ]);
        }else if(count(array_intersect($array,['account_no'])) != 1){
            return response()->json([
                'error'  => 'true',
                'msg'    => 'You can only select one assign Account No',
            ]);
            
        }

        if(!in_array('ch_name', $array)){
            return response()->json([
                'error'  => 'true',
                'msg'    => 'Assign Field CH Name column is missing',
            ]);
        }else if(count(array_intersect($array,['ch_name'])) != 1){
            return response()->json([
                'error'  => 'true',
                'msg'    => 'You can only select one assign CH Name',
            ]);
            
        }

        // if(!in_array('ch_birthday', $array)){
        //     return response()->json([
        //         'error'  => 'true',
        //         'msg'    => 'Assign Field CH Birthday column is missing',
        //     ]);
        // }else if(count(array_intersect($array,['ch_birthday'])) != 1){
        //     return response()->json([
        //         'error'  => 'true',
        //         'msg'    => 'You can only select one assign CH Birthday',
        //     ]);
            
        // }

        // if(!in_array('ch_address', $array)){
        //     return response()->json([
        //         'error'  => 'true',
        //         'msg'    => 'Assign Field CH Address column is missing',
        //     ]);
        // }else if(count(array_intersect($array,['ch_address'])) != 1){
        //     return response()->json([
        //         'error'  => 'true',
        //         'msg'    => 'You can only select one assign CH Address',
        //     ]);
            
        // }

        // if(!in_array('loan_amount', $array)){
        //     return response()->json([
        //         'error'  => 'true',
        //         'msg'    => 'Assign Field Loan Amount is missing',
        //     ]);
        // }else if(count(array_intersect($array,['loan_amount'])) != 1){
        //     return response()->json([
        //         'error'  => 'true',
        //         'msg'    => 'You can only select one assign Loan Amount',
        //     ]);
            
        // }

        if(!in_array('outstanding_amount', $array)){
            return response()->json([
                'error'  => 'true',
                'msg'    => 'Assign Field Outstanding Amount column is missing',
            ]);
        }else if(count(array_intersect($array,['outstanding_amount'])) != 1){
            return response()->json([
                'error'  => 'true',
                'msg'    => 'You can only select one assign Outstanding Amount',
            ]);
            
        }

        $array_contact_no = ['home_no','mobile_no'];

        if(count(array_intersect($array,$array_contact_no)) == 0){
            return response()->json([
                'error'  => 'true',
                'msg'    => 'Aleast one contact no. is required',
            ]);
        }
        
        //$deleted = FileHeaders::where('group', $request->group_id)->delete();
        
            $x = 3;
            foreach ($data as $key => $value) {

                $header = str_replace(array(',','"',"'"),'',$value->header_name);

                $update_header_sync = array(
                    'group'       		      => $request->group_id,
                    'field_name'      	      => $header,
                    'data_type'               => $value->data_type,
                    'assign_field'            => $value->assign_field,
                    'order_no'                => 'data'.$x,
                    'updated_at'              => Carbon::now(),
                );
                //FileHeaders::insert($insert_data);
                FileHeaders::where('id', '=', $value->head_id)->update($update_header_sync);
                $x++;
            }

            $post_sync = array(
                'file_header'   => 1,
                'updated_at'    => Carbon::now(),
            );
            
            Groups::where('id', '=', $request->group_id)->update($post_sync);
        
    	

    	 return response()->json([
                'error'  => 'false',
                'msg'    => 'Successfully Created Group File Upload Header'
         ]);
    }

    public function getUsers(Request $request){

       $user = \Auth::user();
       $q = $request->get('q');
       $records = User::selectRaw('id,name');
       $records->orderBy('name','DESC');
       $records->where(function($query) use ($q)  {
              $query->where('name', 'like', '%' .$q . '%');
       });
       $records->where('email','!=','root');
       $records->where('group','=' ,0);
       $records->skip(0);
       $records->take(10);
       $records = $records->get();

       $data = [];
       foreach ($records as $key => $record) {

          $data[] = array(
                'id'       => $record['id'],
                'text'     => $record['name'],
            );
       }
        
       return response()->json([
            'error'  => 'false',
            'items'    => $data
       ]);

    }


    public function getGroups(Request $request){

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
     $totalRecordswithFilter = Groups::select('count(*) as allcount')
     ->where(function($query) use ($searchValue)  {
              $query->where('name', 'like', '%' .$searchValue . '%');
        })
     ->count();

     // Fetch records
     $records = Groups::orderBy($columnName,$columnSortOrder)
        ->where(function($query) use ($searchValue)  {
              $query->where('name', 'like', '%' .$searchValue . '%');
        })
       ->skip($start)
       ->take($rowperpage)
       ->get();

     $data_arr = array();

     foreach($records as $record){
        $id = $record->id;

        $name 				= ucwords($record->name);
        $description 		= $record->description;
        $create_by 			= User::getName($record->create_by);
        $created_at 		= $record->created_at->diffForHumans();

         $delete_btn = '<a href="#" id="'.$id.'" alt="'.$name.'" class="btn btn-danger font-weight-bold mr-2 delete_groups" title="Delete this group"><i class="flaticon-delete"></i></a>';

         $edit_modal = '<div class="modal fade" id="edit_'.$id.'_groups_modal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="edit_dept_modal" aria-hidden="true">
		    <div class="modal-dialog" role="document" id="modaly_body_'.$id.'">
		        
		    </div>
		</div>';
         $action_btn = '<div class="dropdown">
						    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						    <i class="flaticon-more-1"></i>
						    </button>
						    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
						        <a id="'.$id.'" class="dropdown-item edit_groups" href="#"  title="Edit Group Details" data-toggle="modal" data-target="#edit_'.$id.'_groups_modal">Edit group details</a>
						        <a href="'.route('pages.groups.create_file_header', ['id' => $id]).'" class="dropdown-item" href="#">File Header Group</a>
                                <a href="#" id="'.$id.'" alt="'.$name.'" class="dropdown-item delete_groups" href="#">Delete Group</a>
						    </div>
						</div>'.$edit_modal;

        $data_arr[] = array(
          "name" 			=> $name,
          "description" 	=> $description,
          "create_by" 		=> $create_by,
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
