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
use App\Components\WXPayManager;
use App\Http\Helpers\ApiResponse;
use App\Http\Helpers\SnowFlakeIDWorker;
use App\Models\Agent;
use App\Models\GoodsSKU;
use App\Models\Message;
use App\Models\MessageContent;
use App\Models\NativePlaceRegion;
use App\Models\Order;
use App\Models\Postage;
use App\Models\UserCoupon;
use App\User;
use Encore\Admin\Auth\Database\Administrator;
use Encore\Admin\Facades\Admin;
use Illuminate\Http\Request;

class DemoController extends Controller
{
//	static $worker=SnowFlakeIDWorker(1);
    public static function test()
    {
        $mchid = env("WX_MCH_ID");
        $appid = env("WX_APP_ID");
        $apiKey = env("WX_API_KEY");
        $wxPay = new WXPayManager();
        //4200000214201812297536195648
        $result = $wxPay->refund(1, 1, "3", "4200000214201812297536195648", "XCX_3007");
        dd($result);
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