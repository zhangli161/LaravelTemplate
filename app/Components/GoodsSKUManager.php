<?php
/**
 * Created by PhpStorm.
 * User: Acer
 * Date: 2018/11/23
 * Time: 9:19
 */

namespace App\Components;


use App\Models\GoodsSKU;
use Illuminate\Support\Facades\Storage;

class GoodsSKUManager extends Manager
{
	protected static $keys = [];
	
	protected static $primary_key = 'id';
	
	protected static $Modle = GoodsSKU::class;
	
	public static function getDetailsForApp(GoodsSKU $sku, $getSPU = false)
	{
		if ($getSPU) {
			$sku->spu = GoodsSPUManager::getDetailsForApp($sku->spu);
		}
		$sku->benefits;
		$sku->benefit = $sku->benefits
			->where('status', '>', 0)->first();
//		$sku->postages;
		$pattern = array('/http:\/\//', '/https:\/\//');
		foreach ($sku->albums as $album) {
			$result = preg_match_all($pattern[0], $album->url, $m) || preg_match_all($pattern[1], $album->url, $m);
			if (!$result) {
				$album->url = Storage::disk('admin')->url($album->url);
			}
		};
		$sku = self::getSpecValuesStr($sku);
		
		return $sku;
	}
	
	public static function getSpecValuesStr(GoodsSKU $sku)
	{
		
		$strs = array();
		foreach ($sku->spec_values as $spec_value) {
			array_push($strs,
				$spec_value->spec->spec_name . ':' . $spec_value->value);
		}
		$sku->spec_value_strs = $strs;
		return $sku;
	}
}