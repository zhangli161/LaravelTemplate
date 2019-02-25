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
use Encore\Admin\Layout\Content;
use Illuminate\Http\Request;

class DemoController extends Controller
{
//	static $worker=SnowFlakeIDWorker(1);
    public static function test(Request $request)
    {

//        $sku=GoodsSKU::firstOrCreate();
        $spu1 = GoodsSPU::create([
            'spu_no' => 20003,
            'spu_name' => '测试添加',
            'desc' => 'ffff',
            'thumb' => 'aaa',
            'cate_id' => 1,
            'status'=>1,
            'sences'=>[8,9]
        ]);
//        $spu2 = GoodsSPU::find('12')->update([
//            'spu_no' => 20000,
//            'spu_name' => '测试添加',
//            'desc' => 'ffff',
//            'thumb' => 'aaa',
//            'cate_id' => 1,
//            'status'=>1,
//            'sences'=>[['id'=>8]]
//        ]);

        dd($spu1->sences);
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