<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Homepage extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //9400
        Schema::create('homepages', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title', 100);
            $table->text('classification')->nullable();
            $table->text('url');
            $table->dateTime('date_start')->nullable();
            $table->dateTime('date_end')->nullable();
            $table->tinyInteger('is_display');
            
            $table->integer('display_order');
            $table->tinyInteger('use_classification');
            $table->text('note')->nullable();
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
        Schema::dropIfExists('homepages');
    }
}
