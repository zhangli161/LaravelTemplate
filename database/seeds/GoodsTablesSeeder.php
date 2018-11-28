<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class GoodsTablesSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		for ($i = 0; $i < 10; $i++) {
			$spuid=$i + 1;
			DB::table('goods_spu')->insert([
				'id' => $spuid,
				'spu_no' => 10000 + $spuid,
				'spu_name' => '示例spu' . $spuid,
				'desc' => '描述',
				'status' => '1',
				'thumb' => 'https://timgsa.baidu.com/timg?image&quality=80&size=b9999_10000&sec=1542696681074&di=edf9b3e94186cb281ca2beedda5f038d&imgtype=0&src=http%3A%2F%2Fpic19.photophoto.cn%2F20110403%2F0037037049381747_b.jpg',
				'view' => '0',
				'sell' => '0',
				'cate_id' => rand(1, 5),
			]);
			DB::table('goods_spu_spec')->insert([
				'spu_id' =>$spuid,
				'spec_id' => '1',
			]);
			$sku1_id=$i * 2 + 1;
			$sku1_price=rand(1000,2000)/100;
			DB::table('goods_sku')->insert([
				'id' => $sku1_id,
				'sku_no' => (10000+$i)*100+$sku1_id,
				'sku_name' => "示例sku{$sku1_id}（黑色）",
				'price' => 30,
				'stock' => '10000',
				'shop_id' => '0',
				'spu_id' => $spuid,
				'stock_type' => '0',
				'postage' => '0',
				'order' => '0',
			]);
			DB::table('goods_sku_spec_value')->insert([
				'sku_id' => $sku1_id,
				'spec_id' => '1',
				'spec_value_id' => '1',
			]);
			DB::table('goods_benefits')->insert([
				'sku_id' => $sku1_id,
				'title' => "促销活动",
				'desc' => '描述',
				'price' => $sku1_price,
				'origin_price' => 30,
				'time_form' => \Carbon\Carbon::tomorrow(),
				'time_to' => \Carbon\Carbon::create(2100,1,1),
				'reset' => 1,
			]);
			$sku2_id=$i * 2 + 2;
			DB::table('goods_sku')->insert([
				'id' => $sku2_id,
				'sku_no' => (10000+$i)*100+$sku2_id,
				'sku_name' =>"示例sku{$sku2_id}（白色）",
				'price' => '100',
				'stock' => '10000',
				'shop_id' => '0',
				'spu_id' => $spuid,
				'stock_type' => '0',
				'postage' => '0',
				'order' => '0',
			]);
			DB::table('goods_sku_spec_value')->insert([
				'sku_id' => $i * 2 + 2,
				'spec_id' => '1',
				'spec_value_id' => '2',
			]);
		}
		DB::table('category')->insert([
			'name' => 'LED灯丝灯',
			'parentid' => '0',
		]);
		DB::table('category')->insert([
			'name' => 'SMD灯泡',
			'parentid' => '0',
		]);
		DB::table('category')->insert([
			'name' => '装饰性大灯泡',
			'parentid' => '0',
		]);
		DB::table('category')->insert([
			'name' => '吊线灯头',
			'parentid' => '0',
		]);
		DB::table('rich_texts')->insert([
			"content" => "<p>精品吊灯</p>",
			"item_id" => 1,
			"item_type" => "App\Models\GoodsSPU",
		]);
		DB::table('goods_spec')->insert([
			'id' => 1,
			'spec_no' => '0001',
			'spec_name' => '颜色',
		]);
		DB::table('goods_spec_value')->insert([
			'id' => 1,
			'spec_id' => '1',
			'value' => '黑色',
		
		]);
		DB::table('goods_spec_value')->insert([
			'id' => 2,
			'spec_id' => '1',
			'value' => '白色',
		]);
	}
}
