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
	    DB::table('goods_spu')->insert([
		    'id' => 1,
		    'spu_no' => '0001',
		    'spu_name' =>'示例spu',
		    'desc'=>'描述',
		    'status'=>'1',
		    'thumb'=>'https://timgsa.baidu.com/timg?image&quality=80&size=b9999_10000&sec=1542696681074&di=edf9b3e94186cb281ca2beedda5f038d&imgtype=0&src=http%3A%2F%2Fpic19.photophoto.cn%2F20110403%2F0037037049381747_b.jpg',
		    'view'=>'0',
		    'sell'=>'0',
		    'cate_id'=>'0',
	    ]);
	    DB::table('goods_sku')->insert([
		    'id' => 1,
		    'sku_no' => '0001',
		    'sku_name' =>'示例sku（黑色）',
		    'price'=>'100',
		    'stock'=>'10000',
		    'shop_id'=>'0',
		    'spu_id'=>'1',
		    'stock_type'=>'0',
		    'postage'=>'0',
		    'order'=>'0',
	    ]);
	    DB::table('goods_sku')->insert([
		    'id' => 2,
		    'sku_no' => '0002',
		    'sku_name' =>'示例sku（白色）',
		    'price'=>'100',
		    'stock'=>'10000',
		    'shop_id'=>'0',
		    'spu_id'=>'1',
		    'stock_type'=>'0',
		    'postage'=>'0',
		    'order'=>'0',
	    ]);
	    DB::table('goods_spec')->insert([
		    'id' => 1,
		    'spec_no' => '0001',
		    'spec_name' =>'颜色',
	    ]);
	    DB::table('goods_spec_value')->insert([
		    'id' => 1,
		    'spec_id' => '1',
		    'value' =>'黑色',
		    
	    ]);
	    DB::table('goods_spec_value')->insert([
		    'id' => 2,
		    'spec_id' => '1',
		    'value' =>'白色',
	    ]);
	    DB::table('goods_spu_spec')->insert([
		    'spu_id' =>'1',
		    'spec_id' => '1',
	    ]);
	    DB::table('goods_sku_spec_value')->insert([
		    'sku_id' =>'1',
		    'spec_value_id' => '1',
	    ]);
	    DB::table('goods_sku_spec_value')->insert([
		    'sku_id' =>'2',
		    'spec_value_id' => '2',
	    ]);
    }
}
