<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGoodsBenefitsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('goods_benefits', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('sku_id', false, true);//skuid
			$table->string('title', 50)->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
			$table->string('desc', 255)->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
			$table->decimal('price')->comment('现价');
			$table->decimal('show_origin_price')->comment('原价');
			$table->decimal('origin_price')->comment('原价');
			$table->dateTime('time_form');
			$table->dateTime('time_to');
			$table->tinyInteger('reset', false, true)
				->comment('结束后恢复原价,0否1是')->default('1');
			$table->tinyInteger('status', false, false)
				->comment('状态,0未开始1进行中-1已过期')->default('0');
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
		Schema::dropIfExists('goods_benefits');
	}
}
