<?php

namespace App\Http\Controllers;

use Auth;
class HomeController extends Controller
{
	public function index()
	{
		if(Auth::check()){
			//return view('pages.dashboard.index');
			return redirect()->route('pages.dashboard.index');
		}
		return view('home');
	}

	public function routeName(Request $request){
		return $request->route()->getName();
	}

}