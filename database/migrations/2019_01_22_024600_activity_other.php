<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ActivityOther extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //4200
        Schema::create('activity_others', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('activity_id'); 
            $table->unsignedInteger('theme_id'); 
            $table->text('content')->nullable();
            $table->float('time')->nullable();
            $table->integer('created_by');
            $table->integer('updated_by');
            $table->timestamps(); //create column created_at and updated_at
            
            $table->foreign('activity_id')->references('id')->on('activities');
            $table->foreign('theme_id')->references('id')->on('themes');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('activity_others');
    }
}
