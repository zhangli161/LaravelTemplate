<?php
/**
 * Created by PhpStorm.
 * User: Acer
 * Date: 2018/8/29
 * Time: 16:08
 */

namespace App\Http\Controllers;


use App\Components\AdminManager;
use App\Components\AgentManager;
use App\Components\MessageManager;
use App\Components\NativePalceReagionManager;
use App\Components\OrderManager;
use App\Components\PostageMananger;
use App\Components\QRManager;
use App\Components\StatisticManager;
use App\Components\TemplateManager;
use App\Components\UserCreditManager;
use App\Components\WuliuManager;
use App\Components\WXPayManager;
use App\Http\Helpers\ApiResponse;
use App\Http\Helpers\SnowFlakeIDWorker;
use App\Models\Agent;
use App\Models\AgentCash;
use App\Models\GoodsSKU;
use App\Models\GoodsSPU;
use App\Models\Message;
use App\Models\MessageContent;
use App\Models\NativePlaceRegion;
use App\Models\Order;
use App\Models\OrderPostage;
use App\Models\Postage;
use App\Models\UserCoupon;
use App\User;
use Carbon\Carbon;
use Encore\Admin\Auth\Database\Administrator;
use Encore\Admin\Facades\Admin;
use Illuminate\Http\Request;

class DemoController extends Controller
{
//	static $worker=SnowFlakeIDWorker(1);
    public static function test()
    {
        $order = Order::
        where('status', '5')-> //只寻找交易成功的订单
        findOrFail(270);
        if (!$order)
            return ApiResponse::makeResponse(false, "订单不存在或未完成", ApiResponse::UNKNOW_ERROR);
        $order_sku = $order->skus()->findOrFail(276);
        if ($order_sku==null)
            return ApiResponse::makeResponse(false, "订单中不存在该商品", ApiResponse::UNKNOW_ERROR);
        if ($order_sku->refund_amount >= $order_sku->amount)
            return ApiResponse::makeResponse(false, "商品已退货", ApiResponse::UNKNOW_ERROR);
        if ($order_sku->is_buyer_rated != 0)
            return ApiResponse::makeResponse(false, "已经评论过了", ApiResponse::UNKNOW_ERROR);

    }

    //Manager的用法
    public function test1()
    {
        $mgr = new TemplateManager();

        $template = $mgr->createObject();
        $template = $mgr->set($template, ['value' => 'aaaaa']);
        $template->save();
        return $mgr->getList();
    }


}