<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {

            $table->increments('id');
            $table->decimal('star',8,1)->comment("星级 0-5");
            $table->tinyInteger('star_1')->comment("星级 0-5");
            $table->tinyInteger('star_2')->comment("星级 0-5");
            $table->tinyInteger('star_3')->comment("星级 0-5");
            $table->longText('content')->comment("内容")->nullable();
            $table->text('albums')->comment("图片")->nullable();
            $table->integer('spu_id', false, true);
            $table->integer('sku_id', false, true);
            $table->integer('order_sku_id', false, true);

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
        Schema::dropIfExists('comments');
    }
}
