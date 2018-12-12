<?php
/**
 * Created by PhpStorm.
 * User: Acer
 * Date: 2018/12/11
 * Time: 17:27
 */

namespace App\Components;


use App\Admin\Controllers\NativePlaceRegionController;
use App\Models\PostageRegions;
use Cblink\Region\RegionServiceProvider;

class PostageMananger
{
	public static function getPostageFee($region_id){
		$provience_id=NativePalceReagionManager::getProvienceId($region_id);
		$postage_region=PostageRegions::where('region_id',$provience_id)->first();
		if ($postage_region){
			return $postage_region->postage->cost;
		}
		else{
			return false;
		}
	}
}