<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Groups extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'description',
        'create_by',
        'color_palette',
    ];

     public static function usersGroup($id){

     	if($id == 0){
     		return '--';
     	}

        $groups = Groups::where('id', $id)->first();
        if(isset($groups->name)){
            return ucfirst($groups->name);
        }else{
            return '--';
        }
        

    }

}
