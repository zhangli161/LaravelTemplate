<?php
/**
 * Created by PhpStorm.
 * User: Acer
 * Date: 2018/11/28
 * Time: 9:26
 */

namespace App\Components;


use App\Models\Coupon;
use App\Models\CouponDistributeMethod;
use App\Models\UserCoupon;
use App\User;
use Carbon\Carbon;

class UserCouponManager
{
	public static function canBuyCoupon(User $user, CouponDistributeMethod $coupon_DM)
	{
		$limit_per_user = $coupon_DM->limit_per_user;
		$cooldown = $coupon_DM->cooldown;
		$user_coupons = $user->coupons()->withTrashed()
			->where('coupon_id', $coupon_DM->coupon_id)->get();
		$lasttime = $user_coupons->max('created_at');
		if ($coupon_DM->method == 1 //校验能否购买
			and $coupon_DM->stock != 0 //校验库存
		)
			if ($user_coupons->count() == 0  //未领取该类型优惠券
				or (
					($limit_per_user == -1 or $user_coupons->count < $limit_per_user)//校验领取上限
					and
					(date(strtotime("$lasttime   +$cooldown   hour")) < time())//校验领取间隔
				)
			) {
				return true;
			} else {
				return '第二步校验';
			}
		else {
			return json_encode($coupon_DM);
		}
		return false;
	}
	
	public static function buyCoupon(User $user, $coupon_DM)
	{
		$coupon_id = $coupon_DM->coupon_id;
		$coupon = Coupon::findOrFail($coupon_id);
		$expiry_date = null;
		if ($coupon->expiry_date) {
			$expiry_date = $coupon->expiry_date;
		}
		if ($coupon->expriy_days) {
			$date_t = date('Y-m-d', strtotime("+$coupon->expriy_days days"));
			if (!$expiry_date or $expiry_date > $date_t) {
				$expiry_date = $date_t;
			}
		}
		$user_coupon = new UserCoupon([
			'user_id' => $user->id,
			'coupon_id' => $coupon_id,
			'expiry_date' => $expiry_date
		]);
		
		if (UserCreditManager::changeCredit($user, [
			'amount' => -$coupon_DM->price,
			'reason' => '购买优惠券',
			'note' => '优惠券id:' . $coupon_id,
			'editor' => 'user'
		])) {
			$user_coupon->save();
			return true;
		}
		return false;
	}
	
	public static function canUseCoupon(User $user, $user_coupon_id, $payment)
	{
		$user_coupon = $user->coupons()->find($user_coupon_id);
		if ($user_coupon) {//优惠券存在
			$expiry_date = strtotime($user_coupon->expiry_date . " +1 day");
			$today = time();
			if (strtotime($user_coupon->expiry_date) > $today) {//未失效
				if ($payment >= $user_coupon->coupon->min_cost) {//到达门槛价格
					return ["result" => true, "reson" => null];
				} else {
					return ["result" => false, "reson" => "未达到门槛价格"];
				}
			} else {
				return ["result" => false, "reson" => "优惠券已过期"];
			}
		}
		return ["result" => false, "reson" => "优惠券不存在"];
	}
	
	//返回打折后金额
	public static function useCoupon(User $user, $coupon_id, $payment, $order_id = null)
	{
		$user_coupon = $user->coupons()->find($coupon_id);
		$now = now();
		if ($user_coupon) {//优惠券存在
			switch ($user_coupon->coupon->type) {
				case '1'://打折
					$p = $user_coupon->coupon->value;
					$payment *= $p;
					$user_coupon->note = "【 $now 】:使用打折券进行折扣，比例 $p 。";
					if ($order_id) $user_coupon->note .= "订单号：$order_id";
					$user_coupon->save();
					$user_coupon->delete();
					break;
				case '2'://2代金
					$value = $user_coupon->coupon->value;
					$payment -= $value;
					$user_coupon->note = "【 $now 】:使用代金券进行折扣，金额 $value 。";
					if ($order_id) $user_coupon->note .= "订单号：$order_id";
					$user_coupon->save();
					$user_coupon->delete();
					break;
			}
		}
		return $payment;
	}
	
	public static function paymentAfterUsingCoupon(Coupon $coupon, $payment)
	{
//		$coupon = Coupon::find($coupon_id);
		$now = now();
		if ($coupon) {//优惠券存在
			switch ($coupon->type) {
				case '1'://打折
					$p = $coupon->value;
					$payment *= $p;
					break;
				case '2'://2代金
					$value = $coupon->value;
					$payment -= $value;
					break;
			}
		}
		return $payment;
	}
}