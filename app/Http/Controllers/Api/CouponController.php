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
use App\Models\CouponBenefit;
use App\Models\CouponDistributeMethod;
use App\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class CouponController
{
    public function getList()
    {
        $coupons = CouponDistributeMethod::query()
            ->where('method', 1)
            ->where("stock", "!=", "0")
            ->with("coupon")
            ->paginate();

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
        UserCouponManager::checkUserCoupon(Auth::user());
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

    public static function getBenefitById(Request $request)
    {
//        return 1;
        $coupon = CouponBenefit::query()
            ->with(["coupon", "content"])
            ->findOrFail($request->get("id"));

        if ($coupon->date_form > now() || $coupon->date_to < now())
            return ApiResponse::makeResponse(false, "不在活动时间内", ApiResponse::UNKNOW_ERROR);

        $coupon->can_get = Auth::user()->coupons()
                ->where("get_way", 1)->withTrashed()
                ->where("get_way_id", $request->get("id"))
                ->count() < $coupon->max_amount;
        return ApiResponse::makeResponse(true, $coupon, ApiResponse::SUCCESS_CODE);
    }

    public static function receiveBenefit(Request $request){
        $coupon = CouponBenefit::query()
            ->with(["coupon", "content"])
            ->findOrFail($request->get("id"));

        if ($coupon->date_form > now() || $coupon->date_to < now())
            return ApiResponse::makeResponse(false, "不在活动时间内", ApiResponse::UNKNOW_ERROR);

        $can_get = Auth::user()->coupons()
                ->where("get_way", 1)->withTrashed()
                ->where("get_way_id", $request->get("id"))
                ->count() < $coupon->max_amount;
        if ($can_get){
            UserCouponManager::benefitGetCoupon(Auth::user(),$coupon);
            return ApiResponse::makeResponse(true, "领取成功", ApiResponse::SUCCESS_CODE);
        }
        else{
            return ApiResponse::makeResponse(false, "超过领取限制", ApiResponse::UNKNOW_ERROR);
        }

    }
}