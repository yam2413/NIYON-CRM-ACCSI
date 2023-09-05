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
use App\Imports\AutomaticNewLeadsImport;
use App\Jobs\AutoSyncNewLeadsJob;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AutomaticNewLeadsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public $file_id, $extension, $user_id, $group_id;
    public function __construct($file_id, $extension, $user_id, $group_id)
    {
        $this->file_id = $file_id;
        $this->extension = $extension;
        $this->user_id = $user_id;
        $this->group_id = $group_id;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    protected function formatDateExcel($date){ 
        if (gettype($date) === 'integer') { 
            $birthday = \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($date); 
            return $birthday->format('n/j/Y'); 
        } 
        return $date; 
    }

    public function handle()
    {

        $file_id    = $this->file_id;
        $extension  = $this->extension;
        $user_id    = $this->user_id;
        $file_path  = storage_path('app/public/file_upload').'/'.$file_id.'.'.$extension;
        $headings   = (new HeadingRowImport)->toArray($file_path);


        foreach ($headings[0] as $row) {

            $insert_data = array(
                'user'          => $user_id,
                'upload_type'    => 'new_leads',
                'header'         => 1,
                'file_id'        => $file_id,
                'data1'          => isset($row[0]) ? $row[0]:'--',
                'data2'          => isset($row[1]) ? $row[1]:'--',
                'data3'          => isset($row[2]) ? $row[2]:'--',
                'data4'          => isset($row[3]) ? $row[3]:'--',
                'data5'          => isset($row[4]) ? $row[4]:'--',
                'data6'          => isset($row[5]) ? $row[5]:'--',
                'data7'          => isset($row[6]) ? $row[6]:'--',
                'data8'          => isset($row[7]) ? $row[7]:'--',
                'data9'          => isset($row[8]) ? $row[8]:'--',
                'data10'     => isset($row[9]) ? $row[9]:'--',
                'data11'     => isset($row[10]) ? $row[10]:'--',
                'data12'     => isset($row[11]) ? $row[11]:'--',
                'data13'     => isset($row[12]) ? $row[12]:'--',
                'data14'     => isset($row[13]) ? $row[13]:'--',
                'data15'     => isset($row[14]) ? $row[14]:'--',
                'data16'     => isset($row[15]) ? $row[15]:'--',
                'data17'     => isset($row[16]) ? $row[16]:'--',
                'data18'     => isset($row[17]) ? $row[17]:'--',
                'data19'     => isset($row[18]) ? $row[18]:'--',
                'data20'     => isset($row[19]) ? $row[19]:'--',
                'data21'     => isset($row[20]) ? $row[20]:'--',
                'data22'     => isset($row[21]) ? $row[21]:'--',
                'data23'     => isset($row[22]) ? $row[22]:'--',
                'data24'     => isset($row[23]) ? $row[23]:'--',
                'data25'     => isset($row[24]) ? $row[24]:'--',
                'data26'     => isset($row[25]) ? $row[25]:'--',
                'data27'     => isset($row[26]) ? $row[26]:'--',
                'data28'     => isset($row[27]) ? $row[27]:'--',
                'data29'     => isset($row[28]) ? $row[28]:'--',
                'data30'     => isset($row[29]) ? $row[29]:'--',
                'data31'     => isset($row[30]) ? $row[30]:'--',
                'data32'     => isset($row[31]) ? $row[31]:'--',
                'data33'     => isset($row[32]) ? $row[32]:'--',
                'data34'     => isset($row[33]) ? $row[33]:'--',
                'data35'     => isset($row[34]) ? $row[34]:'--',
                'data36'     => isset($row[35]) ? $row[35]:'--',
                'data37'     => isset($row[36]) ? $row[36]:'--',
                'data38'     => isset($row[37]) ? $row[37]:'--',
                'data39'     => isset($row[38]) ? $row[38]:'--',
                'data40'     => isset($row[39]) ? $row[39]:'--',
                'data41'     => isset($row[40]) ? $row[40]:'--',
                'data42'     => isset($row[41]) ? $row[41]:'--',
                'data43'     => isset($row[42]) ? $row[42]:'--',
                'data44'     => isset($row[43]) ? $row[43]:'--',
                'data45'     => isset($row[44]) ? $row[44]:'--',
                'data46'     => isset($row[45]) ? $row[45]:'--',
                'data47'     => isset($row[46]) ? $row[46]:'--',
                'data48'     => isset($row[47]) ? $row[47]:'--',
                'data49'     => isset($row[48]) ? $row[48]:'--',
                'data50'     => isset($row[49]) ? $row[49]:'--',
                'data51'     => isset($row[50]) ? $row[50]:'--',
                'data52'     => isset($row[51]) ? $row[51]:'--',
                'data53'     => isset($row[52]) ? $row[52]:'--',
                'data54'     => isset($row[53]) ? $row[53]:'--',
                'data55'     => isset($row[54]) ? $row[54]:'--',
                'data56'     => isset($row[55]) ? $row[55]:'--',
                'data57'     => isset($row[56]) ? $row[56]:'--',
                'data58'     => isset($row[57]) ? $row[57]:'--',
                'data59'     => isset($row[58]) ? $row[58]:'--',
                'data60'     => isset($row[59]) ? $row[59]:'--',
                'data61'     => isset($row[60]) ? $row[60]:'--',
                'data62'     => isset($row[61]) ? $row[61]:'--',
                'data63'     => isset($row[62]) ? $row[62]:'--',
                'data64'     => isset($row[63]) ? $row[63]:'--',
                'data65'     => isset($row[64]) ? $row[64]:'--',
                'data66'     => isset($row[65]) ? $row[65]:'--',
                'data67'     => isset($row[66]) ? $row[66]:'--',
                'data68'     => isset($row[67]) ? $row[67]:'--',
                'data69'     => isset($row[68]) ? $row[68]:'--',
                'data70'     => isset($row[69]) ? $row[69]:'--',
                'data71'     => isset($row[70]) ? $row[70]:'--',
                'data72'     => isset($row[71]) ? $row[71]:'--',
                'data73'     => isset($row[72]) ? $row[72]:'--',
                'data74'     => isset($row[73]) ? $row[73]:'--',
                'data75'     => isset($row[74]) ? $row[74]:'--',
                'data76'     => isset($row[75]) ? $row[75]:'--',
                'data77'     => isset($row[76]) ? $row[76]:'--',
                'data78'     => isset($row[77]) ? $row[77]:'--',
                'data79'     => isset($row[78]) ? $row[78]:'--',
                'data80'     => isset($row[79]) ? $row[79]:'--',
                'data81'     => isset($row[80]) ? $row[80]:'--',
                'data82'     => isset($row[81]) ? $row[81]:'--',
                'data83'     => isset($row[82]) ? $row[82]:'--',
                'data84'     => isset($row[83]) ? $row[83]:'--',
                'data85'     => isset($row[84]) ? $row[84]:'--',
                'data86'     => isset($row[85]) ? $row[85]:'--',
                'data87'     => isset($row[86]) ? $row[86]:'--',
                'data88'     => isset($row[87]) ? $row[87]:'--',
                'data89'     => isset($row[88]) ? $row[88]:'--',
                'data90'     => isset($row[89]) ? $row[89]:'--',
        );
        TempUploads::insert($insert_data);

       
        }

        $done = Excel::import(new AutomaticNewLeadsImport($this->file_id, $user_id,'new_leads',$this->group_id), $file_path);

        if($done){
            $post_sync = array(
                'status'         => 2,
            );
                        
            FileUploadLogs::where('file_id', '=', $this->file_id)->update($post_sync);
        }
        
            


    }
}
