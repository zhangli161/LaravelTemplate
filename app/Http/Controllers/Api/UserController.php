<?php

namespace App\Http\Controllers\Api;

use App\Components\XCXLoginManager;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
	//
	public function __construct(\App\User $users)
	{
		$this->users = $users;
		$this->content = array();
	}
	
	public function login(Request $request)
	{
		
		// dd(request('name'));
//		if (Auth::attempt(['name' => request('name'), 'password' => request('password')])) {
//		Auth::provider();
//		$user=new UserProvider()retrieveByCredentials(['name' => request('name'), 'password' => request('password')]);
		$json = XCXLoginManager::getOpenid($request);
		$openid = array_get($json, 'openid');
		
		$user = null;
		if ($openid)
			$user = $this->users
				->firstOrNew(['openid' => $openid], ['openid' => $openid]);
//				->firstOrCreate(['name'=>'new'],['openid' => $openid]);
		if ($user) {
			
			$user->save();
			Auth::login($user);
		} else
			Auth::logout();
		
		if (Auth::check()) {
			$user = Auth::user();
			$this->content['token'] = $user->createToken('Pi App')->accessToken;
			$status = 200;
		} else {
			
			$this->content['error'] = "登录失败" . json_encode($json);
			$status = 401;
		}
		return response()->json($this->content, $status);
	}
	
	public function passport()
	{
		return response()->json(['user' => Auth::user()]);
	}
	
}
