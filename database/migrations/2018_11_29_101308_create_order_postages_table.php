<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderPostagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_postages', function (Blueprint $table) {
	        $table->integer('order_id',false,true);
	        $table->string('postage_name',20)->comment("快递名称")->nullable();
	        $table->string('postage_code',50)->comment("快递单号")->nullable();
//	        $table->integer('postage_id',false,true);
	        
	        $table->timestamps();
	        $table->primary('order_id');
	
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('order_postages');
    }
}
