<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCharityActivitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('charity_activities', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title',255);
            $table->text('content')->comment("活动内容");
            $table->decimal("goal")->comment("目标金额");
            $table->decimal("now")->comment("当前进度");
            $table->text('reciver')->comment("善款接收机构");
            $table->dateTime('date_to')->comment("结束时间");
            $table->tinyInteger("status")
                ->comment("0失效 1进行中 2已完成 3未完成")->default("1");
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
        Schema::dropIfExists('charity_activities');
    }
}
