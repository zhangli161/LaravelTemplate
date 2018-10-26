<?php
/**
 * Created by PhpStorm.
 * User: Acer
 * Date: 2018/8/29
 * Time: 16:08
 */

namespace App\Http\Controllers\Api;

use App\Components\NativePalceReagionManager;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class RegionController
{
	public function getByParentid(Request $request)
	{
		$parentid = $request->q or 0;
//		return $parentid;
		
		$datas = NativePalceReagionManager::getByParentId($parentid);
		$ret = array();
		foreach ($datas as $data) {
			array_push($ret, ['id' => $data->region_id, 'text' => $data->region_name]);
		}
		return $ret;
	}
	
	public function regions(Request $request)
	{
		$q = $request->get('q');
		
		$Modle = NativePalceReagionManager::getModle();
		$Q = $Modle->where('region_name', $q);
		$results = $Q->get();
		while ($results->count() > 0) {
			$t = new Collection();
			foreach ($results as $result) {
				if ($result->have_children == 1) {
					$Q->orWhere('parentid', $result->region_id);
					$t->concat($Modle->where('parentid', $result->region_id)->get());
				}
			}
			$result = $t;
		}
		
		
		return $Q->paginate(null, ['region_id as id', 'region_name as text']);
	}
}