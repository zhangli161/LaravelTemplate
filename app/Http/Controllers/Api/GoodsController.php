<?php
/**
 * Created by PhpStorm.
 * User: Acer
 * Date: 2018/11/23
 * Time: 14:20
 */

namespace App\Http\Controllers\Api;


use App\Components\GoodsSPUManager;
use App\Http\Helpers\ApiResponse;
use App\Models\Cart;
use App\Models\FootPrint;
use App\Models\GoodsSKU;
use App\Models\GoodsSKUSearchWord;
use App\Models\GoodsSPU;
use Carbon\Carbon;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GoodsController extends Controller
{
	public static function getList(Request $request)
	{
		if (gettype($request->get('orderby')) == 'array')
			$goods = GoodsSPUManager::getList($request->get('orderby'));
		else
			$goods = GoodsSPUManager::getList('price', 'asc', 'id', 'desc');
		foreach ($goods as $good) {
			$good = GoodsSPUManager::getDetailsForApp($good);
		}
		return ApiResponse::makeResponse(true, $goods, ApiResponse::SUCCESS_CODE);
	}
	
	public static function getById(Request $request)
	{
		if ($request->filled('spu_id')) {
			$spu = GoodsSPU::findOrFail($request->spu_id);
			$foot_print = FootPrint::query()->firstOrCreate([
				'user_id' => Auth::user()->id,
				'spu_id' => $spu->id
			]);
			$foot_print->updated_at=Carbon::now();
			$foot_print->save();
			$count = FootPrint::where('user_id', Auth::user()->id)->count();
			if ($count > 100) {
				FootPrint::where('user_id', Auth::user()->id)
					->orderBy('updated_at', 'asc')
					->first()->delete();
			}
			
			$spu->view++;
			$spu->save();
			$spu = GoodsSPUManager::getDetailsForApp($spu, $request->sku_id);
			
			return ApiResponse::makeResponse(true, $spu, ApiResponse::SUCCESS_CODE);
		} else
			return ApiResponse::MissingParam();
		
	}
	
	public static function search(Request $request)
	{
		if ($request->filled('search_words')) {
			$searchwords = explode(' ', $request->get('search_words'));
			$query = GoodsSKUSearchWord::query();
			foreach ($searchwords as $searchword) {
				$query->where('search_words', 'like', "%$searchword%");
			}
			$results = $query->paginate();
			foreach ($results as $result) {
				$result->spu = GoodsSPUManager::getDetailsForApp($result->sku->spu, $result->sku_id);
			}
			return ApiResponse::makeResponse(true, $results, ApiResponse::SUCCESS_CODE);
		} else
			return ApiResponse::makeResponse(false, "参数不足", ApiResponse::MISSING_PARAM);
	}
	
	public static function addToCart(Request $request)
	{
		if ($request->filled('sku_id')) {
			$sku = GoodsSKU::findOrFail($request->get('sku_id'));
			$spu = $sku->spu;
			$cart = Cart::query()->updateOrCreate([
				'user_id' => Auth::user()->id,
				'spu_id' => $spu->id,
				'sku_id' => $sku->id,
			], [
				'amount' => $request->filled('amount') ?
					$request->get('amount') : 1
			]);
			
			if ($request->has('remove'))
				$cart->delete();
			return ApiResponse::makeResponse(true, $cart, ApiResponse::SUCCESS_CODE);
		} else
			return ApiResponse::makeResponse(false, "参数不足", ApiResponse::MISSING_PARAM);
	}
	
	public static function footprint()
	{
		$footprints = FootPrint::where('user_id', Auth::user()->id)->paginate();
		foreach ($footprints as $footprint) {
			$footprint->spu;
			$footprint->spu = GoodsSPUManager::getDetailsForApp($footprint->spu);
		}
		return $footprints;
	}
}