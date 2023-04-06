<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class NaviDetail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('navi_details', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('history_id');
            $table->integer('qa_id');

            $table->dateTime('date_answer');
            $table->integer('answer_id');

            $table->integer('created_by');
            $table->integer('updated_by');
            $table->timestamps(); //create column created_at and updated_at

            $table->foreign('history_id')->references('id')->on('navi_histories');

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
