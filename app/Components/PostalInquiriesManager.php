<?php
/**
 * Created by PhpStorm.
 * User: Acer
 * Date: 2018/12/5
 * Time: 10:55
 */

namespace App\Components;

use App\Models\OrderPostage;

/**
 * Class PostalInquiriesManager
 * 快递信息查询Manager
 *
 * @package App\Components
 */
class PostalInquiriesManager
{
	/**
	 * 查询物流信息
	 *
	 * @param OrderPostage $orderPostage
	 * @return OrderPostage
	 */
	public static function inquire(OrderPostage $orderPostage)
	{
		if ($orderPostage->status == 2) {
		
		} else {
			//这里进行查询
			
			$orderPostage->status = 2;
			$orderPostage->save();
		}
		
		return $orderPostage;
	}
}