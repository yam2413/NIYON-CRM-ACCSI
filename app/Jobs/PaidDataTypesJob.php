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

class PaidDataTypesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
   public $file_id, $data1, $data2, $data3,$data4,$data5,$data6,$data7,$data8,$data9,$data10,$data11,$data12,$data13,$data14,$data15,$data16,$data17,$data18,$data19,$data20,$data21,$data22,$data23,$data24,$data25,$data26,$data27,$data28,$data29,$data30,$data31,$data32,$data33,$data34,$data35,$data36,$data37,$data38,$data39,$data40,$data41,$data42,$data43,$data44,$data45,$data46,$data47,$data48,$data49,$data50,$data51,$data52,$data53,$data54,$data55,$data56,$data57,$data58,$data59,$data60,$data61,$data62,$data63,$data64,$data65,$data66,$data67,$data68,$data69,$data70,$data71,$data72,$data73,$data74,$data75,$data76,$data77,$data78,$data79,$data80;
    public function __construct($file_id, $data1, $data2, $data3,$data4,$data5,$data6,$data7,$data8,$data9,$data10,$data11,$data12,$data13,$data14,$data15,$data16,$data17,$data18,$data19,$data20,$data21,$data22,$data23,$data24,$data25,$data26,$data27,$data28,$data29,$data30,$data31,$data32,$data33,$data34,$data35,$data36,$data37,$data38,$data39,$data40,$data41,$data42,$data43,$data44,$data45,$data46,$data47,$data48,$data49,$data50,$data51,$data52,$data53,$data54,$data55,$data56,$data57,$data58,$data59,$data60,$data61,$data62,$data63,$data64,$data65,$data66,$data67,$data68,$data69,$data70,$data71,$data72,$data73,$data74,$data75,$data76,$data77,$data78,$data79,$data80)
    {
        $this->file_id = $file_id;
        $this->data1 = $data1; 
        $this->data2 = $data2; 
        $this->data3 = $data3;
        $this->data4 = $data4;
        $this->data5 = $data5;
        $this->data6 = $data6;
        $this->data7 = $data7;
        $this->data8 = $data8;
        $this->data9 = $data9;
        $this->data10 = $data10;
        $this->data11 = $data11;
        $this->data12 = $data12;
        $this->data13 = $data13;
        $this->data14 = $data14;
        $this->data15 = $data15;
        $this->data16 = $data16;
        $this->data17 = $data17;
        $this->data18 = $data18;
        $this->data19 = $data19;
        $this->data20 = $data20;
        $this->data21 = $data21;
        $this->data22 = $data22;
        $this->data23 = $data23;
        $this->data24 = $data24;
        $this->data25 = $data25;
        $this->data26 = $data26;
        $this->data27 = $data27;
        $this->data28 = $data28;
        $this->data29 = $data29;
        $this->data30 = $data30;
        $this->data31 = $data31;
        $this->data32 = $data32;
        $this->data33 = $data33;
        $this->data34 = $data34;
        $this->data35 = $data35;
        $this->data36 = $data36;
        $this->data37 = $data37;
        $this->data38 = $data38;
        $this->data39 = $data39;
        $this->data40 = $data40;
        $this->data41 = $data41;
        $this->data42 = $data42;
        $this->data43 = $data43;
        $this->data44 = $data44;
        $this->data45 = $data45;
        $this->data46 = $data46;
        $this->data47 = $data47;
        $this->data48 = $data48;
        $this->data49 = $data49;
        $this->data50 = $data50;
        $this->data51 = $data51;
        $this->data52 = $data52;
        $this->data53 = $data53;
        $this->data54 = $data54;
        $this->data55 = $data55;
        $this->data56 = $data56;
        $this->data57 = $data57;
        $this->data58 = $data58;
        $this->data59 = $data59;
        $this->data60 = $data60;
        $this->data61 = $data61;
        $this->data62 = $data62;
        $this->data63 = $data63;
        $this->data64 = $data64;
        $this->data65 = $data65;
        $this->data66 = $data66;
        $this->data67 = $data67;
        $this->data68 = $data68;
        $this->data69 = $data69;
        $this->data70 = $data70;
        $this->data71 = $data71;
        $this->data72 = $data72;
        $this->data73 = $data73;
        $this->data74 = $data74;
        $this->data75 = $data75;
        $this->data76 = $data76;
        $this->data77 = $data77;
        $this->data78 = $data78;
        $this->data79 = $data79;
        $this->data80 = $data80;
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
        );
        FileUploadLogs::where('file_id', '=', $this->file_id)->update($post_sync1);

        $file_logs = FileUploadLogs::where('file_id','=' ,$this->file_id)->first();
        $user_id = $file_logs->user;
        $file_path  = storage_path('app/'.$file_logs->path);

        $insert_data = array(
                'user'           => $user_id,
                'upload_type'    => 'paids',
                'header'         => 2,
                'file_id'        => $this->file_id,
                'data1'          => isset($this->data1) ? $this->data1:'--',
                'data2'          => isset($this->data2) ? $this->data2:'--',
                'data3'          => isset($this->data3) ? $this->data3:'--',
                'data4'          => isset($this->data4) ? $this->data4:'--',
                'data5'          => isset($this->data5) ? $this->data5:'--',
                'data6'          => isset($this->data6) ? $this->data6:'--',
                'data7'          => isset($this->data7) ? $this->data7:'--',
                'data8'          => isset($this->data8) ? $this->data8:'--',
                'data9'          => isset($this->data9) ? $this->data9:'--',
                'data10'         => isset($this->data10) ? $this->data10:'--',
                'data11'         => isset($this->data11) ? $this->data11:'--',
                'data12'         => isset($this->data12) ? $this->data12:'--',
                'data13'         => isset($this->data13) ? $this->data13:'--',
                'data14'         => isset($this->data14) ? $this->data14:'--',
                'data15'         => isset($this->data15) ? $this->data15:'--',
                'data16'         => isset($this->data16) ? $this->data16:'--',
                'data17'         => isset($this->data17) ? $this->data17:'--',
                'data18'         => isset($this->data18) ? $this->data18:'--',
                'data19'         => isset($this->data19) ? $this->data19:'--',
                'data20'         => isset($this->data20) ? $this->data20:'--',
                'data21'         => isset($this->data21) ? $this->data21:'--',
                'data22'         => isset($this->data22) ? $this->data22:'--',
                'data23'         => isset($this->data23) ? $this->data23:'--',
                'data24'         => isset($this->data24) ? $this->data24:'--',
                'data25'         => isset($this->data25) ? $this->data25:'--',
                'data26'         => isset($this->data26) ? $this->data26:'--',
                'data27'         => isset($this->data27) ? $this->data27:'--',
                'data28'         => isset($this->data28) ? $this->data28:'--',
                'data29'         => isset($this->data29) ? $this->data29:'--',
                'data30'         => isset($this->data30) ? $this->data30:'--',
                'data31'         => isset($this->data31) ? $this->data31:'--',
                'data32'         => isset($this->data32) ? $this->data32:'--',
                'data33'         => isset($this->data33) ? $this->data33:'--',
                'data34'         => isset($this->data34) ? $this->data34:'--',
                'data35'         => isset($this->data35) ? $this->data35:'--',
                'data36'         => isset($this->data36) ? $this->data36:'--',
                'data37'         => isset($this->data37) ? $this->data37:'--',
                'data38'         => isset($this->data38) ? $this->data38:'--',
                'data39'         => isset($this->data39) ? $this->data39:'--',
                'data40'         => isset($this->data40) ? $this->data40:'--',
                'data41'         => isset($this->data41) ? $this->data41:'--',
                'data42'         => isset($this->data42) ? $this->data42:'--',
                'data43'         => isset($this->data43) ? $this->data43:'--',
                'data44'         => isset($this->data44) ? $this->data44:'--',
                'data45'         => isset($this->data45) ? $this->data45:'--',
                'data46'         => isset($this->data46) ? $this->data46:'--',
                'data47'         => isset($this->data47) ? $this->data47:'--',
                'data48'         => isset($this->data48) ? $this->data48:'--',
                'data49'         => isset($this->data49) ? $this->data49:'--',
                'data50'         => isset($this->data50) ? $this->data50:'--',
                'data51'         => isset($this->data51) ? $this->data51:'--',
                'data52'         => isset($this->data52) ? $this->data52:'--',
                'data53'         => isset($this->data53) ? $this->data53:'--',
                'data54'         => isset($this->data54) ? $this->data54:'--',
                'data55'         => isset($this->data55) ? $this->data55:'--',
                'data56'         => isset($this->data56) ? $this->data56:'--',
                'data57'         => isset($this->data57) ? $this->data57:'--',
                'data58'         => isset($this->data58) ? $this->data58:'--',
                'data59'         => isset($this->data59) ? $this->data59:'--',
                'data60'         => isset($this->data60) ? $this->data60:'--',
                'data61'         => isset($this->data61) ? $this->data61:'--',
                'data62'         => isset($this->data62) ? $this->data62:'--',
                'data63'         => isset($this->data63) ? $this->data63:'--',
                'data64'         => isset($this->data64) ? $this->data64:'--',
                'data65'         => isset($this->data65) ? $this->data65:'--',
                'data66'         => isset($this->data66) ? $this->data66:'--',
                'data67'         => isset($this->data67) ? $this->data67:'--',
                'data68'         => isset($this->data68) ? $this->data68:'--',
                'data69'         => isset($this->data69) ? $this->data69:'--',
                'data70'         => isset($this->data70) ? $this->data70:'--',
                'data71'         => isset($this->data71) ? $this->data71:'--',
                'data72'         => isset($this->data72) ? $this->data72:'--',
                'data73'         => isset($this->data73) ? $this->data73:'--',
                'data74'         => isset($this->data74) ? $this->data74:'--',
                'data75'         => isset($this->data75) ? $this->data75:'--',
                'data76'         => isset($this->data76) ? $this->data76:'--',
                'data77'         => isset($this->data77) ? $this->data77:'--',
                'data78'         => isset($this->data78) ? $this->data78:'--',
                'data79'         => isset($this->data79) ? $this->data79:'--',
                'data80'         => isset($this->data80) ? $this->data80:'--',
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

        $done = Excel::import(new TempUploadsImport($this->file_id, $user_id, 'paids'), $file_path);

        if($done){
            $post_sync = array(
                'data_type'      => 1,
                'status'         => 1,
            );
                        
            FileUploadLogs::where('file_id', '=', $this->file_id)->update($post_sync);
        }
    }
}
