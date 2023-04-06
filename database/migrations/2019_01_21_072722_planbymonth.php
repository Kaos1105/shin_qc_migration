<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Planbymonth extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //3110
        Schema::create('plan_by_months', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('plan_by_year_id'); 
            $table->tinyInteger('execution_order_no');
            $table->text('contents');
            $table->tinyInteger('month_start');
            $table->tinyInteger('month_end'); 
            $table->text('content_jan')->nullable();
            $table->text('content_feb')->nullable();
            $table->text('content_mar')->nullable();
            $table->text('content_apr')->nullable();
            $table->text('content_may')->nullable();
            $table->text('content_jun')->nullable();
            $table->text('content_jul')->nullable();
            $table->text('content_aug')->nullable();
            $table->text('content_sep')->nullable();
            $table->text('content_oct')->nullable();
            $table->text('content_nov')->nullable();
            $table->text('content_dec')->nullable();
            $table->text('in_charge')->nullable();

            $table->integer('display_order');
            $table->text('note')->nullable();
            $table->integer('created_by');
            $table->integer('updated_by');
            $table->timestamps(); //create column created_at and updated_at
            
            $table->foreign('plan_by_year_id')->references('id')->on('plan_by_years');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('plan_by_months');
    }
}
