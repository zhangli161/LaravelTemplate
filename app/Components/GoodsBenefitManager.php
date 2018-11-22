<?php
/**
 * Created by PhpStorm.
 * User: Acer
 * Date: 2018/11/22
 * Time: 10:13
 */

namespace App\Components;


use App\Models\GoodsBenefit;
use App\Models\GoodsSKU;

class GoodsBenefitManager extends Manager
{
	protected static $keys = [];
	
	protected static $primary_key = 'id';
	
	protected static $Modle = GoodsBenefit::class;
	
	public static function checkStatus()
	{
		$benefits = GoodsBenefit::query()->whereIn('status', ['0', '1'])->get();
		foreach ($benefits as $benefit) {
			//活动时间段内
			if ($benefit->time_form <= now() && $benefit->time_to > now()) {
				if ($benefit->status != 1) {
					$benefit->status = 1;
					$benefit->save();
				}
				$sku = GoodsSKU::find($benefit->sku_id);
				if ($sku->price != $benefit->price) {
					$sku->price = $benefit->price;
					$sku->save();
				}
			}
			//活动结束
			if ($benefit->time_to <= now()) {
				if ($benefit->status != -1) {
					$benefit->status = -1;
					$benefit->save();
				}
				$sku = GoodsSKU::find($benefit->sku_id);
				if ($sku->price != $benefit->origin_price && $benefit->reset == 1) {
					$sku->price = $benefit->origin_price;
					$sku->save();
				}
			}
		}
	}
}