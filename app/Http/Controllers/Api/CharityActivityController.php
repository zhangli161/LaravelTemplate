<?php
/**
 * Created by PhpStorm.
 * User: ieso
 * Date: 2019/3/8
 * Time: 10:07
 */

namespace App\Http\Controllers\api;




use App\Http\Helpers\ApiResponse;
use App\Models\CharityActivity;
use Illuminate\Http\Request;

class CharityActivityController
{
    public function getById(Request$request){
        $id=$request->get('id');

        $data=CharityActivity::findOrFail($id);

        return ApiResponse::makeResponse(true,$data);
    }

    public function donate(Request$request){
        $id=$request->get('id');

        $data=CharityActivity::query()->findOrFail($id);

        $amount=$request->get('amount',0);
        $data->increment('now',$amount);
        return ApiResponse::makeResponse(true,"捐款 $amount 元成功！感谢您的支持");
    }
}