<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CampaignLogs extends Model
{
    use HasFactory;
    public static function saveLogs($user_id, $file_id, $leads_id, $log_type, $actions)
    {
        $insert_data = array(
            'user'     		  => $user_id,
            'file_id'      	  => $file_id,
            'leads_id'        => $leads_id,
            'log_type'        => $log_type,
            'actions'      	  => $actions,
            'created_at'      => date('Y-m-d H:i:s'),
            'updated_at'      => date('Y-m-d H:i:s'),
        );
        CampaignLogs::insert($insert_data);
    }
}
