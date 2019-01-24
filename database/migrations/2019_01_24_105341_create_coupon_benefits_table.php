<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCouponBenefitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupon_benefits', function (Blueprint $table) {
            $table->increments('id');
            $table->integer("coupon_id",false,true);
            $table->integer("max_amount")->comment("领取数量上限")->default(1);
            $table->dateTime("date_form");
            $table->dateTime("date_to");
            $table->text("message_image");

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
        Schema::dropIfExists('coupon_benefits');
    }
}
