<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class QaQuestion extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('qa_questions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('qa_id');
            $table->tinyInteger('screen_classification');
            $table->mediumText('content');
            $table->string('file_name', 100)->nullable();
            $table->integer('file_size');
            $table->tinyInteger('alignment')->nullable();
            $table->integer('height')->nullable();
            $table->integer('length')->nullable();
            $table->mediumText('comment');

            $table->integer('display_order');
            $table->integer('created_by');
            $table->integer('updated_by');
            $table->timestamps(); //create column created_at and updated_at

            $table->foreign('qa_id')->references('id')->on('qas');

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
