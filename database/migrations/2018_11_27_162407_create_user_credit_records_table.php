<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserCreditRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
	    Schema::create('user_credit_records', function (Blueprint $table) {
		    $table->increments('id');
		    $table->integer('user_id',false,true);
		    $table->decimal('amount')->comment('变动数额');
		    $table->decimal('balance')->comment('余额');
		    $table->string('reason',255)
			    ->charset('utf8mb4')->collation('utf8mb4_unicode_ci')
			    ->comment('变动原因');
		    $table->string('editor',55)
			    ->comment('编辑者');
		    $table->string('note',255)
			    ->charset('utf8mb4')->collation('utf8mb4_unicode_ci')
			    ->comment('备注')->nullable();
		
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
        Schema::dropIfExists('user_credit_records');
    }
}
