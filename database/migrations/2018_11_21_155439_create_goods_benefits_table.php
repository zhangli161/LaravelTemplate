<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGoodsBenefitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('goods_benefits', function (Blueprint $table) {
            $table->increments('id');
	        $table->integer('sku_id',false,true);//skuid
			$table->string('title',50)->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
	        $table->string('desc',255)->charset('utf8mb4')->collation('utf8mb4_unicode_ci');
	        $table->decimal('price')->comment('现价');
	        $table->decimal('origin_price')->comment('原价');
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
        Schema::dropIfExists('goods_benefits');
    }
}
