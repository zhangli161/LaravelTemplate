<?php
/**
 * Created by PhpStorm.
 * User: Acer
 * Date: 2018/11/23
 * Time: 9:19
 */

namespace App\Components;


use App\Models\GoodsSPU;
use Hamcrest\Core\Set;

class GoodsSPUManager extends Manager
{
	protected static $keys = [];
	
	protected static $primary_key = 'id';
	
	protected static $Modle = GoodsSPU::class;
	
	public static function getDetailsForApp(GoodsSPU $spu, $skuid = null)
	{
		$spu->albums;
		$spu->detail;
		
		$spec_matrix = array();
		$spec_ids=$spu->specs->pluck('id');
		foreach ($spec_ids as $spec_id){
			foreach ($spec_ids as $other_spec_id){
			
			}
		}
		
		foreach ($spu->skus as $sku) {
			$sku = GoodsSKUManager::getDetailsForApp($sku);
			if (!$skuid) {
				$spu->main_sku = $sku;
			} elseif ($sku->id == $skuid) {
				if (!$spu->main_sku or $spu->main_sku->price > $sku->price) {
					$spu->main_sku = $sku;
				}
			}
		}
		return $spu;
	}
}