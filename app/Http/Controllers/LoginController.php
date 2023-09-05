<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\User;
use App\Models\SystemLogs;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function login(){
    	return view('auth.login');
    }

    public function user_login(Request $request){

    	if(Auth::attempt([
    		'email' 	=> $request->input('email'),
    		'password' 	=> $request->input('password')
    	])){
            $user = Auth::user();
            SystemLogs::saveLogs($user->id, $user->name.' Successfully Logged In');
    		return response()->json([
    			'error'  => 'false',
    			'msg' 	 => 'Successfully Logged In'
    		]);
    	}else{
    		return response()->json([
    			'error'  => 'true',
    			'msg' 	 => 'Invalid Email Or Password.'
    		]);
    	}
    }

    public function getSignout()
    {
        $user = Auth::user();
        SystemLogs::saveLogs($user->id, $user->name.' Successfully Logged Out');
        Auth::logout();

        return redirect()->route('home');
    }
}
