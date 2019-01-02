<?php
/**
 * Created by PhpStorm.
 * User: Acer
 * Date: 2018/11/27
 * Time: 17:07
 */

namespace App\Http\Controllers\Api;


use App\Components\UserCouponManager;
use App\Components\UserCreditManager;
use App\Http\Helpers\ApiResponse;
use App\Models\Coupon;
use App\Models\CouponDistributeMethod;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class CouponController
{
	public function getList()
	{
		$coupons = CouponDistributeMethod::query()->where('method', 1)->paginate();
//		$coupons=Coupon::whereIn('id',$coupon_ids)->paginate();
		foreach ($coupons as $coupon)
			$coupon->coupon;
		return ApiResponse::makeResponse(true, $coupons, ApiResponse::SUCCESS_CODE);
	}
	
	public static function buy(Request $request)
	{
		$method = CouponDistributeMethod::findOrFail($request->get('id'));
		$result = false;
//		return dd(UserCouponManager::canBuyCoupon(Auth::user(), $method));
		
		if (UserCouponManager::canBuyCoupon(Auth::user(), $method)) {
			$result = UserCouponManager::buyCoupon(Auth::user(), $method);
		}
		return ApiResponse::makeResponse($result,
			$result ? "购买成功" : "购买失败",
			$result ? ApiResponse::SUCCESS_CODE : ApiResponse::UNKNOW_ERROR
		);
	}
	
	public static function myCoupons(Request $request)
	{
		if ($request->orderBy)
			$coupons = Auth::user()->coupons()
                ->with('coupon')
				->orderby($request->orderBy)
				->paginate();
		else
			$coupons = Auth::user()->coupons()
				->paginate();
		foreach ($coupons as $coupon)
			$coupon->coupon;
		return ApiResponse::makeResponse(true, $coupons, ApiResponse::SUCCESS_CODE);
	}
	
}