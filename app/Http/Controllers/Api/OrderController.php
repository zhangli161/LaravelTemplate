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
		$user = Auth::user();
		if ($request->filled('skus')) {
			$sku_opts = json_decode($request->skus, true);
			if ($request->has('form_chart'))
				foreach ($sku_opts as $sku_opt) {
					$sku = $user->carts()->find($sku_opt['sku_id']);
					if ($sku) {
						$sku->amount -= $sku_opt['amount'];
						if ($sku->amount <= 0)
							$sku->delete();
						else
							$sku->save();
					}
				}
			
			$result = array();
			$result['addresses'] = Auth::user()->addresses;
			$result['skus'] = array();
			foreach ($sku_opts as $sku_opt) {
				$sku = GoodsSKU::findOrFail($sku_opt['sku_id']);
				if (!$sku->postage)
					$sku->postages;
				array_push($result['skus'], $sku);
			}
			
			return ApiResponse::makeResponse(true, $result, ApiResponse::SUCCESS_CODE);
		} else {
			return ApiResponse::MissingParam();
		}
	}
	
	public static function create(Request $request)
	{
		if ($request->filled(['skus', 'user_address_id'])) {
			$sku_opts = json_decode($request->skus, true);
			$user = Auth::user();
			
			$order = OrderManager::newOrder(
				$user,
				$sku_opts,
				$request->user_address_id,
				$request->coupon_id
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
}