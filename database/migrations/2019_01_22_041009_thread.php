<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Thread extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('threads', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('category_id'); 
            $table->string('thread_name', 100); 
            $table->tinyInteger('is_display');
           
            $table->integer('display_order');
            $table->tinyInteger('statistic_classification');
            $table->tinyInteger('use_classification');
            $table->text('note')->nullable();
            $table->unsignedInteger('circle_id')->nullable();

            $table->integer('created_by');
            $table->integer('updated_by');
            $table->timestamps(); //create column created_at and updated_at
            
            $table->foreign('category_id')->references('id')->on('categories');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('threads');
    }
}
