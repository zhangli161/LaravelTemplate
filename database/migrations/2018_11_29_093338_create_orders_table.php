<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('orders', function (Blueprint $table) {
			$table->increments('id');
			$table->decimal('payment')->comment('实付商品金额');
			$table->tinyInteger('payment_type', false, true)
				->comment('付款方式 1在线支付 2货到付款')->default(1);
			$table->decimal('post_fee')->comment('邮费');
			$table->tinyInteger('status', false, true)
				->comment("状态：1未付款 2已付款 3未发货 4已发货 5交易成功 6交易关闭")
				->default(1);
			$table->timestamp('paid_at')->comment("付款时间")->nullable();
			$table->timestamp('consigned_at')->comment("发货时间")->nullable();
			$table->timestamp('completed_at')->comment("订单完成时间")->nullable();
			$table->timestamp('closed_at')->comment("订单关闭时间")->nullable();
			$table->integer('user_id', false, true);
			$table->string('receiver_name', 20)->comment('收件人姓名');
			$table->string('receiver_phone', 20)->comment('收件人电话');
			$table->integer('receiver_region_id', false, true)
				->comment('收件人地区编码');
			$table->string('receiver_address', 20)->comment('收件人详细地址');
			$table->string('buyer_message', 255)->comment("买家留言")->nullable();
			$table->string('buyer_nick', 50)->comment("买家昵称");
			
			
			$table->timestamps();
//			$table->primary('id');
		});
		
	}
	
	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('orders');
	}
}
