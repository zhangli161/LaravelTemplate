<?php
/**
 * Created by PhpStorm.
 * User: Acer
 * Date: 2018/8/29
 * Time: 15:55
 */

namespace App\Components;


use App\Models\NativePlaceRegion;

class NativePalceReagionManager extends Manager
{
	
	protected static $keys = ['parentid', 'region_name', 'have_children', 'order'];
	
	protected static $primary_key = 'region_id';
	
	protected static $Modle = NativePlaceRegion::class;
	
	public static function getFullAddress($region_id, string $separator = '')
	{
		$t = self::getById($region_id);
		return $t ? self::getFullAddress($t->parentid, $separator) . $separator . $t->region_name : '';
	}
	
	public static function getByParentId($parentid)
	{
		$datas = self::getModle()->where('parentid', '=', $parentid)->get();
		return $datas;
	}
	
	public static function getProvienceId($region_id)
	{
		$t = self::getById($region_id);
		if ($t->parentid == '0') {
			return $t->region_id;
		} else {
			return self::getProvienceId($t->parentid);
		}
	}
	
	public static function getProviences(){
		$proviences=self::getByParentId(0);
		return $proviences;
	}
    public static function getProviencesAndCitys(){
        $regions=NativePlaceRegion::whereIn("have_children",[1,2])->orderBy('region_id')->get();
        return $regions;
    }
	public static function getChildren($region_ids){
//	    $region=NativePlaceRegion::findOrFail($region_id);
        if(gettype($region_ids)!=="array")
            $region_ids=array($region_ids);
	    $regions=NativePlaceRegion::query()
            ->whereIn("parentid",$region_ids)->get();
//	    return $regions->pluck('region_id')->toArray();
	    if (count($regions)>0){
	        return $regions->merge(self::getChildren($regions->pluck('region_id')->toArray()));
        }
        else{
	        return $regions;
        }
    }
}