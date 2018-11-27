<?php
/**
 * Created by PhpStorm.
 * User: Acer
 * Date: 2018/11/27
 * Time: 17:07
 */

namespace App\Http\Controllers\Api;


use App\Models\Coupon;
use App\Models\CouponDistributeMethod;

class CouponController
{
	public function getList()
	{
		$coupon_ids = CouponDistributeMethod::query()->where('method', 1)->pluck('id');
		$coupons=Coupon::whereIn('id',$coupon_ids)->paginate();
		return $coupons;
	}
	
	
	
}