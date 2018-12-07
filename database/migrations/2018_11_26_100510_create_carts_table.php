<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCartsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('carts', function (Blueprint $table) {
        	$table->increments('id');
	        $table->integer('user_id', false, true);//userid
	        $table->integer('spu_id', false, true);//spuid
	        $table->integer('sku_id', false, true);//skuid
	        $table->integer('shop_id', false, true)->default(0);//shopid
	        $table->integer('amount', false, true)->default(0)->comment("商品数量");//商品数量
				
	        $table->timestamps();
	        $table->softDeletes();
			
//	        $table->primary(['spu_id','sku_id','user_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('carts');
    }
}
