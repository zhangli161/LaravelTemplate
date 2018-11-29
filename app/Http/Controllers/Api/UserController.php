<?php

namespace App\Http\Controllers\Api;

use App\Components\MessageManager;
use App\Components\VertifyManager;
use App\Components\XCXLoginManager;
use App\Http\Helpers\ApiResponse;
use App\Models\UserCredit;
use App\Models\UserWX;
use Carbon\Carbon;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Foundation\Auth\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

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
		$data = $request->all();
		$ret = array();
		$result = false;
		
		$json = XCXLoginManager::getOpenid($data);
		$openid = array_get($json, 'openid');
		$sessionkey = array_get($json, 'session_key');
		$ret['session_key'] = $sessionkey;
		$user = null;
		if ($openid) {
			$user_wx = UserWX::query()->firstOrNew(['openId' => $openid],
				['openId' => $openid]);
			$user = XCXLoginManager::getUserByOpenId($openid);
//			return json_encode($user);
			$user->latest_login_time = Carbon::now();
			$user->save();
			$user_wx->user_id = $user->id;
			$user_wx->save();
		}
		
		if ($user and $user->status != 0) {
			$user = $this->users->firstOrNew(['id' => $user->id]);
			
			Auth::login($user);
			if (array_key_exists('rawData', $data)) {
				$wx_userinfo = XCXLoginManager::decryptData($request, $sessionkey);
				Log::info('用户信息：' . json_encode($wx_userinfo));
				if (array_get($wx_userinfo, 'ret')) {
					$userinfo = json_decode($wx_userinfo['ret'], true);
					$userinfo['user_id'] = $user->id;
					$user_wx = UserWX::query()->firstOrNew(['user_id' => $user->id]);
					$user_wx->fill($userinfo)->save();
					$user->avatar = $user_wx->avatarUrl;
					$user->save();
					$ret['wx_userInfo'] = $user_wx;
				};
			};
		} else
			Auth::logout();
		
		if (Auth::check()) {
			$user = Auth::user();
			$ret['token'] = $user->createToken('Pi App')->accessToken;
			$result = true;
			$status = ApiResponse::SUCCESS_CODE;
			
			$user->credit or UserCredit::query()->create(['user_id' => $user->id, 'credit' => 0]);
		} else {
			$ret['error'] = "登录失败" . json_encode($json);
			$status = ApiResponse::NO_USER;
		}
		return ApiResponse::makeResponse(true, $ret, $status);
	}
	
	public function passport()
	{
		$user = Auth::user();
		MessageManager::getGroupMessages($user);
		$user->credit or UserCredit::query()->create(['user_id' => $user->id, 'credit' => 0]);
		$user->messages;
//		$user->coupons;
		return ApiResponse::makeResponse(true, $user, ApiResponse::SUCCESS_CODE);
	}
	
	public static function sendVertifyCode(Request $request)
	{
		$data = $request->all();
		//检验参数
		if ($request->filled('phonenum')) {
			$send_ret = VertifyManager::doVertify($data['phonenum']);
			if ($send_ret)
				return ApiResponse::makeResponse(true, "发送成功", ApiResponse::SUCCESS_CODE);
			else
				return ApiResponse::makeResponse(false, "发送失败", ApiResponse::UNKNOW_ERROR);
		} else {
			return ApiResponse::makeResponse(false, "缺少参数", ApiResponse::MISSING_PARAM);
		}
	}
	
	public static function credit_record()
	{
		return ApiResponse::makeResponse(false, Auth::user()->credit_records, ApiResponse::SUCCESS_CODE);
	}
	
	
}
