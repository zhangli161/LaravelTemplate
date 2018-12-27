<?php
/**
 * Created by PhpStorm.
 * User: Acer
 * Date: 2018/11/26
 * Time: 10:45
 */

namespace App\Http\Controllers\Api;


use App\Components\GoodsSKUManager;
use App\Components\GoodsSPUManager;
use App\Http\Controllers\Controller;
use App\Http\Helpers\ApiResponse;
use App\Models\Favorite;
use App\Models\GoodsSKU;
use App\Models\GoodsSPU;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    const TYPE_TO_CLASS = [
        'goods' => GoodsSKU::class
    ];

    public static function myFavoriteSPU()
    {
        $datas = Auth::user()->favorites()->where('item_type', self::TYPE_TO_CLASS['goods'])->paginate();
        foreach ($datas as $data)
            $data->item = GoodsSKUManager::getDetailsForApp($data->item);
        return ApiResponse::makeResponse(true, $datas, ApiResponse::SUCCESS_CODE);
    }

    public static function add(Request $request)
    {
        if ($request->filled(['item_id', 'item_type'])) {
            $favorite = Favorite::query()->firstOrNew([
                'user_id' => Auth::user()['id'],
                'item_id' => $request->get('item_id'),
                'item_type' => self::TYPE_TO_CLASS[$request->get('item_type')],
            ]);
            if (!$request->has('cancle')) {
                $favorite->save();
                $ret = '加入收藏成功';
            } else {
                $favorite->delete();
                $ret = '移出收藏成功';
            }
            return ApiResponse::makeResponse(true, $ret, ApiResponse::SUCCESS_CODE);
        } else
            return ApiResponse::makeResponse(false, "缺少参数", ApiResponse::MISSING_PARAM);

    }
}