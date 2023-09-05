<?php

namespace App\Http\Controllers;

use Auth;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Groups;
use App\Models\SystemLogs;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public function index(){
         $groups   = Groups::get();
    	return view('pages.users.index', compact('groups'));
    }

    public function my_team(){
        return view('pages.my_team.index');
    }

    public function create(){
        $groups   = Groups::get();
    	return view('pages.users.create', compact('groups'));
    }

    public function edit($id){
    	$users = User::find($id);
        $groups   = Groups::get();
    	return view('pages.users.edit', compact('users','groups'));
    }

    public function edit_pass($id){
    	$users = User::find($id);
    	return view('pages.users.password', compact('users'));
    }

    public function update(Request $request){
        $user = Auth::user();

        $email = User::where('email', $request['email'])->where('id','!=' ,$request->id)->first();

        if($email){
            return response()->json([
                'error'  => 'true',
                'msg'    => 'Email is already exists.'
            ]);
        }

        $extension = User::where('email', $request['extension'])->where('id','!=' ,$request->id)->first();
        if($extension){
            return response()->json([
                'error'  => 'true',
                'msg'    => 'Extension is already exists.'
            ]);
        }

        $collcode = User::where('coll', $request['coll'])->where('id','!=' ,$request->id)->first();
        if($collcode){
            return response()->json([
                'error'  => 'true',
                'msg'    => 'Coll Code is already exists.'
            ]);
        }

        $name = ucfirst($request['firstname']).' '.ucfirst($request['lastname']);
        $post_sync = array(
            'name'       		=> $name,
            'coll'              => $request->coll,
            'lastname'       	=> ucfirst($request->lastname),
            'firstname'       	=> ucfirst($request->firstname),
            'email'      		=> $request->email,
            'group'             => ($user->level == 0) ? $request->groups:$user->group,
            'level'        		=> $request->level,
            'extension'         => $request->extension,
            'updated_at'        => Carbon::now(),
        );
        
        User::where('id', '=', $request->id)->update($post_sync);
        SystemLogs::saveLogs($user->id, $name.' account has been updated ');
        
        return response()->json([
                'error'  => 'false',
                'msg'    => 'User updated successfully'
            ]);
    }

    public function update_pass(Request $request){
        $user = Auth::user();

        $post_sync = array(
            'password'       	=> bcrypt($request->password),
            'updated_at'        => Carbon::now(),
        );
        
        User::where('id', '=', $request->id)->update($post_sync);

        $users = User::where('id',  $request->id)->first();
        SystemLogs::saveLogs($user->id, $users->name.' password changed ');
        
        return response()->json([
                'error'  => 'false',
                'msg'    => 'User updated password successfully'
            ]);
    }

    public function store(Request $request){
        $user = Auth::user();
    	$uniq_id =  substr(md5(rand(999, 999999)), 0, 3).date('YmdHis');
    	$email = User::where('email', $request['email'])->first();
    	if($email){
            return response()->json([
                'error'  => 'true',
                'msg'    => 'Email is already exists.'
            ]);
        }

        $extension = User::where('extension', $request['extension'])->first();

        if($extension){
            return response()->json([
                'error'  => 'true',
                'msg'    => 'Extension is already exists.'
            ]);
        }

        $coll_code = $request->coll;
        $name = ucfirst($request['firstname']).' '.ucfirst($request['lastname']);

        $insert_data = array(
            'name'       		=> $name,
            'lastname'       	=> ucfirst($request->lastname),
            'firstname'       	=> ucfirst($request->firstname),
            'email'      		=> $request->email,
            'password'          => bcrypt($request->password),
            'level'        		=> $request->level,
            'extension'         => $request->extension,
            'coll'             	=> $coll_code,
            'group'             => ($user->level == 0) ? $request->groups:$user->group,
            'created_at'        => Carbon::now(),
            'updated_at'        => Carbon::now(),
        );
        User::insert($insert_data);


        SystemLogs::saveLogs($user->id, $name.' user account has been created ');

    	 return response()->json([
                'error'  => 'false',
                'msg'    => 'User successfully created'
         ]);
    }

    public function delete(Request $request){
        $agent = \Auth::user();
        $user  = User::find($request->id);
        SystemLogs::saveLogs($agent->id, $user->nme.' user account has been deleted ');
        $user->delete();
        return response()->json([
                'error'  => 'false',
                'msg'    => 'User '.$user->name.' Successfully deleted.'
            ]);
    }

    public function getUsers(Request $request){

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
     $totalRecordswithFilter = User::select('count(*) as allcount');
     $totalRecordswithFilter->where(function($query) use ($searchValue)  {
              $query->where('users.name', 'like', '%' .$searchValue . '%')
                      ->orWhere('users.email', 'like', '%' .$searchValue . '%');
        });
     $totalRecordswithFilter->where('users.email','!=','root');
     if($user->level == 2){
        $totalRecordswithFilter->where('group','=' ,$user->group);
     }
     $totalRecordswithFilter->where('users.id','!=',$user->id);
     $totalRecordswithFilter = $totalRecordswithFilter->count();

     // Fetch records
     $records = User::orderBy($columnName,$columnSortOrder);
     $records->where(function($query) use ($searchValue)  {
              $query->where('users.name', 'like', '%' .$searchValue . '%')
                      ->orWhere('users.email', 'like', '%' .$searchValue . '%');
     });
     $records->where('users.email','!=','root');
     $records->where('users.id','!=',$user->id);

     if($user->level == 2){
        $records->where('group','=' ,$user->group);
     }
     
     $records->skip($start);
     $records->take($rowperpage);
     $records = $records->get();


     $data_arr = array();

     foreach($records as $record){
        $id = $record->id;

        $name 				= ucwords($record->name);
        $email 				= $record->email;
        $coll 				= $record->coll;
        $extension 			= $record->extension;
        $level 				= User::usersRole($record->level);
        $group 				= Groups::usersGroup($record->group);
        $created_at 		= $record->created_at->diffForHumans();

         $delete_btn = '<a href="#" id="'.$id.'" alt="'.$name.'" class="btn btn-danger font-weight-bold mr-2 delete_users" title="Delete this users"><i class="flaticon-delete"></i></a>';
         $edit_modal = '<div class="modal fade" id="edit_'.$id.'_users_modal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="edit_dept_modal" aria-hidden="true">
		    <div class="modal-dialog" role="document" id="modaly_body_'.$id.'">
		        
		    </div>
		</div>';
		$pass_modal = '<div class="modal fade" id="edit_'.$id.'_pass_modal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="edit_pass_modal" aria-hidden="true">
		    <div class="modal-dialog" role="document" id="modaly_body_pass_'.$id.'">
		        
		    </div>
		</div>';
         $action_btn = '<div class="dropdown">
						    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
						    <i class="flaticon-more-1"></i>
						    </button>
						    <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
						        <a id="'.$id.'" class="dropdown-item edit_users" href="#"  title="Edit User Details" data-toggle="modal" data-target="#edit_'.$id.'_users_modal">Edit user details</a>
						        <a id="'.$id.'" class="dropdown-item edit_pass" href="#"  title="Edit Password Details" data-toggle="modal" data-target="#edit_'.$id.'_pass_modal">Change Password</a>
						        '.(($user->level == 0) ? '<a href="#" id="'.$id.'" alt="'.$name.'" class="dropdown-item delete_users" href="#">Delete user</a>':'').'
						    </div>
						</div>'.$edit_modal.$pass_modal;

        $data_arr[] = array(
          "coll" 			=> $coll,
          "extension" 		=> $extension,
          "name" 			=> $name,
          "email" 			=> $email,
          "level"        	=> $level,
          "group"        	=> $group,
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
