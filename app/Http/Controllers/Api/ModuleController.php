<?php
/**
 * Created by PhpStorm.
 * User: ieso
 * Date: 2018/12/28
 * Time: 9:47
 */

namespace App\Http\Controllers\api;


use App\Components\GoodsSKUManager;
use App\Http\Helpers\ApiResponse;
use App\Models\Module;

class ModuleController
{
    public static function getList()
    {
        $modules = Module::with("skus")->orderBy('order','desc')->get();
        foreach ($modules as $module) {
            foreach ($module->skus as $sku) {
                $sku = GoodsSKUManager::getDetailsForApp($sku, true);
            }
        }
        return ApiResponse::makeResponse(true, $modules, ApiResponse::SUCCESS_CODE);
    }
}