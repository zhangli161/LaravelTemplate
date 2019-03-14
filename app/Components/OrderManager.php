<?php
/**
 * Created by PhpStorm.
 * User: Acer
 * Date: 2018/11/29
 * Time: 10:56
 */

namespace App\Components;


use App\Http\Controllers\PayController;
use App\Http\Helpers\ApiResponse;
use App\Models\Agent;
use App\Models\GoodsSKU;
use App\Models\Order;
use App\Http\Helpers\SnowFlakeIDWorker;
use App\Models\OrderAgent;
use App\Models\OrderCoupon;
use App\Models\OrderRefund;
use App\Models\OrderSKU;
use App\Models\UserCoupon;
use App\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class OrderManager extends Manager
{
    protected static $keys = [];

    protected static $primary_key = 'id';

    protected static $Modle = Order::class;

    /**
     * 结算订单
     * @param User $user
     * @param array $sku_opts
     * @param bool $save
     * @param null $user_address_id
     * @param null $coupon_id
     * @param string $buyer_message
     * @param int $payment_type
     * @return Order
     */


    public static function settlement(User $user, array $sku_opts, $save = false, $user_address_id = null, $coupon_id = null, $buyer_message = "", $payment_type = 1)
    {
        if ($user_address_id)
            $user_address = $user->addresses()->findOrFail($user_address_id);
        $payment = 0.0;
        if (isset($user_address)) {
            $post_fee = PostageMananger::getPostageFee($user_address->region_id);
        } else {
            $post_fee = '请选择地址';
        }
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
        if ($save) {
            $order = Order::create($order_arr);
        } else {
            $order = new Order($order_arr);
            //预生成订单时创建数组
            $order->skus = array();
        }
        //以上为创建订单主体部分

        $canuseCoupon = true;//是否可以使用优惠券
        foreach ($sku_opts as $sku_opt) {
            $sku = GoodsSKU::with('benefits')->findOrFail($sku_opt['sku_id']);
            $amount = $sku_opt['amount'] or 1;
            $total_price = $amount * $sku->price;
            $payment += $total_price;

            $order_sku = new OrderSKU([
                'sku_id' => $sku->id,
                'sku_name' => $sku->sku_name,
                'thumb' => getRealImageUrl($sku->spu->thumb),
                'amount' => $amount,
                'price' => $sku->price,
                'total_price' => $total_price,
            ]);

            if ($sku->benefits()
                ->where('status', '>', 0)->exists()) {
                $canuseCoupon = false;//有特惠商品则不能用优惠券
            };
            if ($sku->postage == "1") {
                //包邮时所有的商品都包邮
                $postage = 1;
            }
            if ($save) {
                $order->skus()->associate($order_sku);
            } else {
                $skus = $order->skus;
                array_push($skus, $order_sku);
                $order->skus = $skus;
            }
        }
        //以上为循环添加所有的sku到订单内

        $order->payment = $payment;
        $order->post_fee = $postage == '0' ? $post_fee : 0;
        $order->postage = $postage;
        //以上为结算邮费


        if ($coupon_id && $canuseCoupon) {
            if (UserCouponManager::canUseCoupon($user, $coupon_id, $order->payment)["result"]) {
                //不保存的情况下结算优惠券，将不消耗优惠券
                if (!$save)
                    $payment = UserCouponManager::useCoupon($user, $coupon_id, $payment, $order->id,false);
                else
                    $payment = UserCouponManager::useCoupon($user, $coupon_id,  $payment, $order->id);

                // dd(1,$payment,$coupon_id,$canuseCoupon);
                if ($payment) {
                    //$order->coupon()->associate(new OrderCoupon([
                    //  "user_coupon_id" => $coupon_id,
                    //"pirce" => $order->payment - $payment
                    //]));
                    $t = $payment / $order->payment;
                    $order->payment = $payment >= 0 ? $payment : 0;
                    foreach ($order->skus as $order_sku) {
                        $order_sku->total_price *= $t;
                        $order_sku->average_price = $order_sku->total_price / $order_sku->amount;
                    }
                }
            }
        }

        if ($save) {
            $order->save();
        }
        $order->can_use_coupon = $canuseCoupon;
        $order->pay_value = (is_numeric($order->post_fee) ? $order->post_fee : 0)
            + $order->payment;
        return $order;
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
    public static function newOrder(User $user, array $sku_opts, $user_address_id, $coupon_id, $buyer_message = null, $payment_type = 1)
    {
        $user_address = $user->addresses()->findOrFail($user_address_id);
        $payment = 0.0;
        $post_fee = PostageMananger::getPostageFee($user_address->region_id);
        if ($post_fee==false){
            return null;
        }
        $postage = 0;

        $create = [
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
        ];
        if ($buyer_message) {
            $create['buyer_message'] = $buyer_message;
        }

        $order = Order::create($create);

        $order_skus = array();
        $canuseCoupon = true;//是否可以使用优惠券
        foreach ($sku_opts as $sku_opt) {
            $sku = GoodsSKU::with('benefits')->findOrFail($sku_opt['sku_id']);

            $amount = $sku_opt['amount'] or 1;
            $total_price = $amount * $sku->price;
            $payment += $total_price;
            $price=$sku->price;

            array_push($order_skus, [
                'sku_id' => $sku->id,
                'sku_name' => $sku->name,
                'thumb' => getRealImageUrl($sku->spu->thumb),
                'amount' => $amount,
                'price' => $sku->price,
                'total_price' => $total_price,
            ]);


            if ($sku->benefits()
                ->where('status', '>', 0)->exists()) {
                $canuseCoupon = false;//有特惠商品则不能用优惠券
                //存在特惠商品则以显示原价为原价
                $price=$sku->benefit = $sku->benefits
                    ->where('status', '>', 0)->first()->show_origin_price;
            };
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
                'thumb' => getRealImageUrl($sku->spu->thumb),
                'amount' => $amount,
                'price' => $price,
                'total_price' => $amount * $sku->price,
                'average_price' => $sku->price
            ]);

            //从购物车中移除对应产品
            $cart = $user->carts()->where("sku_id", $sku->id)->first();
            if ($cart)
                if ($cart->amount <= $amount) {
                    $cart->delete();
                } else {
                    $cart->amount -= $amount;
                    $cart->save();
                }

            if ($sku->stock_type == 1) {
                //有库存且商品上架
                if ($sku->stock > 0 && $sku->spu->status == "1") {
                    //下单计算销量
                    $sku->sell += $amount;
                    //下单减库存
                    $sku->stock -= $amount;
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

        if ($coupon_id and $canuseCoupon) {
            if (UserCouponManager::canUseCoupon($user, $coupon_id, $order->payment)["result"]) {
                $payment = UserCouponManager::useCoupon($user, $coupon_id, $payment = $order->payment, $order->id);
                $order->used_user_coupon_id = $coupon_id;

                if ($payment) {
                    $order->coupon()->create([
                        "user_coupon_id" => $coupon_id,
                        "pirce" => $order->payment - $payment
                    ]);

                    //
                    $t = $payment / $order->payment;
                    $order->payment = $payment >= 0 ? $payment : 0;
                    foreach ($order->skus as $order_sku) {
                        $order_sku->total_price *= $t;
                        $order_sku->average_price = $order_sku->total_price / $order_sku->amount;
                        $order_sku->save();
                    }
                }
            }
        }

        $order->save();
        $order->pay_value = (is_numeric($order->post_fee) ? $order->post_fee : 0)
            + $order->payment;
        return $order;
    }

    /**
     * 支付后对订单进行处理
     *
     * @param Order $order
     * @return mixed
     */

    public static function afterPaid(Order $order)
    {
        Log::info("$order->id 支付完成，进行后续流程");
        foreach ($order->skus as $order_sku) {
            $sku = GoodsSKU::find($order_sku->sku_id);
            if ($sku->stock_type == 0) {//付款减库存
                if ($sku->stock > 0) {
                    $sku->stock--;
                    $sku->save();
                    $order->status = 2;
                    $order->save();
                } else {
                    //库存不足
                    self::cancle($order);
                    //执行退款流程
                    self::refund($order, $order_sku, $order_sku->amount, "付款后库存不足");
                }
            }
        }

        //有代理商则进行分销程序
        if ($order->user()->has("agent")->exists()) {
            $agent = $order->user->agent;
            Log::info("$order->id 分销人为 $agent->id ,$agent->name");
            $percent = AgentManager::getRebateRate($agent);

            $order_agent = new OrderAgent();
            $order_agent->order_id = $order->id;
            $order_agent->agent_id = $agent->id;
            $order_agent->order_payment = $order->payment;
            $order_agent->percent = $percent;//固定5个点的分成
            $order_agent->payment = $order->payment * $percent / 100.0;

            $order_agent->save();
        }
        return [];
    }

    /**
     * 查询订单支付状态
     *
     * @param Order $order
     * @return bool
     */
    public static function check_pay(Order $order)
    {
        //查询订单支付状态
        $pay = new PayController();
        $ret = $pay->orderQuery("XCX_" . $order->id);
        $pay_update = [
            'openid' => array_get($ret, "openid"),
            'trade_state' => array_get($ret, "trade_state"),
            'trade_state_desc' => array_get($ret, "trade_state_desc"),
            "transaction_id" => array_get($ret, "transaction_id"),
        ];
        $result = array_get($ret, 'trade_state') == "SUCCESS";
        if ($result) {
            $order->status = 2;//已付款
            if (array_key_exists("total_fee", $ret))
                $pay_update['total_fee'] = array_get($ret, "total_fee");
            $order->paid_at = Carbon::now();
            $order->save();
            $order->xcx_pay()->update($pay_update);
            self::afterPaid($order);

        } else {
            $created_at = strtotime($order->created_at);
            if (time() - $created_at > 30 * 60) {
                self::cancle($order);//30分钟交易关闭
            }
        }
        $order->save();
        $order->xcx_pay()->update($pay_update);

//		return $ret;
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
        if ($order->wuliu()->doesntExist())
            return false;
        $postage = PostageMananger::query($order->wuliu);

        $result = $postage->status == "3" //已收货
            && (strtotime($order->updated_at) - time()) > 7 * 24 * 3600;//收货时间超过一星期
        if ($result) {
            $order = self::complete($order);
        };
        Log::info("查询物流,{$order->id}:" . json_encode($postage)."
        ".$order->wuliu);

        return $result;
    }

    public static function complete(Order $order)
    {
        $order->status = 5;//已完成
        $order->completed_at = now();
        $order->save();

        //计算销量
        foreach ($order->skus as $order_sku) {
            $sku = $order_sku->sku;
            if ($sku) {
                $spu = $sku->spu;
                $spu->increment('sell', $order_sku->amount);
            }
        }

        //结算佣金
        if ($order->order_agent()->exists()) {
            $order_agent = $order->order_agent;

            //佣金金额需要减掉订单退款
            $payment = $order->payment -
                $order->refund()->whereIn('status', ['1', '2', '3'])->sum('payment');

            //未处理退款信息变为已确认收货，无法退款
            $r_refunds = $order->refund()->where('status', '0')->get();
            foreach ($r_refunds as $r_refund) {
                $r_refund->update(["status" => 5]);
            }

            $order->order_agent()->update(['status' => 1, 'payment' => $payment]);//可提现
            $a_f = AgentManager::makeFinance(
                $order_agent->agent,
                (float)$order->order_agent->payment, 0,
                "订单完成获得佣金");

            $order_agent->agent->history_balance = (float)$order_agent->agent->balance + (float)$order->order_agent->payment;
            $order_agent->agent->save();
            Log::info("【 $a_f 】订单完成获得佣金:order_id $order->id |order_angent_id $order_agent->id");
        }

        //结算积分
        UserCreditManager::changeCredit($order->user, [
            'amount' => (int)$order->payment,
            'reason' => "消费 $order->payment 元，赠送积分",
            'note' => '订单号:' . $order->id,
            'editor' => 'system'
        ]);

        return $order;
    }

    /**
     * 检测所有订单的支付状态
     *
     */
    public static function check_pay_all()
    {
        Log::info(time() . "订单支付状态查询:");
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
        foreach ($skus as $order_sku) {
            $sku = GoodsSKU::find($order_sku->sku_id);
            if ($sku->stock_type == 1 ||
                ($sku->stock_type == 0 &&
                    ($order->status == 2 || $order->status == 3)
                )
            )//1.下单减库存的商品   2.付款减库存的商品，如果订单已付款未发货
            {
//                $sku->
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

    public static function refund(Order $order, OrderSKU $order_sku, int $amount, $reason = null, $desc = null, $albums = [])
    {
        $refund = $order->refund()->create([
            'order_sku_id' => $order_sku->id,
            'amount' => $amount,
            'reason' => $reason ? $reason : request('reason'),
            'desc' => $desc,
            'status' => 0,
            'payment' => $amount * $order_sku->average_price,
            'albums' => $albums,
            'note']);
        if ($refund) {
            $order_sku->increment('refund_amount', $amount);
        }
        return $refund;
    }

    public static function editRefund($refund_id, Order $order, OrderSKU $order_sku, int $amount, $reason = null, $albums = [])
    {
        $refund = $order->refund()->findOrFail($refund_id)->update([
            'order_sku_id' => $order_sku->id,
            'amount' => $amount,
            'reason' => $reason ? $reason : request('reason'),
            'status' => 0,
            'payment' => $amount * $order_sku->average_price,
            'albums' => $albums,
            'note']);
        if ($refund) {
            $order_sku->increment('refund_amount', $amount);
        }
        return $refund;
    }

    public static function doRefund($refund)
    {
        $refund = OrderRefund::query()->find($refund->id);
        $order = Order::with("xcx_pay")->find($refund->order_id);
        if (!$order)
            return "订单不存在";
        if (!$order->xcx_pay)
            return "订单未付款";
        Log::info("订单退款:" . "|" . $order->xcx_pay->total_fee . "|" .
            (int)($refund->payment * 100) . "|" .
            $refund->id . "|" .
            $order->xcx_pay->transaction_id . "|" .
            "XCX_$order->id ");

        $wxPay = new WXPayManager();
        //4200000214201812297536195648
        $result = $wxPay->refund(
            (int)$order->xcx_pay->total_fee,
            (int)($refund->payment * 100),
//            1,
            $refund->id,
            $order->xcx_pay->transaction_id,
            "XCX_$order->id "
        );

        $refund->update(['result' => json_encode($result)]);
        Log::info("退款结果" . json_encode($result));
    }
}