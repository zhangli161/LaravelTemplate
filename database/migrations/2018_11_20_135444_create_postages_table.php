<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePostagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('postage', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name',50)->comment('名称');
            $table->decimal('cost')->comment('费用');
            $table->timestamps();
	        $table->softDeletes();
        });
//	    Schema::create('goods_sku_postage', function (Blueprint $table) {
//		    $table->increments('id');
//		    $table->integer('sku_id',false,true)->comment('sku_id');
//		    $table->integer('postage_id',false,true)->comment('邮递方式id');
//		    $table->timestamps();
//		    $table->softDeletes();
//	    });
	    Schema::create('postage_regions', function (Blueprint $table) {
		    $table->increments('id');
		    $table->integer('region_id',false,true)->comment('地区编码');
		    $table->integer('postage_id',false,true)->comment('邮递方式id');
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
        Schema::dropIfExists('postage');
	    Schema::dropIfExists('goods_sku_postage');
	
    }
}
