<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTemplateTables extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//1.轮播图
		Schema::create('banner', function (Blueprint $table) {
			$table->integer('id')->autoIncrement();
			$table->string('desc', 255)->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
			$table->string('img_url', 255);
			$table->integer('order');
			$table->tinyInteger('status')->default(0)->comment('状态：1生效 0失效');
			$table->text('attr')->nullable()->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
			
			$table->timestamps();
			$table->timestamp('deleted_at')->nullable();
		});
		//2.分类表
		Schema::create('category', function (Blueprint $table) {
			$table->integer('id')->autoIncrement();
			$table->string('name', 255)->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
			$table->integer('order');
			$table->integer('parentid');
			
			$table->timestamps();
			$table->timestamp('deleted_at')->nullable();
		});
		//3.消息表
		Schema::create('message', function (Blueprint $table) {
			$table->integer('id')->autoIncrement();
			$table->integer('content_id');
			$table->text('attr')->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
			$table->tinyInteger('status')->default(0)->comment('状态：1生效 0失效');
			$table->integer('form_user_id')->default(0);
			$table->integer('to_user_id');
			
			$table->timestamps();
			$table->timestamp('deleted_at')->nullable();
		});
		//4.消息内容表
		Schema::create('message_content', function (Blueprint $table) {
			$table->integer('id')->autoIncrement();
			$table->string('title',128)->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
			$table->longText('content')->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
			$table->string('send_type')->default(0);
			$table->integer('source_id');
			$table->text('attr')->nullable()->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
			
			$table->timestamps();
			$table->timestamp('deleted_at')->nullable();
		});
		//5.消息来源表
		Schema::create('message_source', function (Blueprint $table) {
			$table->integer('id')->autoIncrement();
			$table->string('name',128)->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
			$table->string('code')->default(0);
			
			$table->timestamps();
			$table->timestamp('deleted_at')->nullable();
		});
		//6.地区表
//		Schema::create('native_place_region', function (Blueprint $table) {
//			$table->integer('region_id', false, true);
//			$table->integer('parentid', false, true);
//			$table->string('region_name', 255)->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
//			$table->tinyInteger('have_children', false, true);
//			$table->integer('order', false, true);
//			$table->primary('region_id');
//		});
		//7.用户地址表
		Schema::create('users_address', function (Blueprint $table) {
			$table->integer('id')->autoIncrement();
			$table->string('name',55)->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
			$table->integer('user_id');
			$table->integer('region_id');
			$table->integer('region_id_1');
			$table->integer('region_id_2');
			$table->string('address',255)->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
			$table->string('mobile',20);
			
			$table->timestamps();
			$table->timestamp('deleted_at')->nullable();
		});
		//8.用户绑定微信表
		Schema::create('users_wx', function (Blueprint $table) {
			$table->integer('user_id');
			
			$table->string('openId',100);
			$table->string('unionId',100)->nullable();
			$table->string('nickName',100)->nullable()->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
			$table->tinyInteger('gender',false,true)->nullable();;
			$table->string('city',55)->nullable()->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
			$table->string('province',55)->nullable()->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
			$table->string('country',55)->nullable()->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
			$table->string('avatarUrl',255)->nullable();
			
			$table->timestamps();
			$table->timestamp('deleted_at')->nullable();
			
			$table->primary('user_id');
		});
		//9手机验证码表
		Schema::create('vertify', function (Blueprint $table) {
			$table->integer('id')->autoIncrement();
			$table->string('mobile',20);
			$table->string('code',55);
			$table->tinyInteger('status')->default(0)->comment('状态：1生效 0失效');
			
			$table->timestamps();
			$table->timestamp('deleted_at')->nullable();
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
