<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
	/**
	 * Create a new controller instance.
	 *
	 * @return void
	 */
	public function __construct()
	{
		//通过这种方法调用中间件，也可以写在路由中
		$this->middleware('auth');
	}
	
	/**
	 * Show the application dashboard.
	 *
	 * @return \Illuminate\Http\Response
	 */
	public function index(Request $request)
	{
		// 获取当前认证用户...
		$user = Auth::user();

		// 获取当前认证用户的ID...
		$id = Auth::id();
		
		//从Request中获取用户实例
		$r_user = $request->user();
		
		$login=false;
		//判断用户是否登录
		if (Auth::check()) {
			// The user is logged in...
			$login=true;
		}
		
		return view('home', ['user' => $user, 'id' => $id, 'ruser' => $r_user]);
	}
}
