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
            $table->string('name',50);
            $table->decimal('cost');
            $table->timestamps();
	        $table->timestamp('deleted_at')->nullable();
        });
	    Schema::create('goods_sku_postage', function (Blueprint $table) {
		    $table->increments('id');
		    $table->integer('sku_id',false,true);
		    $table->integer('postage_id',false,true);
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
        Schema::dropIfExists('postages');
    }
}
