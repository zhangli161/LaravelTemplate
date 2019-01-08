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
use Illuminate\Support\Facades\Log;

class GoodsController extends Controller
{
    public static function getList(Request $request)
    {
        $query = GoodsSPU::query()->where('status', 1);

//        $goods = GoodsSPUManager::getList(true, $request->get('orderby'));
//		else
//			$goods = GoodsSPUManager::getList(true,'price', 'asc', 'id', 'desc');
        if ($request->filled('cate_id')) {
            $query->where('cate_id', $request->get('cate_id'));
        }
        if ($request->filled('sence_cate_id')) {
            $query->where('sence_cate_id', $request->get('sence_cate_id'));
        }
        $goods = $query->get();
        foreach ($goods as $good) {
            $good = GoodsSPUManager::getDetailsForApp($good);

        }

//        dd($goods);

        if (gettype($request->get('orderby')) == 'array') {
            $orderby = $request->get('orderby');
            for ($i = 0; $i < (count($orderby) - 1); $i += 2) {
                if ($orderby[$i + 1] != "desc")
                    $goods=$goods->sortBy(function ($item, $key) use ($orderby, $i) {
                        Log::info("正排序："
                            .$item->main_sku."
                            ".$orderby[$i].
                            $item->main_sku->getAttributeValue("$orderby[$i]"));
                        return (int)$item->main_sku->getAttributeValue($orderby[$i]);
                    });
                else
                    $goods=$goods->sortByDesc(function ($item, $key) use ($orderby, $i) {
                        Log::info("倒叙排序："
                            .$item->main_sku."
                            ".$orderby[$i].
                            $item->main_sku->getAttributeValue("$orderby[$i]"));
                        return (int)$item->main_sku->getAttributeValue($orderby[$i]);
                    });
            }
//            dd($goods->pluck("main_sku.price"),$orderby,$goods->values()->all());
        } else {
            $goods->sortBy("id");
        }

        return ApiResponse::makeResponse(true, $goods->values()->all(), ApiResponse::SUCCESS_CODE);
    }

    public static function getById(Request $request)
    {
        if ($request->filled('spu_id')) {
            $spu = GoodsSPU::where('status', 1)->findOrFail($request->spu_id);
            $foot_print = FootPrint::query()->firstOrCreate([
                'user_id' => Auth::user()->id,
                'spu_id' => $spu->id
            ]);
            $foot_print->updated_at = Carbon::now();
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
//            if (gettype($request->get('orderby')) == 'array') {
//                $orderby = $request->get('orderby');
//                for ($i = 0; $i < (count($orderby) - 1); $i += 2) {
//                    $query
//                        ->orderby($orderby[$i], $orderby[$i + 1]);
//                }
//            } else {
//                $query->orderby("id", 'desc');
//            }
//        $goods = GoodsSPUManager::getList(true, $request->get('orderby'));
//		else
//			$goods = GoodsSPUManager::getList(true,'price', 'asc', 'id', 'desc');
//            $results = $query->with("sku")->get();
            if ($request->filled('cate_id')) {
                $spus = GoodsSPU::where("cate_id", $request->get('cate_id'))->where('status', 1)->with("skus")->get();
                $sku_ids = array();
                $spus->each(function ($spu) use ($sku_ids) {
                    array_push($sku_ids, $spu->skus->pluck('id'));
                });

                $query->whereIn('', $sku_ids);
            }
            if ($request->filled('sence_cate_id')) {
                $spus = GoodsSPU::where("sence_cate_id", $query->where('status', 1)->get('sence_cate_id'))->with("skus")->get();
                $sku_ids = array();
                $spus->each(function ($spu) use ($sku_ids) {
                    array_push($sku_ids, $spu->skus->pluck('id'));
                });
                $query->whereIn('', $sku_ids);
            }

            $results = $query->with("sku")->paginate();
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