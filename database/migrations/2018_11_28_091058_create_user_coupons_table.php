<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_coupons', function (Blueprint $table) {
            $table->increments('id');
	        $table->integer('user_id',false,true);
	        $table->integer('coupon_id',false,true);
	        $table->date('expiry_date')->comment("有效期至");
	        $table->text('note')->nullable()->comment("备注");
            $table->decimal('payment')->comment("总优惠价格")->nullable();
            $table->tinyInteger("get_way")->comment("获取方式 0积分兑换 1活动领取")->default(0);
            $table->integer("get_way_id")->comment("获取方式id")->nullable();

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_coupons');
    }
}
