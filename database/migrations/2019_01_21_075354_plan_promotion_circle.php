<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PlanPromotionCircle extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //3200
        Schema::create('promotion_circles', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('circle_id'); 
            $table->integer('year');
            $table->text('motto_of_the_workplace')->nullable();
            $table->text('motto_of_circle')->nullable();
            $table->float('axis_x')->nullable();
            $table->float('axis_y')->nullable();
            $table->integer('target_number_of_meeting')->nullable();
            $table->float('target_hour_of_meeting')->nullable();
            $table->integer('target_case_complete')->nullable();
            $table->integer('improved_cases')->nullable();
            $table->integer('objectives_of_organizing_classe')->nullable();
            $table->text('review_this_year')->nullable();
            $table->text('comment_promoter')->nullable();

            $table->integer('display_order')->nullable();
            $table->tinyInteger('statistic_classification')->nullable();
            $table->tinyInteger('use_classification')->nullable();
            $table->text('note')->nullable();
            $table->integer('created_by');
            $table->integer('updated_by');
            $table->timestamps(); //create column created_at and updated_at
            
            $table->foreign('circle_id')->references('id')->on('circles');
        });    
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('promotion_circles');
    }
}
