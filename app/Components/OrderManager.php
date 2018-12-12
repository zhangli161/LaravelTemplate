<?php
/**
 * Created by PhpStorm.
 * User: Acer
 * Date: 2018/11/29
 * Time: 10:56
 */

namespace App\Components;


use App\Models\GoodsSKU;
use App\Models\Order;
use App\Http\Helpers\SnowFlakeIDWorker;
use App\Models\UserCoupon;
use App\User;

class OrderManager extends Manager
{
	protected static $keys = [];
	
	protected static $primary_key = 'id';
	
	protected static $Modle = Order::class;
	
	/**
	 * 结算订单
	 * @param User $user
	 * @param array $sku_opts
	 * @param null $user_address_id
	 * @param null $coupon_id
	 * @param int $payment_type
	 * @return Order|array
	 */
	public static function settlement(User $user, array $sku_opts, $user_address_id = null, $coupon_id = null, $buyer_message = "", $payment_type = 1)
	{
		if ($user_address_id)
			$user_address = $user->addresses()->findOrFail($user_address_id);
		$payment = 0.0;
		$post_fee = isset($user_address) ? PostageMananger::getPostageFee($user_address->region_id) : 0;
		$postage = 0;
		
		$order_arr = [
			'payment_type' => $payment_type,
			'user_id' => $user->id,
			'buyer_nick' => $user->name,
			'payment' => $payment,
			"postage" => $postage,//默认不包邮
			'post_fee' => $post_fee,
			'buyer_message' => $buyer_message
		];
		if (isset($user_address))
			$order_arr = array_merge(['receiver_name' => $user_address->name,
				'receiver_phone' => $user_address->mobile,
				'receiver_region_id' => $user_address->region_id,
				'receiver_address' => $user_address->address], $order_arr);
		$order = new Order($order_arr);
		
		$order_skus = array();
		foreach ($sku_opts as $sku_opt) {
			$sku = GoodsSKU::findOrFail($sku_opt['sku_id']);
			$amount = $sku_opt['amount'] or 1;
			$total_price = $amount * $sku->price;
			$payment += $total_price;
			
			array_push($order_skus, [
				'sku_id' => $sku->id,
				'sku_name' => $sku->name,
				'thumb' => $sku->spu->thumb,
				'amount' => $amount,
				'price' => $sku->price,
				'total_price' => $total_price,
			]);
			
			//不包邮时计算邮费
			if (!$sku->postage) {
//				$sku_postage = $sku->postages()->findOrFail($sku_opt['postage_id']);
//				$post_fee += $sku_postage->cost;
			} else {
				//包邮时所有的商品都包邮
				$postage = 1;
			}
			$order->skus = $order_skus;

//			array_push($order->skus, [
//				'sku_id' => $sku->id,
//				'sku_name' => $sku->sku_name,
//				'thumb' => $sku->spu->thumb,
//				'amount' => $amount,
//				'price' => $sku->price,
//				'total_price' => $amount * $sku->price,
//			]);
		
		
		}
		
		
		$order->payment = $payment;
		$order->post_fee = $postage == 0 ? $post_fee : 0;
		$order->postage = $postage;
		
		$coupons = $user->coupons;
		foreach ($coupons as $coupon) {
			$coupon->can_use = UserCouponManager::canUseCoupon($user, $coupon->id, $order->payment);
		}
		
		if ($coupon_id) {
			if (UserCouponManager::canUseCoupon($user, $coupon_id, $order->payment)["result"]) {
				
				$payment = UserCouponManager::paymentAfterUsingCoupon($user->coupons()->find($coupon_id)->coupon, $order->payment);
				$order->used_user_coupon_id = $coupon_id;
//				$payment = UserCouponManager::useCoupon($user, $coupon_id, $payment = $order->payment, $order->id);
				if ($payment) {
					$order->payment = $payment;
				}
			}
//			else return ["aaaaa"];
		}

//		$order->save();
//
			return ["order" => $order, "address" => $user->addresses, "user_coupons" => $coupons];
	}
	
	/**
	 * 创建订单
	 * @param User $user
	 * @param array $sku_opts
	 * @param $user_address_id
	 * @param $coupon_id
	 * @param int $payment_type
	 * @return mixed
	 */
	public static function newOrder(User $user, array $sku_opts, $user_address_id, $coupon_id, $buyer_message = "", $payment_type = 1)
	{
		$user_address = $user->addresses()->findOrFail($user_address_id);
		$payment = 0.0;
		$post_fee = PostageMananger::getPostageFee($user_address->region_id);
		$postage = 0;
		
		$order = Order::create([
			'payment_type' => $payment_type,
			'user_id' => $user->id,
			'buyer_nick' => $user->name,
			'receiver_name' => $user_address->name,
			'receiver_phone' => $user_address->mobile,
			'receiver_region_id' => $user_address->region_id,
			'receiver_address' => $user_address->address,
			'payment' => $payment,
			"postage" => $postage,//默认不包邮
			'post_fee' => $post_fee,
			'buyer_message' => $buyer_message
		]);
		
		$order_skus = array();
		foreach ($sku_opts as $sku_opt) {
			$sku = GoodsSKU::findOrFail($sku_opt['sku_id']);
			$amount = $sku_opt['amount'] or 1;
			$total_price = $amount * $sku->price;
			$payment += $total_price;
			
			array_push($order_skus, [
				'sku_id' => $sku->id,
				'sku_name' => $sku->name,
				'thumb' => $sku->spu->thumb,
				'amount' => $amount,
				'price' => $sku->price,
				'total_price' => $total_price,
			]);
			
			//不包邮时计算邮费
			if (!$sku->postage) {
//				$sku_postage = $sku->postages()->findOrFail($sku_opt['postage_id']);
//				$post_fee += $sku_postage->cost;
			} else {
				//包邮时所有的商品都包邮
				$postage = 1;
			}
			
			$order->skus()->create([
				'sku_id' => $sku->id,
				'sku_name' => $sku->sku_name,
				'thumb' => $sku->spu->thumb,
				'amount' => $amount,
				'price' => $sku->price,
				'total_price' => $amount * $sku->price,
			]);
			
			if ($sku->stock_type == 1)//下单减库存
			{
				if ($sku->stock > 0) {
					$sku->stock--;
					$sku->save();
					$order->status = 2;
					$order->save();
				} else {
					self::cancle($order);
					return false;
				}
			}
		}
		
		$order->payment = $payment;
		$order->post_fee = $postage == 0 ? $post_fee : 0;
		$order->postage = $postage;
		
		if ($coupon_id) {
			if (UserCouponManager::canUseCoupon($user, $coupon_id, $order->payment)["result"]) {
				$payment = UserCouponManager::useCoupon($user, $coupon_id, $payment = $order->payment, $order->id);
				$order->used_user_coupon_id = $coupon_id;
				
				if ($payment) {
					$order->payment = $payment;
				}
			}
		}
		
		$order->save();
		return $order;
	}
	
	/**
	 * 支付订单
	 *
	 * @param Order $order
	 * @return mixed
	 */
	
	public static function pay(Order $order)
	{
		foreach ($order->skus as $sku) {
			if ($sku->stock_type == 0) {//付款减库存
				if ($sku->stock > 0) {
					$sku->stock--;
					$sku->save();
					$order->status = 2;
					$order->save();
				} else {
					//库存不足
					self::cancle($order);
					//执行退款流程(待实现)
					
				}
			}
		}
		return [];
	}
	
	/**
	 * 查询订单支付状态 （待完善）
	 *
	 * @param Order $order
	 * @return bool
	 */
	public static function check_pay(Order $order)
	{
		//查询订单支付状态
		$result = true;
		if ($result)
			$order->status = 2;//已付款
		else {
			$created_at = strtotime($order->created_at);
			if (time() - $created_at > 30 * 60) {
				self::cancle($order);//交易关闭
			}
		}
		$order->save();
		return $result;
	}
	
	
	/**
	 * 检测物流信息
	 *
	 * @param Order $order
	 * @return bool
	 */
	public static function check_postage(Order $order)
	{
		$postage = PostalInquiriesManager::inquire($order->postage);
		
		$result = $postage->status == "2" //已收货
			&& (strtotime($order->updated_at) - time()) > 7 * 24 * 3600;//收货时间超过一星期
		if ($result) {
			$order->status = 5;//自动已完成
			$order->completed_at = now();
			$order->save();
		};
		return $result;
	}
	
	/**
	 * 检测所有订单的支付状态
	 *
	 */
	public static function check_pay_all()
	{
		$orders = Order::where('status', 1)->get();
		foreach ($orders as $order) {
			self::check_pay($order);
		}
	}
	
	public static function check_postage_all()
	{
		$orders = Order::where('status', 4)->get();
		foreach ($orders as $order) {
			self::check_postage($order);
		}
	}
	
	/**
	 * 取消订单
	 *
	 * @param Order $order
	 */
	public static function cancle(Order $order)
	{
		$skus = $order->skus;
		//释放库存
		foreach ($skus as $sku) {
			if ($sku->stock_type == 1)//1下单减库存
			{
				$sku->stock++;
				$sku->save();
			}
		}
		$order->closed_at = now();
		$order->status = 6;
		$order->save();
		$order->delete();
		
		return;
	}
}