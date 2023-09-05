<?php

namespace App\Jobs;

use Auth;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Groups;
use App\Models\TempUploads;
use App\Models\FileUploadLogs;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\HeadingRowImport;
use App\Imports\TempUploadsImport;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class DataTypesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $file_id, $import_array;
    public function __construct($file_id, $import_array)
    {
        $this->file_id      = $file_id;
        $this->import_array = $import_array;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {

        $post_sync1 = array(
           'status'         => 4,
           'data_type'      => 1,
        );
        FileUploadLogs::where('file_id', '=', $this->file_id)->update($post_sync1);

        $file_logs = FileUploadLogs::where('file_id','=' ,$this->file_id)->first();
        $user_id = $file_logs->user;
        $file_path  = storage_path('app/'.$file_logs->path);

        $decode     = json_decode($this->import_array, true);

        $insert_data = array(
                'user'           => $user_id,
                'upload_type'    => 'new_leads',
                'header'         => 2,
                'file_id'        => $this->file_id,
                'data1'          => isset($decode->data1) ? $decode->data1:'--',
                'data2'          => isset($decode->data2) ? $decode->data2:'--',
                'data3'          => isset($decode->data3) ? $decode->data3:'--',
                'data4'          => isset($decode->data4) ? $decode->data4:'--',
                'data5'          => isset($decode->data5) ? $decode->data5:'--',
                'data6'          => isset($decode->data6) ? $decode->data6:'--',
                'data7'          => isset($decode->data7) ? $decode->data7:'--',
                'data8'          => isset($decode->data8) ? $decode->data8:'--',
                'data9'          => isset($decode->data9) ? $decode->data9:'--',
                'data10'         => isset($decode->data10) ? $decode->data10:'--',
                'data11'         => isset($decode->data11) ? $decode->data11:'--',
                'data12'         => isset($decode->data12) ? $decode->data12:'--',
                'data13'         => isset($decode->data13) ? $decode->data13:'--',
                'data14'         => isset($decode->data14) ? $decode->data14:'--',
                'data15'         => isset($decode->data15) ? $decode->data15:'--',
                'data16'         => isset($decode->data16) ? $decode->data16:'--',
                'data17'         => isset($decode->data17) ? $decode->data17:'--',
                'data18'         => isset($decode->data18) ? $decode->data18:'--',
                'data19'         => isset($decode->data19) ? $decode->data19:'--',
                'data20'         => isset($decode->data20) ? $decode->data20:'--',
                'data21'         => isset($decode->data21) ? $decode->data21:'--',
                'data22'         => isset($decode->data22) ? $decode->data22:'--',
                'data23'         => isset($decode->data23) ? $decode->data23:'--',
                'data24'         => isset($decode->data24) ? $decode->data24:'--',
                'data25'         => isset($decode->data25) ? $decode->data25:'--',
                'data26'         => isset($decode->data26) ? $decode->data26:'--',
                'data27'         => isset($decode->data27) ? $decode->data27:'--',
                'data28'         => isset($decode->data28) ? $decode->data28:'--',
                'data29'         => isset($decode->data29) ? $decode->data29:'--',
                'data30'         => isset($decode->data30) ? $decode->data30:'--',
                'data31'         => isset($decode->data31) ? $decode->data31:'--',
                'data32'         => isset($decode->data32) ? $decode->data32:'--',
                'data33'         => isset($decode->data33) ? $decode->data33:'--',
                'data34'         => isset($decode->data34) ? $decode->data34:'--',
                'data35'         => isset($decode->data35) ? $decode->data35:'--',
                'data36'         => isset($decode->data36) ? $decode->data36:'--',
                'data37'         => isset($decode->data37) ? $decode->data37:'--',
                'data38'         => isset($decode->data38) ? $decode->data38:'--',
                'data39'         => isset($decode->data39) ? $decode->data39:'--',
                'data40'         => isset($decode->data40) ? $decode->data40:'--',
                'data41'         => isset($decode->data41) ? $decode->data41:'--',
                'data42'         => isset($decode->data42) ? $decode->data42:'--',
                'data43'         => isset($decode->data43) ? $decode->data43:'--',
                'data44'         => isset($decode->data44) ? $decode->data44:'--',
                'data45'         => isset($decode->data45) ? $decode->data45:'--',
                'data46'         => isset($decode->data46) ? $decode->data46:'--',
                'data47'         => isset($decode->data47) ? $decode->data47:'--',
                'data48'         => isset($decode->data48) ? $decode->data48:'--',
                'data49'         => isset($decode->data49) ? $decode->data49:'--',
                'data50'         => isset($decode->data50) ? $decode->data50:'--',
                'data51'         => isset($decode->data51) ? $decode->data51:'--',
                'data52'         => isset($decode->data52) ? $decode->data52:'--',
                'data53'         => isset($decode->data53) ? $decode->data53:'--',
                'data54'         => isset($decode->data54) ? $decode->data54:'--',
                'data55'         => isset($decode->data55) ? $decode->data55:'--',
                'data56'         => isset($decode->data56) ? $decode->data56:'--',
                'data57'         => isset($decode->data57) ? $decode->data57:'--',
                'data58'         => isset($decode->data58) ? $decode->data58:'--',
                'data59'         => isset($decode->data59) ? $decode->data59:'--',
                'data60'         => isset($decode->data60) ? $decode->data60:'--',
                'data61'         => isset($decode->data61) ? $decode->data61:'--',
                'data62'         => isset($decode->data62) ? $decode->data62:'--',
                'data63'         => isset($decode->data63) ? $decode->data63:'--',
                'data64'         => isset($decode->data64) ? $decode->data64:'--',
                'data65'         => isset($decode->data65) ? $decode->data65:'--',
                'data66'         => isset($decode->data66) ? $decode->data66:'--',
                'data67'         => isset($decode->data67) ? $decode->data67:'--',
                'data68'         => isset($decode->data68) ? $decode->data68:'--',
                'data69'         => isset($decode->data69) ? $decode->data69:'--',
                'data70'         => isset($decode->data70) ? $decode->data70:'--',
                'data71'         => isset($decode->data71) ? $decode->data71:'--',
                'data72'         => isset($decode->data72) ? $decode->data72:'--',
                'data73'         => isset($decode->data73) ? $decode->data73:'--',
                'data74'         => isset($decode->data74) ? $decode->data74:'--',
                'data75'         => isset($decode->data75) ? $decode->data75:'--',
                'data76'         => isset($decode->data76) ? $decode->data76:'--',
                'data77'         => isset($decode->data77) ? $decode->data77:'--',
                'data78'         => isset($decode->data78) ? $decode->data78:'--',
                'data79'         => isset($decode->data79) ? $decode->data79:'--',
                'data80'         => isset($decode->data80) ? $decode->data80:'--',
                // 'data81'         => isset($this->data81) ? $row[80]:'--',
                // 'data82'         => isset($this->data82) ? $row[81]:'--',
                // 'data83'         => isset($this->data83) ? $row[82]:'--',
                // 'data84'         => isset($this->data84) ? $row[83]:'--',
                // 'data85'         => isset($this->data85) ? $row[84]:'--',
                // 'data86'         => isset($this->data86) ? $row[85]:'--',
                // 'data87'         => isset($this->data87) ? $row[86]:'--',
                // 'data88'         => isset($this->data88) ? $row[87]:'--',
                // 'data89'         => isset($this->data89) ? $row[88]:'--',
                // 'data90'         => isset($this->data90) ? $row[89]:'--',
        );
        TempUploads::insert($insert_data);

        $done = Excel::import(new TempUploadsImport($this->file_id, $user_id,'new_leads'), $file_path);

        if($done){
            $post_sync = array(
                'status'         => 1,
            );
                        
            FileUploadLogs::where('file_id', '=', $this->file_id)->update($post_sync);
        }
    }
}
