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
            $table->string("title",255)->collation('utf8mb4_unicode_ci')
	            ->comment("标题");
	        $table->string("desc",255)->collation('utf8mb4_unicode_ci')->nullable()
		        ->comment("描述");
//	        $table->longText('content')->collation('utf8mb4_unicode_ci');
	        $table->string("author",100)->collation('utf8mb4_unicode_ci')
		        ->comment("作者");
	        $table->integer('hits',false,true)->default(0)
		        ->comment("点击量");
			$table->integer('order')->default(0)->comment("排序 越大越靠前");
	        $table->string('thumb',255)->nullable()->comment("封面图片");
	
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
