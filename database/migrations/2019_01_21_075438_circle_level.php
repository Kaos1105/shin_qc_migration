<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CircleLevel extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //3210
        Schema::create('circle_levels', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('promotion_circle_id'); 
            $table->unsignedInteger('member_id'); 
            $table->integer('axis_x_i');
            $table->integer('axis_x_ro');
            $table->integer('axis_x_ha');
            $table->integer('axis_x_ni');
            $table->integer('axis_x_ho');
            $table->integer('axis_y_i');
            $table->integer('axis_y_ro');
            $table->integer('axis_y_ha');
            $table->integer('axis_y_ni');
            $table->integer('axis_y_ho');

            $table->integer('display_order');
            $table->tinyInteger('statistic_classification');
            $table->tinyInteger('use_classification');
            $table->text('note')->nullable();
            $table->integer('created_by');
            $table->integer('updated_by');
            $table->timestamps(); //create column created_at and updated_at
            
            $table->foreign('promotion_circle_id')->references('id')->on('promotion_circles');
            $table->foreign('member_id')->references('id')->on('members');
        });    
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('level_circles');
    }
}
