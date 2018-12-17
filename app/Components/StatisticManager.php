<?php
/**
 * Created by PhpStorm.
 * User: Acer
 * Date: 2018/12/13
 * Time: 15:44
 */

namespace App\Components;


use App\Models\Order;
use App\Models\StatisticOrder;
use Carbon\Carbon;

class StatisticManager
{
	public static function order(string $date_str = "Y-m-d")
	{
		$date = date($date_str);
		//获得当日完成的订单
		$orders = Order::whereDate('completed_at', $date)->get();
		$records = [0 => ["orders_count" => 0, "orders_total_payment" => 0.00]];
		foreach ($orders as $order) {
			$prov_id = NativePalceReagionManager::getProvienceId($order->receiver_region_id);
			$record = array_key_exists($prov_id, $records) ? $records[$prov_id] :
				["orders_count" => 0, "orders_total_payment" => 0.00];
//			return $record["orders_count"];
			
			$record["orders_count"] += 1;
			$record["orders_total_payment"] += $order->payment;
			$records[0]["orders_count"] += 1;
			$records[0]["orders_total_payment"] += $order->payment;
			
			$records[$prov_id] = $record;
		}
		foreach ($records as $region_id => $record)
			StatisticOrder::query()->insert([
				[
					'date' => $date,
					"region_id" => $region_id,
					'orders_count' => $record["orders_count"],
					'orders_total_payment' => $record["orders_total_payment"]
				],
			]);
		
		return $records;
	}
	
}