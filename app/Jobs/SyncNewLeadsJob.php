<?php

namespace App\Jobs;

use Auth;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Groups;
use App\Models\TempUploads;
use App\Models\FileUploadLogs;
use App\Models\CrmBorrowers;
use App\Models\CrmLeads;
use App\Models\CrmLogs;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SyncNewLeadsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $file_id, $groups, $import_array;
    public function __construct($file_id, $groups, $import_array)
    {
        $this->file_id          = $file_id;
        $this->groups           = $groups;
        $this->import_array     = $import_array;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    protected function formatContact($mobile){ 

       if($mobile != '--'){
            if(substr($mobile,0,2) == '63'){
                return '0'.substr($mobile,2,strlen($mobile));
            }else if( strlen($mobile) > 10 && substr($mobile,0,1) == '0' ){
                return '0'.substr($mobile,1,strlen($mobile));
            }else{
                return '0'.$mobile;
            }
        }else{
            return $mobile;
        }
    }

    public function handle()
    {
        $file_id          = $this->file_id;
        $groups           = $this->groups;

        $decode           = json_decode($this->import_array, true);
        $coll_code        = $decode['coll_code'];
        $account_no       = $decode['account_no'];
        $full_name        = $decode['full_name'];
        $cycle_day        = $decode['cycle_day'];
        $address          = $decode['address'];
        $email            = $decode['email'];
        $due_date         = $decode['due_date'];
        $outstanding_bal  = $decode['outstanding_bal'];
        $loan_amount      = $decode['loan_amount'];
        $endo_date        = $decode['endo_date'];
        $home_no          = $decode['home_no'];
        $business_no      = $decode['business_no'];
        $cellphone_no     = $decode['cellphone_no'];
        $other_phone_no_1 = $decode['other_phone_no_1'];
        $other_phone_no_2 = $decode['other_phone_no_2'];
        $other_phone_no_3 = $decode['other_phone_no_3'];
        $other_phone_no_4 = $decode['other_phone_no_4'];
        $other_phone_no_5 = $decode['other_phone_no_5'];

       
        $records = TempUploads::orderBy('id','DESC')
        ->where('header','=','0')
        ->where('file_id','=',$file_id)
        ->get();

        foreach ($records as $key => $record) {

            $uniq_id =  'LEADS-'.substr(md5(rand(999, 999999)), 0, 3).date('YmdHis');

            $check_coll_codes   = User::select('count(*) as allcount')
                              ->where('coll', '=', $record[$coll_code])
                              ->count();
            //Check if the coll codes exist or not
            if($check_coll_codes == 0){
                $post_sync = array(
                    'error'      => 1,
                    'error_msg'  => 'Coll Code not found',
                    'updated_at' => Carbon::now(),
                );
                            
                TempUploads::where('file_id', '=', $file_id)->where('id', '=', $record['id'])
                                ->update($post_sync);
                continue;
            }//End if the validation


            $user_id = User::where('coll', '=', $record[$coll_code])
                              ->first();

            $check_borrowers   = CrmBorrowers::select('count(*) as allcount')
                              ->where('full_name', '=', $record[$full_name])
                              //->where('birthday', '=', $record[$birthday])
                              ->count();

            if($check_borrowers == 0){
                
                $insert_CrmBorrowers = array(
                    'profile_id'      => $uniq_id,
                    'full_name'       => $record[$full_name],
                    'birthday'        => '',
                    'address'         => $record[$address],
                    'email'           => (isset($record[$email])) ? $record[$email]:'',
                    'home_no'         => (isset($record[$home_no])) ? $record[$home_no]:'',
                    'business_no'     => (isset($record[$business_no])) ? $record[$business_no]:'',
                    'cellphone_no'    => (isset($record[$cellphone_no])) ? $this->formatContact($record[$cellphone_no]):'',
                    'other_phone_1'   => (isset($record[$other_phone_no_1])) ? $this->formatContact($record[$other_phone_no_1]):'',
                    'other_phone_2'   => (isset($record[$other_phone_no_2])) ? $this->formatContact($record[$other_phone_no_2]):'',
                    'other_phone_3'   => (isset($record[$other_phone_no_3])) ? $this->formatContact($record[$other_phone_no_3]):'',
                    'other_phone_4'   => (isset($record[$other_phone_no_4])) ? $this->formatContact($record[$other_phone_no_4]):'',
                    'other_phone_5'   => (isset($record[$other_phone_no_5])) ? $this->formatContact($record[$other_phone_no_5]):'',
                    'created_at'      => Carbon::now(),
                    'updated_at'      => Carbon::now(),
                );
                CrmBorrowers::insert($insert_CrmBorrowers);

                $log_txt = 'New account added in leads';

                $insert_CrmLeads = array(
                    'profile_id'            => $uniq_id,
                    'file_id'               => $file_id,
                    'assign_user'           => (isset($user_id->id)) ? $user_id->id:0,
                    'assign_group'          => (isset($groups)) ? $groups:0,
                    'account_number'        => (isset($record[$account_no])) ? $record[$account_no]:'',
                    'endo_date'             => (isset($record[$endo_date])) ? $record[$endo_date]:'',
                    'due_date'              => (isset($record[$due_date])) ? $record[$due_date]:'',
                    'outstanding_balance'   => (isset($record[$outstanding_bal])) ? $record[$outstanding_bal]:'',
                    'loan_amount'           => (isset($record[$loan_amount])) ? $record[$loan_amount]:'',
                    'cycle_day'             => (isset($record[$cycle_day])) ? $record[$cycle_day]:'',
                    'created_at'            => Carbon::now(),
                    'updated_at'            => Carbon::now(),
                );
                CrmLeads::insert($insert_CrmLeads);

            }else{

                if($check_borrowers == 1){

                    $crm_borrowers   = CrmBorrowers::where('full_name', '=', $record[$full_name])
                              //->where('birthday', '=', $record[$birthday])
                              ->first();
                    $uniq_id = $crm_borrowers['profile_id'];

                    $update_leads = array(
                        'deleted'       => 1,
                        'updated_at'    => Carbon::now(),
                    );
                                
                    CrmLeads::where('account_number', '=', $record[$account_no])->where('deleted', '=', 0)
                                    ->update($update_leads);

                     $insert_CrmLeads = array(
                        'profile_id'            => $uniq_id,
                        'file_id'               => $file_id,
                        'assign_user'           => (isset($user_id->id)) ? $user_id->id:0,
                        'assign_group'          => (isset($groups)) ? $groups:0,
                        'account_number'        => (isset($record[$account_no])) ? $record[$account_no]:'',
                        'endo_date'             => (isset($record[$endo_date])) ? $record[$endo_date]:'',
                        'due_date'              => (isset($record[$due_date])) ? $record[$due_date]:'',
                        'outstanding_balance'   => (isset($record[$outstanding_bal])) ? $record[$outstanding_bal]:'',
                        'loan_amount'           => (isset($record[$loan_amount])) ? $record[$loan_amount]:'',
                        'cycle_day'             => (isset($record[$cycle_day])) ? $record[$cycle_day]:'',
                        'created_at'            => Carbon::now(),
                        'updated_at'            => Carbon::now(),
                    );
                    CrmLeads::insert($insert_CrmLeads);

                    $log_txt = 'Updated account in leads';

                }else{
                    $post_sync = array(
                        'error'      => 1,
                        'error_msg'  => 'Account not found in the leads',
                        'updated_at' => Carbon::now(),
                    );
                                
                    TempUploads::where('file_id', '=', $file_id)->where('id', '=', $record['id'])
                                    ->update($post_sync);
                    continue;
                }
                
                


                
            }
            
          
            CrmLogs::saveLogs(0, $uniq_id, $record[$full_name], $log_txt);


                


                $post_sync = array(
                    'success'       => 1,
                    'profile_id'    => $uniq_id,
                    'updated_at'    => Carbon::now(),
                );
                            
                TempUploads::where('file_id', '=', $file_id)->where('id', '=', $record['id'])
                                ->update($post_sync);

                CrmLogs::saveLogs(0, $uniq_id, $record[$full_name], 'Account has been assigned to '.$user_id->name);


        }//End of foreach for list temp uploads


        $post_sync_status = array(
          'status'         => 2,
          'updated_at'     => Carbon::now(),
        );
                    
        FileUploadLogs::where('file_id', '=', $file_id)->update($post_sync_status);




    }
}
