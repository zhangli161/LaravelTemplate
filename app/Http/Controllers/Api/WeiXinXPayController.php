<?php
/**
 * Created by PhpStorm.
 * User: Acer
 * Date: 2018/12/18
 * Time: 10:08
 */

namespace App\Http\Controllers\Api;

use App\Models\Order;
use App\Models\WeiXinXPay;
use Illuminate\Http\Request;

class WeiXinXPayController
{
	
	public $config;
	
	public function __construct()
	{
		$config = array(
			'appid' => 'wxda6df008e0971806',
			'pay_mchid' => '1498279392',
			'pay_apikey' => 'xxxxxx',
			'notify_url' => 'xxxxxx',
			'body' => 'xxxxxx'
		);
		$this->config = $config;
	}
	
	
	/**
	 * 预支付请求接口(POST)
	 * $openid         openid
	 * $body      商品简单描述
	 * $order_sn    订单编号
	 * $total_fee   金额
	 * json的数据
	 */
	public function requestPayment(Request $request)
	{
		$errors = new \stdClass();
		$object = $request->all();
		$res = Order::where([
			'order_id' => trim($object['order_id']),
			'openid' => trim($object['openid'])
		])->select('openid', 'price', 'pay_status', 'order_id')->first();
		if ($res && $res->pay_status == 0) {
			$pay = new WeiXinXPay($this->config);
			$obj = new \stdClass();
			$obj->openid = $res->openid;
			$obj->body = $this->config['body'];
			$obj->order_sn = $res->order_id;
			$obj->total_fee = $res->price;
			$result = $pay->prepay($obj);
			$result = json_decode($result);
			if ($result->status == 1) {
				$errors->status = 1;
				$errors->data = $result->data;
				return response()->json($errors, 200);
			} else {
				$errors->status = 0;
				$errors->result = '支付异常';
				return response()->json($errors, 200);
			}
		} else {
			$errors->status = 0;
			$errors->result = '支付异常';
			return response()->json($errors, 200);
		}
	}
	
	/**
	 * 支付回调
	 * 返回数组去修改支付订单数据
	 */
	public function notifyPay()
	{
		$notify = new WeiXinXPay($this->config);
		$data = $notify->notify();
		if ($data) {
			//修改数据库订单状态
		}
	}
	
}