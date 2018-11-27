<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCouponDistributeMethodsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('coupon_distribute_methods', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('coupon_id', false, true);
			$table->tinyInteger('method', false, true)
				->comment('发放方式 1积分兑换');
//			$table->text('attr')->comment('属性,json形式')->nullable();
			$table->decimal('price')->comment('价格')->default(0);
			$table->tinyInteger('send_message', false, true)
				->comment('发送系统消息 0不发送 1发送')->default(0);
			$table->integer('stock', false, false)
			->comment('库存 -1为无限');
			$table->integer('limit_per_user', false, false)
				->comment('用户个人领取数量上限 -1为无限');
			$table->integer('cooldown', false, true)
				->comment('用户个人领取间隔。单位小时');
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
		Schema::dropIfExists('coupon_distribute_methods');
	}
}
