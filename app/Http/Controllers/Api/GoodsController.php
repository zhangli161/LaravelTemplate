<?php
/**
 * Created by PhpStorm.
 * User: Acer
 * Date: 2018/11/23
 * Time: 14:20
 */

namespace App\Http\Controllers\Api;


use App\Components\GoodsSPUManager;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;

class GoodsController extends Controller
{
	public static function getList(){
		$goods=GoodsSPUManager::getList();
		foreach ($goods as $good){
			$good=GoodsSPUManager::getDetailsForApp($good);
		}
		return $goods;
	}
	
	public static function getById(Request $request){
		$spu=GoodsSPUManager::getById($request->spu_id);
		$spu=GoodsSPUManager::getDetailsForApp($spu);
		return $spu;
	}

}