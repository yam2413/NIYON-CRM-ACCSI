<?php

namespace App\Http\Controllers;

use Auth;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Groups;
use App\Models\Statuses;
use App\Models\SystemLogs;
use App\Models\CrmLeads;
use App\Jobs\ImportStatusesJob;
use Illuminate\Http\Request;

class StatusesController extends Controller
{
    public function index(){
        $groups   = Groups::get();
    	return view('pages.statuses.index', compact('groups'));
    }

    public function edit($id){
        $statuses = Statuses::find($id);
        $groups   = Groups::get();
        return view('pages.statuses.edit', compact('statuses','groups'));
    }

    public function update(Request $request){
        $user = Auth::user();

        $email = Statuses::where('status_name', $request['statuses_name'])->where('id','!=' ,$request->id)->first();

        if($email){
            return response()->json([
                'error'  => 'true',
                'msg'    => 'Status Name is already exists.'
            ]);
        }

        $post_sync = array(
            'status_name'       => strtoupper($request->statuses_name),
            'group'             => $request->groups,
            'description'       => $request->description,
            'updated_at'        => Carbon::now(),
        );
        
        Statuses::where('id', '=', $request->id)->update($post_sync);
        SystemLogs::saveLogs($user->id, 'Status '.$request->statuses_name.' has been updated ');
        
        return response()->json([
            'error'  => 'false',
            'msg'    => 'Status updated successfully'
        ]);
    }

    public function store(Request $request){
        $user = Auth::user();

        $statuses = Statuses::where('status_name', $request['status_name'])->first();
        if($statuses){
            return response()->json([
                'error'  => 'true',
                'msg'    => 'Status Name is already exists in this group.'
            ]);
        }

        $uniq_id =  substr(md5(rand(999, 999999)), 0, 3).date('YmdHis');

        $insert_data = array(
            'value'             => 0,
            'status_name'       => strtoupper($request->statuses_name),
            'group'             => $request->groups,
            'description'       => $request->description,
            'added_by'          => $user->id,
            'created_at'        => Carbon::now(),
            'updated_at'        => Carbon::now(),
        );
        Statuses::insert($insert_data);


        SystemLogs::saveLogs($user->id, 'Added New Status'.$request->statuses_name);

         return response()->json([
                'error'  => 'false',
                'msg'    => 'Status successfully created'
         ]);
    }

    public function delete(Request $request){
        $agent = \Auth::user();
        $statuses  = Statuses::find($request->id);
        SystemLogs::saveLogs($agent->id, 'Status '.$statuses->status_name.' has been deleted ');

        $check_statuses = CrmLeads::select('count(*) as allcount')
                        ->where('status','=' ,$statuses->status_name)
                        ->count();
        if($check_statuses == 0){
            $statuses->delete();
        }else{
            return response()->json([
                'error'  => 'true',
                'msg'    => 'Status '.$statuses->status_name.' cannot be delete, because there is existing account was tag on this status.'
            ]);
        }

        return response()->json([
            'error'  => 'false',
            'msg'    => 'Status '.$statuses->status_name.' Successfully deleted.'
        ]);
    }

    public function import_statuses(Request $request){
        $user = \Auth::user();
        $uniq_id =  substr(md5(rand(999, 999999)), 0, 3).date('YmdHis');
        $error = "false";
        $msg = "";
        $error_msg  = [];
        $extension  = $request->file->extension();
        $path       = $request->file->storeAs('public/file_upload', $uniq_id.'.'.$extension);

        ImportStatusesJob::dispatch($uniq_id, $request->group_id, $user->id, $path);
        SystemLogs::saveLogs($user->id, 'Import Statuses');

        return response()->json([
           'error'  => 'false',
           'msg'    => 'Successfully imported new statuses from '.Groups::usersGroup($request->group_id).' .'
        ]);
    }

    public function getStatuses(Request $request){

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
        $totalRecordswithFilter = Statuses::select('count(*) as allcount');
        $totalRecordswithFilter->leftjoin('groups', 'groups.id', '=', 'statuses.group');
        $totalRecordswithFilter->where(function($query) use ($searchValue)  {
            $query->where('statuses.status_name', 'like', '%' .$searchValue . '%')
            ->orWhere('groups.name', 'like', '%' .$searchValue . '%');
        });
        $totalRecordswithFilter->where('statuses.status_name', '!=', 'NEW');
        $totalRecordswithFilter->where('statuses.status_name', '!=', 'BEST TIME TO CALL');
        $totalRecordswithFilter = $totalRecordswithFilter->count();
   
        // Fetch records
        $records = Statuses::select(
            [
                    'statuses.id',
                    'statuses.value',
                    'statuses.status_name',
                    'statuses.group',
                    'statuses.description',
                    'statuses.added_by',
                    'statuses.created_at',
                    'groups.name',
                ]
            );
        $records->orderBy('statuses.created_at',$columnSortOrder);
        $records->leftjoin('groups', 'groups.id', '=', 'statuses.group');
        $records->where(function($query) use ($searchValue)  {
                 $query->where('statuses.status_name', 'like', '%' .$searchValue . '%')
                         ->orWhere('groups.name', 'like', '%' .$searchValue . '%');
        });
        $records->where('statuses.status_name', '!=', 'NEW');
        $records->where('statuses.status_name', '!=', 'BEST TIME TO CALL');
        $records->skip($start);
        $records->take($rowperpage);
        $records = $records->get();
   
   
        $data_arr = array();
   
        foreach($records as $record){
           $id = $record->id;
   
           $status_name 		= ucwords($record->status_name);
           $description 		= $record->description;
           $added_by            = User::getName($record->added_by);
           $group 				= $record->name;
           $created_at 		    = $record->created_at->diffForHumans();
   
            $delete_btn = '<a href="#" id="'.$id.'" alt="'.$status_name.'" class="btn btn-danger font-weight-bold mr-2 delete_statuses" title="Delete this statuses"><i class="flaticon-delete"></i></a>';
            $edit_modal = '<div class="modal fade" id="edit_'.$id.'_statuses_modal" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="edit_statuses_modal" aria-hidden="true">
               <div class="modal-dialog" role="document" id="modaly_body_'.$id.'">
                   
               </div>
           </div>';
            $action_btn = '<div class="dropdown">
                               <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                               <i class="flaticon-more-1"></i>
                               </button>
                               <div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                                   <a id="'.$id.'" class="dropdown-item edit_statuses" href="#"  title="Edit Status Details" data-toggle="modal" data-target="#edit_'.$id.'_statuses_modal">Edit Status Details</a>
                                   <a href="#" id="'.$id.'" alt="'.$status_name.'" class="dropdown-item delete_statuses" href="#">Delete Status</a>
                               </div>
                           </div>'.$edit_modal;
   
           $data_arr[] = array(
             "status_name" 		=> $status_name,
             "description" 		=> $description,
             "group" 			=> $group,
             "added_by" 		=> $added_by,
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
