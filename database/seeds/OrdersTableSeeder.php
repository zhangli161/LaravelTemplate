<?php

use Illuminate\Database\Seeder;

class OrdersTableSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		for ($i = 1; $i <= 1000; $i++) {
			DB::table('orders')->insert([
//		        'id' => $spuid,
				'payment' => rand(100, 1000) / 10.0,
				'payment_type' => 1,
				'postage' => 1,
				"post_fee" => 0,
				"status" => 5,
				"completed_at" => \Carbon\Carbon::createFromTimestamp(1514736000 + rand(0, 31536000)),
				"user_id" => 1,
				"receiver_name" => "测试订单",
				"receiver_phone" => "测试订单",
				"receiver_address" => "测试订单",
				"receiver_region_id" => \App\Models\NativePlaceRegion::where('have_children','<>','1')->inRandomOrder()->first()->region_id,
				"buyer_nick"=>"测试"
			]);
		}
	}
}
