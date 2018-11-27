<?php
/**
 * Created by PhpStorm.
 * User: Acer
 * Date: 2018/11/26
 * Time: 10:45
 */

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Http\Helpers\ApiResponse;
use App\Models\Favorite;
use App\Models\GoodsSPU;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class FavoriteController extends Controller
{
	const TYPE_TO_CLASS=[
		'goods'=>GoodsSPU::class
	];
	
	public static function myFavoriteSPU(){
		return Auth::user()->favorites->where('item_type',GoodsSPU::class);
	}
	
	public static function addFavorite(Request $request){
		$favorite = Favorite::query()->firstOrCreate([
			'user_id' => Auth::user()['id'],
			'item_id' => $request->item_id,
			'item_type' => self::TYPE_TO_CLASS[$request->item_type],
		]);
		if (!$request->cancle) {
			$favorite->save();
			$ret = '加入购物车成功';
		} else {
			$favorite->delete();
			$ret = '移出购物车成功';
		}
		return ApiResponse::makeResponse(true, $ret, ApiResponse::SUCCESS_CODE);
	}
}