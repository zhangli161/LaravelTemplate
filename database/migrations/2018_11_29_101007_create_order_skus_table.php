<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderSKUsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	    Schema::create('order_skus', function (Blueprint $table) {
		    $table->increments('id');
		    $table->integer('order_id',false,true);
		    $table->integer('sku_id',false,true);
		    $table->string('sku_name')->comment("商品名称");
		    $table->string('thumb',255)->comment("商品封面");
		    $table->integer('amount',false,true)
			    ->comment("数量");
		    $table->decimal('price')->comment("单价");
		    $table->decimal('total_price')->comment("总价");
		    $table->tinyInteger('is_buyer_rated',false,true)
			    ->comment("买家是否评价")->default(0);
		    $table->timestamps();
	    });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_s_k_us');
    }
}
