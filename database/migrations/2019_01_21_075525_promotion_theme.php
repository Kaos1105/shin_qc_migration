<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PromotionTheme extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //4110
        Schema::create('promotion_themes', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('theme_id'); 
            $table->integer('progression_category');
            $table->date('date_expected_start');
            $table->date('date_expected_completion');
            $table->date('date_actual_start')->nullable();
            $table->date('date_actual_completion')->nullable();

            $table->text('note')->nullable();
            $table->integer('created_by');
            $table->integer('updated_by');
            $table->timestamps();
            
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
        Schema::dropIfExists('promotion_themes');
    }
}
