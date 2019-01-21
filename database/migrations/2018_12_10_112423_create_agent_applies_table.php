<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAgentAppliesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agent_applies', function (Blueprint $table) {
	        $table->increments('id');
	        $table->integer('user_id', false, true);
	        $table->string("real_name", 50)->collation('utf8mb4_unicode_ci')->comment("真实姓名");
	        $table->tinyInteger("gender")->comment("性别 0男 1女");
	        $table->string("telephone", 50)->comment("电话号码");
	        $table->string("address", 100)->collation('utf8mb4_unicode_ci')->comment("地址");
            $table->integer('province_id', false, true)->nullable();
            $table->integer('city_id', false, true)->nullable();
            $table->integer('region_id', false, true);
            $table->integer('province_id', false, true)->nullable();
            $table->integer('city_id', false, true)->nullable();     $table->string("wx", 100)->comment("微信号")->nullable();
	        $table->string("qq", 100)->comment("QQ")->nullable();
	        $table->string("email", 100)->comment("E-mail")->nullable();
	        $table->string("business", 100)->collation('utf8mb4_unicode_ci')->comment("行业")->nullable();
	        $table->text("store")->collation('utf8mb4_unicode_ci')->comment("门店信息，json")->nullable();
			$table->tinyInteger('status',false,true)->comment("0未处理 1已通过 2未通过")->default(0);
	        $table->text("note")->collation('utf8mb4_unicode_ci')->comment("备注 ")->nullable();
	
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
        Schema::dropIfExists('agent_applies');
    }
}
