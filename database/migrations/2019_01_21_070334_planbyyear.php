<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Planbyyear extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //3100
        Schema::create('plan_by_years', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('year');
            $table->text('vision')->nullable();
            $table->text('target')->nullable();
            $table->text('motto')->nullable();
            $table->string('prioritize_1', 100);
            $table->string('prioritize_2', 100)->nullable();
            $table->string('prioritize_3', 100)->nullable();
            $table->string('prioritize_4', 100)->nullable();
            $table->string('prioritize_5', 100)->nullable();
            $table->string('prioritize_6', 100)->nullable();
            $table->string('prioritize_7', 100)->nullable();
            $table->string('prioritize_8', 100)->nullable();
            $table->string('prioritize_9', 100)->nullable();
            $table->string('prioritize_10', 100)->nullable();
            $table->integer('meeting_times');   //so lan hop
            $table->integer('meeting_hour');   //so gio hop
            $table->integer('case_number_complete');  //
            $table->integer('case_number_improve');
            $table->integer('classes_organizing_objective');
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
        Schema::dropIfExists('plan_by_years');
    }
}
