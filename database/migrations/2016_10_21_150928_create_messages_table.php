<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMessagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('messages', function (Blueprint $table) {
            //$table->integer('id');
			$table->integer('sent_to');
			$table->integer('project_id');
			$table->integer('sentby');
			$table->longText('message');
			$table->integer('readflag',1);
			$table->integer('sentflag',1);
			$table->integer('replyflag',1);
			$table->integer('deleteflag',1);
			$table->dateTime('created_at');
			$table->increments('id');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('messages');
    }
}
