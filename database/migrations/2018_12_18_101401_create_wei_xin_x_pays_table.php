<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWeiXinXPaysTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('wx_xcx_pays', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('order_id', false, true);
			$table->string('prepay_id', 50)->comment("预生成订单id");
			$table->string('openid', 50)->nullable()->comment("支付者openid");
			$table->decimal('total_fee')->comment("总钱数（单位：分）")->nullable();
			$table->string('nonce_str', 50)
				->comment("随机字符串，长度为32个字符以下。");
			$table->string('sign', 50)
				->comment("签名");
			$table->string('out_trade_no', 50)
				->comment("商户订单号(前缀XCX_加order_id)");
            $table->string('transaction_id', 50)
                ->comment("微信订单号");
			$table->string('trade_state', 50)
				->comment("支付状态")->nullable()->default("NOTPAY");
			$table->string('trade_state_desc', 50)
				->comment("支付状态描述")->nullable()->default("订单未支付");
			$table->text('note', 50)
				->comment("备注")->nullable();
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
		Schema::dropIfExists('wx_xcx_pays');
	}
}
