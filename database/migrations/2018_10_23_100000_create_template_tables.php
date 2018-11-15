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
		//轮播图
		Schema::create('banner', function (Blueprint $table) {
			$table->integer('id')->autoIncrement();
			$table->string('desc', 255)->charset('utf8_mb4');
			$table->string('img_url', 255);
			$table->integer('order');
			$table->tinyInteger('status')->default(0)->comment('状态：1生效 0失效');
			$table->text('attr')->nullable();
			
			$table->timestamps();
			$this->timestamp('deleted_at')->nullable();
		});
		//分类表
		Schema::create('category', function (Blueprint $table) {
			$table->integer('id')->autoIncrement();
			$table->string('name', 255)->charset('utf8_mb4');
			$table->integer('order');
			$table->integer('parentid');
			
			$table->timestamps();
			$this->timestamp('deleted_at')->nullable();
		});
		//消息表
		Schema::create('message', function (Blueprint $table) {
			$table->integer('id')->autoIncrement();
			$table->integer('content_id');
			$table->text('attr');
			$table->tinyInteger('status')->default(0)->comment('状态：1生效 0失效');
			$table->integer('form_user_id')->default(0);
			$table->integer('to_user_id');
			
			$table->timestamps();
			$this->timestamp('deleted_at')->nullable();
		});
		//消息内容表
		Schema::create('message_content', function (Blueprint $table) {
			$table->integer('id')->autoIncrement();
			$table->string('title',128);
			$table->longText('content');
			$table->string('send_type')->default(0);
			$table->integer('source_id');
			$table->text('attr')->nullable();
			
			$table->timestamps();
			$this->timestamp('deleted_at')->nullable();
		});
		//地区表
		Schema::create('native_place_region', function (Blueprint $table) {
			$table->integer('region_id', false, true);
			$table->integer('parentid', false, true);
			$table->string('region_name', 255);
			$table->tinyInteger('have_children', false, true);
			$table->integer('order', false, true);
			$table->primary('region_id');
		});
		//用户地址表
		Schema::create('user_address', function (Blueprint $table) {
			$table->integer('id')->autoIncrement();
			$table->string('name',55);
			$table->integer('user_id');
			$table->integer('region_id');
			$table->integer('region_id_1');
			$table->integer('region_id_2');
			$table->string('address',255);
			$table->string('mobile',20);
			
			$table->timestamps();
			$this->timestamp('deleted_at')->nullable();
		});
		//用户绑定微信表
		Schema::create('user_wx', function (Blueprint $table) {
			$table->integer('user_id');
			
			$table->string('openId',100);
			$table->string('unionId',100)->nullable();
			$table->string('nickName',100)->nullable();
			$table->tinyInteger('gender',false,true)->nullable();;
			$table->string('city',55)->nullable();
			$table->string('province',55)->nullable();
			$table->string('country',55)->nullable();
			$table->string('avatarUrl',255)->nullable();
			
			$table->timestamps();
			$this->timestamp('deleted_at')->nullable();
			
			$table->primary('user_id');
		});
		//手机验证码表
		Schema::create('vertify', function (Blueprint $table) {
			$table->integer('id')->autoIncrement();
			$table->string('mobile',20);
			$table->string('code',55);
			$table->tinyInteger('status')->default(0)->comment('状态：1生效 0失效');
			
			$table->timestamps();
			$this->timestamp('deleted_at')->nullable();
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
