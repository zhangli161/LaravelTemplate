<?php
/**
 * Created by PhpStorm.
 * User: Acer
 * Date: 2018/11/26
 * Time: 10:34
 */

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Http\Helpers\ApiResponse;
use App\Models\Cart;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class CartController extends Controller
{
	public static function myCart(Request $request)
	{
		return Auth::user()->carts;
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
}