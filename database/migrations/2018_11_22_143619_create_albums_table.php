<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAlbumsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('albums', function (Blueprint $table) {
            $table->increments('id');
	        $table->tinyInteger('order',false,true)->default(0)->comment("排序");
//	        $table->integer('spu_id',false,true);
	        $table->integer('sku_id',false,true)->nullable()->comment("关联skuid");
	        $table->string('url',255)->comment("图片地址");//封面
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
        Schema::dropIfExists('albums');
    }
}
