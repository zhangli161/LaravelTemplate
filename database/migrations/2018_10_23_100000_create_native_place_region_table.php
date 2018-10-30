<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNativePlaceRegionTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('NATIVE_PLACE_REGION ', function (Blueprint $table) {
			$table->integer('region_id', false, true);
			$table->integer('parentid', false, true);
			$table->string('region_name', 255);
			$table->tinyInteger('have_children', false, true);
			$table->integer('order', false, true);
			$table->primary(['region_id']);
		});
	}
	
	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('password_resets');
	}
}
