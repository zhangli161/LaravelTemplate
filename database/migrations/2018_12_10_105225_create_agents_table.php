<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAgentsTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('agents', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('admin_id', false, true);
			$table->string("real_name", 50)->collation('utf8mb4_unicode_ci')->comment("真实姓名");
			$table->tinyInteger("gender")->comment("性别 0男 1女");
			$table->string("telephone", 50)->comment("电话号码");
			$table->string("address", 100)->collation('utf8mb4_unicode_ci')->comment("地址");
			$table->integer('region_id', false, true);
			$table->string("wx", 100)->comment("微信号")->nullable();
			$table->string("qq", 100)->comment("QQ")->nullable();
			$table->string("email", 100)->comment("E-mail")->nullable();
			$table->string("business", 100)->collation('utf8mb4_unicode_ci')->comment("行业")->nullable();
			$table->text("store")->collation('utf8mb4_unicode_ci')->comment("门店信息，json")->nullable();
			$table->string("xcx_qr", 255)->collation('utf8mb4_unicode_ci')->comment("微信小程序二维码地址")->nullable();
			$table->decimal("balance")->comment("分销收益")->default(0);
			$table->tinyInteger('status',false,true)->comment("0封禁 1正常使用")->default(0);
			
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
		Schema::dropIfExists('agents');
	}
}
