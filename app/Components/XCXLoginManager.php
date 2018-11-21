<?php
/**
 * Created by PhpStorm.
 * User: Acer
 * Date: 2018/10/29
 * Time: 15:21
 */

namespace App\Components;


use App\Http\Helpers\WXBizDataCrypt;
use App\Models\UserWX;
use Carbon\Carbon;
use App\User;
use Illuminate\Http\Request;

class XCXLoginManager
{
	public static function getOpenid($data)
	{
		$AppId = env('XCX_APP_ID');
		$AppSecret = env('XCX_APP_SECRET');
//		return [$AppId,$AppSecret];
		$code = $data['code'];//小程序传来的code值
		$url = 'https://api.weixin.qq.com/sns/jscode2session?appid=' . $AppId . '&secret=' . $AppSecret . '&js_code=' . $code . '&grant_type=authorization_code';
		//yourAppid为开发者appid.appSecret为开发者的appsecret,都可以从微信公众平台获取；
		$info = file_get_contents($url);
		//发送HTTPs请求并获取返回的数据，推荐使用curl
		$json = json_decode($info, true);//对json数据解码
		return $json;
	}
	
	public static function decryptData(Request $request, $sessionKey)
	{
		$data = $request->all();
		$appid = env('XCX_APP_ID');
		$encryptedData = $data['encryptedData'];
		$iv = $data['iv'];
		
		$pc = new WXBizDataCrypt($appid, $sessionKey);
		$errCode = $pc->decryptData($encryptedData, $iv, $ret);
//		return[$appid, $sessionKey,$encryptedData, $iv];
		
		return ['ret' => $ret, 'errCode' => $errCode];
	}
	
	public static function getUserByOpenId($openid)
	{
		$user_wx = UserWX::query()->where('openId', $openid)->first();
		if (!$user_wx) {
			$user = User::query()->create(['latest_login_time' => Carbon::now()]);
		} else {
			$user = User::query()->where('id', '=',$user_wx->user_id)->first();
		}
		return $user;
	}
}