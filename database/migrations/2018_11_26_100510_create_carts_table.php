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
	        $table->integer('user_id', false, true);//userid
	        $table->integer('spu_id', false, true);//spuid
	        $table->integer('sku_id', false, true);//skuid
	        $table->integer('shop_id', false, true)->default(0);//shopid
	        $table->integer('count', false, true)->default(1);//商品数量
				
	        $table->timestamps();
	        $table->timestamp('deleted_at')->nullable();
			
	        $table->primary(['spu_id','sku_id','user_id']);
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
