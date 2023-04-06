<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class NaviHistory extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('navi_histories', function (Blueprint $table) {
            $table->increments('id');
            $table->dateTime('date_start');
            $table->integer('user_id');
            $table->integer('story_id');
            $table->integer('starting_qa');
            $table->integer('thread_id');
            $table->tinyInteger('done_status');

            $table->integer('created_by');
            $table->integer('updated_by');
            $table->timestamps(); //create column created_at and updated_at

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
