<?php

namespace App\Http\Controllers\Api;

use App\Http\Helpers\ApiResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class BannerController extends Controller
{
    public function __construct(\App\Models\Banner $datas)
    {
        $this->datas = $datas;
        $this->content = array();
    }

    public function getList()
    {
        $banners = $this->datas->where('status', '1')->get();
        $pattern = array('/http:\/\//', '/https:\/\//');
        foreach ($banners as $banner) {
            $result = preg_match_all($pattern[0], $banner->img_url, $m) || preg_match_all($pattern[1], $banner->image, $m);
            if (!$result) {
                $banner->img_url = $url =
//                    Storage::disk('admin')->url($banner->img_url);
                    env("APP_URL") . "/storage/admin/" . $banner->img_url;
            }
            $banner->result = [preg_match_all($pattern[0], $banner->image, $m),
                preg_match_all($pattern[1], $banner->image, $m)];
        }
        return ApiResponse::makeResponse(true, $banners, ApiResponse::SUCCESS_CODE);
    }
}
