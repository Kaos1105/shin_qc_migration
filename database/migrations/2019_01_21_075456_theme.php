<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Theme extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //4100
        Schema::create('themes', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('circle_id'); 
            $table->string('theme_name', 100); 
            $table->string('value_property', 100); 
            $table->string('value_objective', 100); 
            $table->date('date_start'); 
            $table->date('date_expected_completion'); 
            $table->date('date_actual_completion')->nullable();
            $table->text('note')->nullable();
            $table->integer('created_by');
            $table->integer('updated_by');
            $table->timestamps();

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
        Schema::dropIfExists('themes');
    }
}
