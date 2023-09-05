<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemLogs extends Model
{
    use HasFactory;

    public static function saveLogs($user_id, $actions)
    {
        $insert_data = array(
            'user'     		  => $user_id,
            'actions'      	  => $actions,
            'created_at'      => date('Y-m-d H:i:s'),
            'updated_at'      => date('Y-m-d H:i:s'),
        );
        SystemLogs::insert($insert_data);
    }
}
