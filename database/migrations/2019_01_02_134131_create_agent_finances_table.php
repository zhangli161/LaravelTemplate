<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAgentFinancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agent_finances', function (Blueprint $table) {
            $table->increments('id');
            $table->decimal("income")->comment("收入 默认0")->default(0);
            $table->decimal("expenditure")->comment("提现 默认0")->default(0);
            $table->decimal("balance")->comment("余额");
            $table->text("note")->comment("备注")->nullable();
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
        Schema::dropIfExists('agent_finances');
    }
}
