<?php
/**
 * Created by PhpStorm.
 * User: ieso
 * Date: 2018/12/25
 * Time: 9:15
 */

namespace App\Http\Controllers\api;

use App\Http\Helpers\ApiResponse;
use App\Models\UserAddress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;


class UserAddressController
{
    public static function my()
    {
        $user = Auth::user();
        $ret = $user->addresses()->with(['region','region_1','region_2'])->get();
        return ApiResponse::makeResponse(true, $ret, ApiResponse::SUCCESS_CODE);
    }

    public static function edit(Request $request)
    {
        $user = Auth::user();

        $address = $user->addresses()->findOrNew($request->get('id'));

//        UserAddress::
        $address->fill($request->all());
        $address->save();
        if ($request->get('is_main') == 1) {
            $others = $user->addresses()->where('id', "<>", $address->id)->get();
            foreach ($others as $other) {
                if ($other->is_main == 1)
                    $other->update(['is_main' => '0']);
            }
        }
        return ApiResponse::makeResponse(true, $address, ApiResponse::SUCCESS_CODE);
    }
}