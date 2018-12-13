<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name',255)->nullable();
	        $table->tinyInteger('status')->default(1)->comment('状态：1正常 0封禁');
	        $table->string('password',255)->nullable();
	        $table->string('avatar',255)->nullable();
            $table->string('email',255)->nullable();
	        $table->timestamp('latest_login_time')->nullable();
	        $table->integer('agent_id',false,true)->nullable();
	        $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
