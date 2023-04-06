<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Department extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //2200
        Schema::create('departments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('department_name',100); 
            $table->unsignedInteger('bs_id');
            $table->unsignedInteger('sw_id');
            $table->integer('display_order');
            $table->tinyInteger('statistic_classification');
            $table->tinyInteger('use_classification');
            $table->text('note')->nullable();
            $table->integer('created_by');
            $table->integer('updated_by');
            $table->timestamps(); //create column created_at and updated_at
       
            $table->foreign('bs_id')->references('id')->on('users');
            $table->foreign('sw_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('departments');
    }
}
