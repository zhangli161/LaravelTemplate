<?php
/**
 * Created by PhpStorm.
 * User: Acer
 * Date: 2018/9/25
 * Time: 16:56
 */

namespace App\Components;


use App\Http\Controllers\LoginController;
use App\Http\Helpers\ApiResponse;
use App\Models\QR;
use Illuminate\Support\Facades\Log;
use Mockery\Exception;

class QRManager
{
	public static function getXCXQR($agent_id, $page = 'pages/index/index')
	{
		$time =time();
//			date("Y-m-d_h:i:s");
		$filename = "Agent_{$agent_id}_$time";
		$access_token = self::getACCESS_TOKEN()->access_token;
		if (!$access_token)
			return ApiResponse::makeResponse(false, "获取access_token失败", ApiResponse::UNKNOW_ERROR);
		$url = 'https://api.weixin.qq.com/wxa/getwxacodeunlimit?access_token=' . $access_token;
		$headers = array('Content-type: ' . 'application/json');
		$body = [
//			'access_token'=>$access_token,
			'scene' => "agent_id=$agent_id",
			'page' => $page,
		];
		// 拼接字符串
		$fields_string = json_encode($body);
		
		$con = curl_init();
		curl_setopt($con, CURLOPT_URL, $url);
//		curl_setopt($con, CURLOPT_SSL_VERIFYHOST, FALSE);
//		curl_setopt($con, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($con, CURLOPT_HEADER, 0);
		curl_setopt($con, CURLOPT_POST, 1);
		curl_setopt($con, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($con, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($con, CURLOPT_POSTFIELDS, $fields_string);
		$info = curl_exec($con);
		//发送HTTPs请求并获取返回的数据，推荐使用curl
//		$json = json_decode($info);//对json数据解码
		$err = curl_error($con);
		curl_close($con);
		
		$path = storage_path('app\\public\\agentQR');
		
		
		$filePath = "$path\\{$filename}.jpg";
//		return $path;
//		$i = 0;
//		do
//			str_replace('/', '\\', $filePath, $i);
//		while ($i >= 0);
		if (!file_exists($path)) {
			mkdir($path, 0777, true);
		}
		file_put_contents($filePath, $info);
//		$url = qiniu_upload($filePath, 'wxqr');  //调用的全局函数
//		unlink($filePath);
		return $filePath;
//		dd($info);
	}
	
	public static function getACCESS_TOKEN()
	{
		$url = 'https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=' . env("XCX_APP_ID") . '&secret=' . env("XCX_APP_SECRET");
		//yourAppid为开发者appid.appSecret为开发者的appsecret,都可以从微信公众平台获取；
		$info = file_get_contents($url);
		//发送HTTPs请求并获取返回的数据，推荐使用curl
		$json = json_decode($info);//对json数据解码
		return $json;
	}
	
}