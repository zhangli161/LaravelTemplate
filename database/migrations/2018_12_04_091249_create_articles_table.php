<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('cate_id',false,true);
            $table->string("title",255)->collation('utf8mb4_unicode_ci');
	        $table->string("desc",255)->collation('utf8mb4_unicode_ci')->nullable();
//	        $table->longText('content')->collation('utf8mb4_unicode_ci');
	        $table->string("author",100)->collation('utf8mb4_unicode_ci');
	        $table->integer('hits',false,true)->default(0);
			$table->tinyInteger('on_top')->default(0);
	        $table->string('thumb',255)->nullable();
	
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
        Schema::dropIfExists('articles');
    }
}
