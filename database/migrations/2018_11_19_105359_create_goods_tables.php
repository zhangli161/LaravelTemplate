<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGoodsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
    	//商品SPU表
        Schema::create('goods_spu', function (Blueprint $table) {
            $table->increments('id');
	        $table->integer('spu_no',false,true)
		        ->comment("商品spu编号");//
	        $table->string('spu_name',100)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')
		        ->comment("商品名称");//
	        $table->text('desc')->charset('utf8mb4')->collation('utf8mb4_unicode_ci')
		        ->comment("描述");//
	        $table->tinyInteger('status')
		        ->comment("商品状态  0下架 1上架");//
	        $table->string('thumb',255)->comment("封面图片");//
	        $table->integer('view',false,true)->default(0)
		        ->comment("浏览量");//
	        $table->integer('sell',false,true)->default(0)
		        ->comment("销量");//
	        $table->integer('cate_id',false,true)
		        ->comment("分类id");//
	        
	        $table->timestamps();
	        $table->softDeletes();
	
	        $table->unique('spu_no');
        });
	    //商品SKU表
	    Schema::create('goods_sku', function (Blueprint $table) {
		    $table->increments('id');
		    $table->integer('sku_no',false,true)->nullable()
			    ->comment("商品sku编号");//
		    $table->string('sku_name',100)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->default('默认商品')
			    ->comment("商品名称");//
		    $table->decimal('price')->default(0)
			    ->comment("价格");//
		    $table->integer('stock',false,true)->default(0)
			    ->comment("库存");//
		    $table->integer('shop_id',false,true)->default(0)
			    ->comment("预留，店铺id。  0为自营");//
		    $table->integer('spu_id',false,true)
			    ->comment("spuid");//
		    $table->unsignedTinyInteger('stock_type',false)->default(0)
			    ->comment("减库存时间 0付款减库存1下单减库存");//
		    $table->tinyInteger('postage')->default(1)
			    ->comment("包邮  0否 1是");//
		    $table->unsignedTinyInteger('order',false)->default(0)
			    ->comment("排序，越大越靠前");//
		    
		    $table->timestamps();
		    $table->softDeletes();
		
		    $table->unique('sku_no');
	    });
	    //商品规格名称表
	    Schema::create('goods_spec', function (Blueprint $table) {
		    $table->increments('id');
		    $table->integer('spec_no',false,true)
			    ->comment("规格编号");//
		    $table->string('spec_name',50)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')
			    ->comment("规格名称");//
		    
		    $table->timestamps();
		    $table->softDeletes();
	    });
	    //商品规格值表
	    Schema::create('goods_spec_value', function (Blueprint $table) {
		    $table->increments('id');
		    $table->integer('spec_id',false,true)
			    ->comment("规格id");//
		    $table->string('value',50)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')
			    ->comment("规格值");//
		
		    $table->timestamps();
		    $table->softDeletes();
	    });
	    
	    //商品spu规格表
	    Schema::create('goods_spu_spec', function (Blueprint $table) {
		    $table->increments('id');
		    $table->integer('spu_id',false,true)
			    ->comment("spuid");//
		    $table->integer('spec_id',false,true)
			    ->comment("规格id");//
		    
		    $table->timestamps();
		    $table->softDeletes();
	    });
	    
	    //商品sku规格值表
	    Schema::create('goods_sku_spec_value', function (Blueprint $table) {
		    $table->increments('id');
		    $table->integer('sku_id',false,true)
			    ->comment("skuid");//
		    $table->integer('spec_id',false,true)
			    ->comment("规格id");//skuid
		    $table->integer('spec_value_id',false,true)
			    ->comment("规格值id");//
		
		    $table->timestamps();
		    $table->softDeletes();
	    });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('goods_spu');
	    Schema::dropIfExists('goods_sku');
	    Schema::dropIfExists('goods_spec');
	    Schema::dropIfExists('goods_spec_value');
	    Schema::dropIfExists('goods_spu_spec');
	    Schema::dropIfExists('goods_sku_spec_value');
    }
}
