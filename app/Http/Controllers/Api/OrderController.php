<?php
/**
 * Created by PhpStorm.
 * User: Acer
 * Date: 2018/11/29
 * Time: 13:41
 */

namespace App\Http\Controllers\Api;


use App\Components\NativePalceReagionManager;
use App\Components\OrderManager;
use App\Components\UserCouponManager;
use App\Http\Controllers\Controller;
use App\Http\Controllers\PayController;
use App\Http\Helpers\ApiResponse;
use App\Models\Comment;
use App\Models\GoodsSKU;
use App\Models\Order;
use App\Models\OrderRefund;
use App\Models\OrderSKU;
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

    public static function getCount()
    {
        $datas = Auth::user()->orders();

        $status_1_count = $datas->where('status', 1)->count();
        $status_2_3_4_count = $datas->whereIn('status', [2, 3, 4])->count();
        $status_5_ids = $datas->where('status', 1)->pluck("id");
        $commentable_count = OrderSKU::whereIn('order_id', $status_5_ids)->where("is_buyer_rated", 0)->count();
        $refund_count = OrderRefund::whereIn('order_id', $status_5_ids)->count();
        return ApiResponse::makeResponse(true, [$status_1_count, $status_2_3_4_count, $commentable_count, $refund_count], ApiResponse::SUCCESS_CODE);
    }

    public static function my(Request $request)
    {
        $query = Auth::user()->orders()->orderBy("created_at", 'desc')->with(["skus", "wuliu", "xcx_pay"]);
        if ($request->filled('status')) {
            $query->where("status", $request->get('status'));
        }
        $datas = $query->get();

        return ApiResponse::makeResponse(true, $datas, ApiResponse::SUCCESS_CODE);

    }

    public static function getById(Request $request)
    {
        $order = Auth::user()->orders()->with(["skus","coupon", "wuliu", "xcx_pay"])->findOrFail($request->get('id'));
        $order->region_str=NativePalceReagionManager::getFullAddress($order->receiver_region_id);
//        $order->region_str=NativePalceReagionManager::getFullAddress($order->receiver_region_id);

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

    public static function checkPayment(Request $request)
    {
        $data = new PayController();
        $order = Order::findOrFail($request->get("order_id"));

        $ret = OrderManager::check_pay($order);
        return ApiResponse::makeResponse(true, $ret, ApiResponse::SUCCESS_CODE);

    }

    public static function notify(Request $request)
    {
        $pay_c = new PayController();
        $data = $pay_c->getNotifyData();
        Log::info("支付回调信息：" . json_encode($data));

        $data1 = $request->all();
        Log::info("【 $data1 】");
        $pay_c->replyNotify();
    }

    public static function refund(Request $request)
    {
        if ($request->filled(['order_id', 'order_sku_id', "amount"])) {
            $order = Order::
//            where('status', '5')-> //只寻找交易成功的订单
            findOrFail($request->get("order_id"));
            if (!$order)
                return ApiResponse::makeResponse(false, "订单不存在或未完成", ApiResponse::UNKNOW_ERROR);
            $order_sku = $order->skus()->find($request->get("order_sku_id"));
            if (!$order_sku)
                return ApiResponse::makeResponse(false, "订单中不存在该商品", ApiResponse::UNKNOW_ERROR);
            if ($order_sku->refund_amount + $request->get("amount") > $order_sku->amount)
                return ApiResponse::makeResponse(false, "商品超过最大退换货次数", ApiResponse::UNKNOW_ERROR);
            $return = OrderManager::refund($order, $order_sku, $request->get("amount"));

//            $return = [$order, $order_sku];
            return ApiResponse::makeResponse(true, $return, ApiResponse::SUCCESS_CODE);
        } else {
            return ApiResponse::MissingParam();
        }

    }

    public static function comment(Request $request)
    {
        if ($request->filled(['order_id', 'order_sku_id', "content", 'star_1', 'star_2', 'star_3'])) {
            $order = Order::
            where('status', '5')-> //只寻找交易成功的订单
            findOrFail($request->get("order_id"));
            if (!$order)
                return ApiResponse::makeResponse(false, "订单不存在或未完成", ApiResponse::UNKNOW_ERROR);
            $order_sku = $order->skus()->find($request->get("order_sku_id"));
            if (!$order_sku)
                return ApiResponse::makeResponse(false, "订单中不存在该商品", ApiResponse::UNKNOW_ERROR);
            if ($order_sku->refund_amount >= $order_sku->amount)
                return ApiResponse::makeResponse(false, "商品已退货", ApiResponse::UNKNOW_ERROR);
            if ($order_sku->is_buyer_rated != 0)
                return ApiResponse::makeResponse(false, "已经评论过了", ApiResponse::UNKNOW_ERROR);

            $comment =
                new Comment([
                    'star_1' => $request->get('star_1'),
                    'star_2' => $request->get('star_2'),
                    'star_3' => $request->get('star_3'),
                    'content' => $request->get('content'),
                    'albums' => $request->filled('albums') ? $request->get('albums') : [],
                ]);
//            return $order_sku;
            $comment->star = ($request->get('star_1') + $request->get('star_2') + $request->get('star_3')) / 3.0;
            $comment->sku_id = $order_sku->sku_id;
            $comment->spu_id = $order_sku->sku->spu_id;
            $comment->order_sku_id = $order_sku->id;
            $comment->save();
            $order_sku->is_buyer_rated = 1;
            $order_sku->save();

            return ApiResponse::makeResponse(true, $comment, ApiResponse::SUCCESS_CODE);
        } else {
            return ApiResponse::MissingParam();
        }
    }

    //确认收货
    public static function confirm(Request $request)
    {
        $order = Auth::user()->orders()
            ->whereIn('status', [2, 3, 4])
            ->findOrFail($request->get('order_id'));
        $order = OrderManager::complete($order);

        return ApiResponse::makeResponse(true, $order, ApiResponse::SUCCESS_CODE);
    }
}