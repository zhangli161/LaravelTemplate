<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PostageTablesSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		DB::table('postage')->insert([
			'id' => 1,
			'name' => '顺丰',
			'cost' => 20.00,
		]);
		DB::table('postage')->insert([
			'id' => 2,
			'name' => '韵达',
			'cost' => 10.00,
		]);
		DB::table('goods_sku_postage')->insert([
			'sku_id' => '1',
			'postage_id' => '1',
		]);
		DB::table('goods_sku_postage')->insert([
			'sku_id' => '1',
			'postage_id' => '2',
		]);
		DB::table('goods_sku_postage')->insert([
			'sku_id' => '2',
			'postage_id' => '1',
		]);
	}
}
