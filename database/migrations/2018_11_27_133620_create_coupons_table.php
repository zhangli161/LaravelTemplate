<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCouponsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('coupons', function (Blueprint $table) {
			$table->increments('id');
			$table->string('name')->charset('utf8mb4')->collation('utf8mb4_unicode_ci')
				->comment('优惠券名称');
			$table->tinyInteger('type', false, true)
				->comment('优惠券类型 1打折券 2代金券');
			$table->decimal('value')->comment('打折比例/代金券金额');;
			$table->decimal('min_cost')->comment('门槛金额');
			$table->date('expiry_date')->comment('固定有效期(至该日23:59)')->nullable();
			$table->integer('expriy_days', false, true)
				->comment('动态有效期')->nullable();
			$table->timestamps();
		});
		
		Schema::create('coupon_skus', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('coupon_id', false, true);//spuid
			$table->integer('sku_id', false, true);//规格id
			
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
		Schema::dropIfExists('coupons');
	}
}
