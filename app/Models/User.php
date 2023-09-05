<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'level',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public static function usersRole($level){
        switch ($level) {

            case '0':
                return 'System Administrator';
                break;

            case '1':
                return 'Admin';
                break;

            case '2':
                return 'Manager';
                break;

            case '3':
                return 'Collector';
                break;
            
            default:
                # code...
                break;
        }
    }

    public static function getName($id)
    {
        if($id == 0){
            return "-----";
        }

        $user = User::where('id', $id)->first();
        if(isset($user->firstname)){
            return $user->firstname.' '.$user->lastname;
        }else{
            return "-----";
        }
        
    }
}
