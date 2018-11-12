<?php

namespace App\Http\Controllers\Api;

use App\Components\XCXLoginManager;
use App\Http\Helpers\ApiResponse;
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
		$ret=array();
		$result=false;
		
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
			$ret['token'] = $user->createToken('Pi App')->accessToken;
			$result=true;
			$status = ApiResponse::SUCCESS_CODE;
		} else {
			$ret['error'] = "登录失败" . json_encode($json);
			$status = ApiResponse::NO_USER;
		}
		return ApiResponse::makeResponse(true,$ret,$status);
	}
	
	public function passport()
	{
		return response()->json(['user' => Auth::user(),'message'=>Auth::user()->messages()]);
	}
	
}
