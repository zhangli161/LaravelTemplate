<?php
/**
 * Created by PhpStorm.
 * User: Acer
 * Date: 2018/11/29
 * Time: 13:41
 */

namespace App\Http\Controllers\Api;


use App\Components\OrderManager;
use App\Http\Controllers\Controller;
use App\Http\Helpers\ApiResponse;
use App\Models\GoodsSKU;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrderController extends Controller
{
	public static function settlement(Request $request)
	{
		if ($request->filled(['skus'])) {
			$sku_opts = $request->get("skus");
//			dd( $sku_opts);
//			return;
			$user = Auth::user();
			
			$order = OrderManager::settlement(
				$user,
				$sku_opts,
				$request->get("user_address_id"),
				$request->get("coupon_id"),
				$request->get("buyer_message")
			);
//			$order->skus;
			return ApiResponse::makeResponse(true, $order, ApiResponse::SUCCESS_CODE);
		} else {
			return ApiResponse::MissingParam();
		}
	}
	
	public static function create(Request $request)
	{
		if ($request->filled(['skus', 'user_address_id'])) {
			$sku_opts = $request->get("skus");
			$user = Auth::user();
			
			$order = OrderManager::newOrder(
				$user,
				$sku_opts,
				$request->get("user_address_id"),
				$request->get("coupon_id"),
				$request->get("buyer_message")
			);
			$order->skus;
			return ApiResponse::makeResponse(true, $order, ApiResponse::SUCCESS_CODE);
		} else {
			return ApiResponse::MissingParam();
		}
	}
	
	public static function my(Request $request)
	{
		$datas = Auth::user()->orders;
		return ApiResponse::makeResponse(true, $datas, ApiResponse::SUCCESS_CODE);
		
	}
	
	public static function getById(Request $request){
		$order=Auth::user()->orders()->findOrFail($request->get('id'));
		return ApiResponse::makeResponse(true, $order, ApiResponse::SUCCESS_CODE);
	}
}