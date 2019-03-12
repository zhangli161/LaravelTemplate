<?php
/**
 * Created by PhpStorm.
 * User: Acer
 * Date: 2018/11/8
 * Time: 14:34
 */

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Http\Helpers\ApiResponse;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function category()
    {
        $cates = $this->getCategory(0,'-----');
        array_unshift($cates, ['id' => 0, 'text' => '根类别']);
        return $cates;
    }

    private function getCategory($parentid, $pre = '             ')
    {
        $result = [];
        $categories = Category::where('parentid', $parentid)->orderBy('order')->get();
        foreach ($categories as $category) {
            array_push($result, ['id' => $category->id, 'text' => $pre . $category->name]);
            $result = array_merge($result, $this->getCategory($category->id, $pre . $pre));
        }
        return $result;
    }

    public static function getByParentid(Request $request)
    {
        if ($request->filled(['parentid'])) {
            $ret=Category::where("parentid",$request->get('parentid'))->get();
            foreach ($ret as $item){
                $item->image=getRealImageUrl($item->image);
                $item->icon=getRealImageUrl($item->icon);
            }
            return ApiResponse::makeResponse(true, $ret, ApiResponse::SUCCESS_CODE);
        } else {
            return ApiResponse::MissingParam();
        }
    }

}