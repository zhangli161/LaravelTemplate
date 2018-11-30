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
	
	public static function newOrder(User $user, array $sku_opts, $user_address_id, $coupon_id, $payment_type = 1)
	{
		$user_address = $user->addresses()->findOrFail($user_address_id);
		$payment = 0.0;
		$post_fee = 0.0;
		
		$order = Order::create([
			'payment_type' => $payment_type,
			'user_id' => $user->id,
			'buyer_nick' => $user->name,
			'receiver_name' => $user_address->name,
			'receiver_phone' => $user_address->mobile,
			'receiver_region_id' => $user_address->region_id,
			'receiver_address' => $user_address->address,
			'payment' => $payment,
			'post_fee' => $post_fee,
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
				$sku_postage = $sku->postages()->findOrFail($sku_opt['postage_id']);
				$post_fee += $sku_postage->cost;
			}
			
			$order->skus()->create([
				'sku_id' => $sku->id,
				'sku_name' => $sku->sku_name,
				'thumb' => $sku->spu->thumb,
				'amount' => $amount,
				'price' => $sku->price,
				'total_price' => $amount * $sku->price,
			]);
		}
		
		$order->payment = $payment;
		$order->post_fee = $post_fee;
		if ($coupon_id) {
			if (UserCouponManager::canUseCoupon($user, $coupon_id, $order->payment)) {
				$payment = UserCouponManager::useCoupon($user, $coupon_id, $payment = $order->payment, $order->id);
				if ($payment) {
					$order->payment = $payment;
				}
			}
		}
		
		$order->save();
		return $order;
		
	}
}