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
use App\Models\Coupon;
use App\Models\CouponDistributeMethod;
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
use Encore\Admin\Layout\Content;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class DemoController extends Controller
{
//	static $worker=SnowFlakeIDWorker(1);
    public static function test(Request $request)
    {
        $agent = Agent::find(6);
        $oldData=$agent->toJson();
        AgentManager::makeFinance($agent,1,0,"测试");
        $agent = Agent::find(6);

        dd($agent,$oldData);
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