<?php
/**
 * Created by PhpStorm.
 * User: ieso
 * Date: 2018/12/21
 * Time: 9:40
 */

namespace App\Http\Controllers\Api;


use App\Http\Helpers\ApiResponse;
use App\Models\Article;
use Illuminate\Http\Request;

class ArticleController
{
    public static function getByCategory(Request $request)
    {
        if ($request->filled('cate_id')) {
            $ret = Article::query()->where("cate_id", $request->get("cate_id"))->orderBy("order", 'desc')->paginate();
            foreach ($ret as $item)
                $item->thumb=getRealImageUrl($item->thumb);
            return ApiResponse::makeResponse(true, $ret, ApiResponse::SUCCESS_CODE);
        } else
            return ApiResponse::makeResponse(false, "缺少参数", ApiResponse::MISSING_PARAM);
    }

    public static function getOneByCategory(Request $request)
    {
        if ($request->filled('cate_id')) {
            $query = Article::query()->where("cate_id", $request->get("cate_id"))->with("content");
            if ($request->filled('skip')) {
                $query->skip($request->get("skip"));
            }
            $ret = $query->first();
            $ret->thumb=getRealImageUrl($ret->thumb);
            return ApiResponse::makeResponse(true, $ret, ApiResponse::SUCCESS_CODE);
        } else
            return ApiResponse::makeResponse(false, "缺少参数", ApiResponse::MISSING_PARAM);
    }

    public static function getById(Request $request)
    {
        if ($request->filled(['id'])) {
            $ret = Article::findOrFail($request->get("id"));
            $ret->thumb=getRealImageUrl($ret->thumb);

            return ApiResponse::makeResponse(true, $ret, ApiResponse::SUCCESS_CODE);
        } else
            return ApiResponse::makeResponse(false, "缺少参数", ApiResponse::MISSING_PARAM);
    }
}