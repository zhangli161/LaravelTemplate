<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStatisticOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('statistic_orders', function (Blueprint $table) {
            $table->increments('id');
	        $table->tinyInteger('type')->comment("0每日统计 2每月统计 3每年统计");
	        $table->date('date')->comment("统计日期");
	        $table->integer("region_id",false,true)->default(0)->comment("统计地区");
	        $table->integer("orders_count",false,true)->default(0)->comment("订单数量");
	        $table->decimal("orders_total_payment")->default(0)->comment("订单总金额");
	        
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
        Schema::dropIfExists('statistic_orders');
    }
}
