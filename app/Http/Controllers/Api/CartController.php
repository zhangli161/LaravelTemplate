<?php
/**
 * Created by PhpStorm.
 * User: Acer
 * Date: 2018/11/26
 * Time: 10:34
 */

namespace App\Http\Controllers\Api;


use App\Components\GoodsSKUManager;
use App\Components\GoodsSPUManager;
use App\Http\Controllers\Controller;
use App\Http\Helpers\ApiResponse;
use App\Models\Cart;
use App\Models\GoodsSPU;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class CartController extends Controller
{
    public static function myCart(Request $request)
    {
        $carts = Auth::user()->carts;
        foreach ($carts as $cart) {
            $cart->spu = GoodsSPUManager::getDetailsForApp($cart->spu, $cart->sku_id);
            $cart->sku = GoodsSKUManager::getDetailsForApp($cart->sku);
        }
        return ApiResponse::makeResponse(true, $carts, ApiResponse::SUCCESS_CODE);
    }

    public static function addToCart(Request $request)
    {
        $cart = Cart::query()->firstOrCreate([
            'user_id' => Auth::user()['id'],
            'spu_id' => $request->spu_id,
            'sku_id' => $request->sku_id,
        ]);
        if (!$request->cancle) {
            $cart->count = $request->count;
            $cart->save();
            $ret = '加入购物车成功';
        } else {
            $cart->delete();
            $ret = '移出购物车成功';
        }
        return ApiResponse::makeResponse(true, $ret, ApiResponse::SUCCESS_CODE);

    }

    public static function cancle(Request $request)
    {
        if (gettype($request->filled('ids') == "array")) {
            $ids = $request->get('ids');

            foreach ($ids as $id) {
                $cart = Cart::find($id);
                $cart->delete();
            }
            return ApiResponse::makeResponse(true, "删除成功", ApiResponse::SUCCESS_CODE);

        } else
            return ApiResponse::makeResponse(false, "缺少参数或格式不正确", ApiResponse::MISSING_PARAM);

    }
}