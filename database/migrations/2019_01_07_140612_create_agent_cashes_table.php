<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAgentCashesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agent_cashes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer("agent_id",false,true);
            $table->integer("user_id",false,true);
            $table->integer("amount",false,true);
            $table->tinyInteger("status",false,true)
            ->comment("提现状态 0未执行 1成功 2失败")->default(0);
            $table->text("return")->comment("执行转账返回")->nullable();
            $table->string("note")->comment("备注")->nullable();

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
        Schema::dropIfExists('agent_cashes');
    }
}
