<?php
/**
 * Created by PhpStorm.
 * User: Acer
 * Date: 2018/12/11
 * Time: 17:27
 */

namespace App\Components;


use App\Admin\Controllers\NativePlaceRegionController;
use App\Models\OrderPostage;
use App\Models\PostageRegions;
use Cblink\Region\RegionServiceProvider;

class PostageMananger
{
    static $codes = [
        "KYSY" => "跨越速运",
        "KYWL" => "跨越物流",
        "ANE" => "安能物流",
        "SF" => "顺丰速运",
        "HTKY" => "百世快递",
        "ZTO" => "中通快递",
        "STO" => "申通快递",
        "YTO" => "圆通速递",
        "YD" => "韵达速递",
        "YZPY" => "邮政快递包裹",
        "EMS" => "EMS",
        "HHTT" => "天天快递",
        "JD" => "京东快递",
        "UC" => "优速快递",
        "DBL" => "德邦快递",
        "ZJS" => "宅急送",
        "TNT" => "TNT快递",
        "UPS" => "UPS",
        "DHL" => "DHL",
        "FEDEX" => "FEDEX联邦(国内件）",
        "FEDEX_GJ" => "FEDEX联邦(国际件）"
    ];

    public static function getPostageFee($region_id)
    {
        $provience_id = NativePalceReagionManager::getProvienceId($region_id);
        $postage_region = PostageRegions::where('region_id', $provience_id)->first();
        if ($postage_region) {
            return $postage_region->postage->cost;
        } else {
            return false;
        }
    }

    public static function queryAll()
    {
        $order_postages = OrderPostage::query()->whereIn("status", ["0", "2"])->get();
        $wuliu = new WuliuManager();
        foreach ($order_postages as $order_postage) {
            $result = $wuliu->query($order_sn = $order_postage->order_id, $shipper_code = $order_postage->postage_name, $logistic_code = $order_postage->postage_code);
//            dd($result);
            $order_postage->update([
                "status" => $result->State,
                "data" => json_encode($result)
            ]);
        }
    }

    public static function query(OrderPostage $order_postage)
    {
        $wuliu = new WuliuManager();
        $result = $wuliu->query($order_sn = $order_postage->order_id, $shipper_code = $order_postage->postage_name, $logistic_code = $order_postage->postage_code);
//            dd($result);
        if ($result->Success == "true")
            $order_postage->update([
                "status" => $result->State,
                "data" => json_encode($result)
            ]);
        return $order_postage;
    }
}