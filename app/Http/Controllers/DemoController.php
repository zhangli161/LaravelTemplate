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
//        dd(getDatesBetween('2018-12-1','2018-12-31',0));
//        dd(getDatesBetween('2018-12-1','2018-12-31',1));
//        dd(getDatesBetween('2018-1-1','2018-12-31',2));
//        dd(getDatesBetween('2016-11-1','2018-11-31',3));
//        dd(getDatesBetween('2016-1-1','2018-12-31',4));
        dd(date('Y-m-d',strtotime('last Monday')));
//        return json_encode(count(AgentManager::getOrders(Agent::find(1))));
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