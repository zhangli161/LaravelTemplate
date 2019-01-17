<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderRefundsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_refunds', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order_id',false,true);
            $table->integer('order_sku_id',false,true);
            $table->integer('amount',false,true)->comment("退货数量");
            $table->string('reason')->comment("退款理由")->nullable();
            $table->string('desc')->comment("退款描述")->nullable();
            $table->integer('status',false,true)->comment("处理状态 0 未处理 1 已通过 2 退款中 3 退款完成 4驳回")->default(0);
            $table->decimal('payment')->comment("退款金额");
            $table->string('note',255)->comment("备注")->nullable();
            $table->string('result',255)->comment("退款结果")->nullable();

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
        Schema::dropIfExists('order_refunds');
    }
}
