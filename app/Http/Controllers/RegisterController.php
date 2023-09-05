<?php

namespace App\Http\Controllers;

use Auth;
use App\Models\User;
use Illuminate\Http\Request;

class RegisterController extends Controller
{
    public function register(){
    	return view('home');
    }

    public function save_user(Request $request){
    	$user = User::where('email', $request['email'])->first();

    	if($user){
    		return response()->json([
    			'error'  => 'true',
    			'msg' 	 => 'Email is already exists.'
    		]);
    	}else{
    		$user = new User;
    		$user->name = $request['name'];
    		$user->email = $request['email'];
    		$user->password = bcrypt($request['password']);
            $user->level = $request['level'];
    	}
    	$user->save();
    	return response()->json([
    			'error'  => 'false',
    			'msg' 	 => 'User Registered Successfully'
    		]);
    }
}
