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
			$table->string('desc', 255)->charset('utf8mb4')
				->collation('utf8mb4_unicode_ci')->comment("描述");
			$table->string('img_url', 255)->comment("图片地址");
			$table->integer('order')->comment("排序");
			$table->tinyInteger('status')->default(0)
				->comment('状态：1生效 0失效');
			$table->text('attr')->nullable()->charset('utf8mb4')
				->collation('utf8mb4_unicode_ci')->comment("属性");
			
			$table->timestamps();
			$table->softDeletes();
		});
		//2.分类表
		Schema::create('category', function (Blueprint $table) {
			$table->integer('id')->autoIncrement();
			$table->string('name', 255)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->comment("分类名称");
			$table->integer('order')->default(0)->comment("排序");
			$table->string('icon', 255)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->comment("图标")->nullable();
			$table->string('image', 255)->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->comment("图片")->nullable();
			
			$table->integer('parentid')->default(0)->comment("父类id");
			
			$table->timestamps();
			$table->softDeletes();
		});
		//3.消息表
		Schema::create('message', function (Blueprint $table) {
			$table->integer('id')->autoIncrement();
			$table->integer('content_id')->comment("关联message_content");;
			$table->text('attr')
				->charset('utf8mb4')->collation('utf8mb4_unicode_ci')
				->comment("属性");;
			$table->tinyInteger('status')
				->default(0)->comment('状态：1生效 0失效');
			$table->integer('form_user_id')
				->default(0)->comment("发送人id，0为系统消息");;
			$table->integer('to_user_id')->comment("收信人user_id");
			
			$table->timestamps();
			$table->softDeletes();
		});
		//4.消息内容表
		Schema::create('message_content', function (Blueprint $table) {
			$table->integer('id')->autoIncrement();
			$table->string('title',128)
				->charset('utf8mb4')->collation('utf8mb4_unicode_ci')
				->comment("标题");;
			$table->string('send_type')
				->default(0)->comment("发送类型 0：指定用户 1：群发");
			$table->integer('source_id')->comment("消息源id");
			$table->text('attr')
				->nullable()->charset('utf8mb4')->collation('utf8mb4_unicode_ci')
				->comment("属性");
			
			$table->timestamps();
			$table->softDeletes();
		});
		//5.消息来源表
		Schema::create('message_source', function (Blueprint $table) {
			$table->integer('id')->autoIncrement();
			$table->string('name',128)
				->charset('utf8mb4')->collation('utf8mb4_unicode_ci')->comment("来源名称");
			$table->string('code')->default(0)->comment("英文简称");
			
			$table->timestamps();
			$table->softDeletes();
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
			$table->string('name',55)
				->charset('utf8mb4')->collation('utf8mb4_unicode_ci')
				->comment("收件人姓名");
			$table->integer('user_id')->comment("用户id");
			$table->integer('region_id')->comment("地区code");
			$table->integer('region_id_1')->comment("省code");
			$table->integer('region_id_2')->comment("市code");
			$table->string('address',255)
				->charset('utf8mb4')->collation('utf8mb4_unicode_ci')
				->comment("详细地址");
			$table->string('mobile',20)->comment("手机号码");
			$table->tinyInteger("is_main")->comment("是否为默认地址")->default(0);
			
			$table->timestamps();
			$table->softDeletes();
		});
		//8.用户绑定微信表
		Schema::create('users_wx', function (Blueprint $table) {
			$table->integer('user_id');
			
			$table->string('openId',100)->comment("小程序openid");
			$table->string('unionId',100)->nullable()->comment("微信开放平台unionid");
			$table->string('nickName',100)->nullable()
				->charset('utf8mb4')->collation('utf8mb4_unicode_ci')
				->comment("昵称");
			$table->tinyInteger('gender',false,true)
				->nullable()->comment("性别");
			$table->string('city',55)->nullable()
				->charset('utf8mb4')->collation('utf8mb4_unicode_ci')
				->comment("城市");
			$table->string('province',55)->nullable()
				->charset('utf8mb4')->collation('utf8mb4_unicode_ci')
				->comment("省份");
			$table->string('country',55)->nullable()
				->charset('utf8mb4')->collation('utf8mb4_unicode_ci')
				->comment("国家");
			$table->string('avatarUrl',255)->nullable()
				->comment("头像");
			
			$table->timestamps();
			$table->softDeletes();
			
			$table->primary('user_id');
		});
		//9.手机验证码表
		Schema::create('vertify', function (Blueprint $table) {
			$table->integer('id')->autoIncrement();
			$table->string('mobile',20)
				->comment("手机号");
			$table->string('code',55)
				->comment("验证码");
			$table->tinyInteger('status')->default(0)->comment('状态：1生效 0失效');
			
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
		Schema::dropIfExists('banner');
		Schema::dropIfExists('category');
		Schema::dropIfExists('message');
		Schema::dropIfExists('message_content');
		Schema::dropIfExists('message_source');
		Schema::dropIfExists('users_address');
		Schema::dropIfExists('users_wx');
		Schema::dropIfExists('vertify');
	}
}
