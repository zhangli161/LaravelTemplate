<?php
/**
 * Created by PhpStorm.
 * User: Acer
 * Date: 2018/11/29
 * Time: 13:41
 */

namespace App\Http\Controllers\Api;


use App\Components\OrderManager;
use App\Components\UserCouponManager;
use App\Http\Controllers\Controller;
use App\Http\Controllers\PayController;
use App\Http\Helpers\ApiResponse;
use App\Models\GoodsSKU;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class OrderController extends Controller
{
    public static function settlement(Request $request)
    {
        if ($request->filled(['skus'])) {
            $sku_opts = $request->get("skus");
//			dd( $sku_opts);
//			return;
            $user = Auth::user();

            $order = OrderManager::settlement(
                $user,
                $sku_opts,
                false,
                $request->get("user_address_id"),
                $request->get("coupon_id"),
                $request->get("buyer_message")
            );

            $coupons = $user->coupons;
            foreach ($coupons as $coupon) {
                $coupon->can_use = UserCouponManager::canUseCoupon($user, $coupon->id, $order->payment);
            }
//			$order->skus;
            return ApiResponse::makeResponse(true, [
                "order" => $order,
                "address" => $user->addresses()->orderBy('is_main', 'desc')->get(),
                "user_coupons" => $coupons
            ], ApiResponse::SUCCESS_CODE);
        } else {
            return ApiResponse::MissingParam();
        }
    }

    public static function create(Request $request)
    {
        if ($request->filled(['skus', 'user_address_id'])) {
            $sku_opts = $request->get("skus");
            $user = Auth::user();

            $order = OrderManager::newOrder(
                $user,
                $sku_opts,
                $request->get("user_address_id"),
                $request->get("coupon_id"),
                $request->get("buyer_message")
            );
            $order->skus;
            return ApiResponse::makeResponse(true, $order, ApiResponse::SUCCESS_CODE);
        } else {
            return ApiResponse::MissingParam();
        }
    }

    public static function my(Request $request)
    {
        $datas = Auth::user()->orders;
        return ApiResponse::makeResponse(true, $datas, ApiResponse::SUCCESS_CODE);

    }

    public static function getById(Request $request)
    {
        $order = Auth::user()->orders()->with(["skus", "wuliu", "xcx_pay"])->findOrFail($request->get('id'));
        return ApiResponse::makeResponse(true, $order, ApiResponse::SUCCESS_CODE);
    }

    public static function pay(Request $request)
    {
        $data = new PayController();
        $order = Order::findOrFail($request->get("order_id"));
        if (!$order->xcx_pay) {
            $ret = $data->unifiedOrder([
                'out_trade_no' => "XCX_" . $order->id,           // 订单号
                'total_fee' => 1,//$order->payment*100,              // 订单金额，**单位：分**
                'body' => '测试订单',                   // 订单描述
                'openid' => $order->user->WX->openId               // 支付人的 openID
            ]);
            if (array_key_exists("nonce_str", $ret) &&
                array_key_exists("sign", $ret) &&
                array_key_exists("prepay_id", $ret)
            ) {
                $order->xcx_pay()->create([
                    "nonce_str" => $ret["nonce_str"],
                    "sign" => $ret["sign"],
                    "out_trade_no" => "XCX_" . $order->id,
                    "total_fee" => $order->payment,
                    "prepay_id" => $ret["prepay_id"]
                ]);

                $timestamp = time();
                $nonce_str = $ret["nonce_str"];
                $prepay_id = $ret["prepay_id"];
                $key = env('WX_API_KEY');
                $paySign = MD5("appId={$ret['appid']}&nonceStr={$nonce_str}&package=prepay_id={$prepay_id}&signType=MD5&timeStamp={$timestamp}&key={$key}");
                $return = [
                    "timeStamp" => "" . $timestamp,
                    "nonceStr" => $ret["nonce_str"],
                    "package" => 'prepay_id=' . $ret["prepay_id"],
                    "signType" => "MD5",
                    "paySign" => $paySign
                ];
                return ApiResponse::makeResponse(true, $return, ApiResponse::SUCCESS_CODE);
            }
            return ApiResponse::makeResponse(false, $ret, ApiResponse::UNKNOW_ERROR);
        } else {
            $xcx_pay = $order->xcx_pay;
            $appid = env("WX_APP_ID");
            $timestamp = time();
            $nonce_str = $xcx_pay->nonce_str;
            $prepay_id = $xcx_pay->prepay_id;
            $key = env('WX_API_KEY');
            $paySign = MD5("appId={$appid}&nonceStr={$nonce_str}&package=prepay_id={$prepay_id}&signType=MD5&timeStamp={$timestamp}&key={$key}");
            $return = [
                "timeStamp" => "" . $timestamp,
                "nonceStr" => $nonce_str,
                "package" => 'prepay_id=' . $prepay_id,
                "signType" => "MD5",
                "paySign" => $paySign
            ];
            return ApiResponse::makeResponse(true, $return, ApiResponse::SUCCESS_CODE);
        }
    }

    public static function notify()
    {
        $pay_c = new PayController();
        $data = $pay_c->getNotifyData();
        Log::info("支付回调信息：".json_encode($data));

        $pay_c->replyNotify();
    }
}