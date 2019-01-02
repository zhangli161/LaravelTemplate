<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderAgentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order_agents', function (Blueprint $table) {
            $table->increments('id');
            $table->integer("order_id",false,true);
            $table->integer("agent_id",false,true);
            $table->tinyInteger("percent",false,true)->comment("分成比例 1-100");
            $table->decimal("payment")->comment("分成金额");
            $table->tinyInteger("status",false,true)->comment("状态 0不可提现 1可提现 2冻结（预留）");

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
        Schema::dropIfExists('order_agents');
    }
}
