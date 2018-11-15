<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2018/2/2
 * Time: 14:23
 */

namespace App\Components;


use App\Models\Vertify;

class VertifyManager
{
	/*
	   * 生成验证码
	   *
	   * By TerryQi
	   */
	public static function doVertify($mobile)
	{
		$vertify_code = rand(0, 9) . rand(0, 9) . rand(0, 9) . rand(0, 9);  //生成4位验证码
		$vertify = new Vertify();
		$vertify->addtime = time();
		$vertify->status = 0;//待发送状态
		$vertify->mobile = $mobile;
		$vertify->code = $vertify_code;
		$vertify->save();
		/*
		 * 预留，需要触发短信端口进行验证码下发
		 */
		if ($vertify) {
			return self::sendVerification($vertify);
		}
		return false;
	}
	
	
	
	public static function sendVerification($vertify)
	{
		$phone = $vertify->mobile;
		$content = $vertify->code;
		
		$result='******这里发送短信并返回result******';
		
		$vertify->status = $result ? 3 : 0;
		$vertify->save();
		return $result;
	}
	
	/*
	 * 校验验证码
	 *
	 * By TerryQi
	 *
	 * 2017-11-28
	 */
	public static function judgeVertifyCode($mobile, $vertify_code)
	{
		$vertify = Vertify::where('mobile', $mobile)
			->where('code', $vertify_code)->where('status', '3')->first();
		if ($vertify) {
			//验证码置为失效
			$vertify->status = '2';
			$vertify->save();
			return true;
		} else {
			return false;
		}
	}
	
}