<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CrmLogs extends Model
{
    use HasFactory;

    public static function saveLogs($user_id, $profile_id, $name, $actions)
    {
        $insert_data = array(
            'user'     		  => $user_id,
            'profile_id'      => $profile_id,
            'name'      	  => $name,
            'actions'      	  => $actions,
            'created_at'      => date('Y-m-d H:i:s'),
            'updated_at'      => date('Y-m-d H:i:s'),
        );
        CrmLogs::insert($insert_data);
    }
}
