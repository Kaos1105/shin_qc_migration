<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ActivityApproval extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //5100
        Schema::create('activity_approvals', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('promotion_circle_id'); 
            $table->integer('approver_classification'); 
            $table->unsignedInteger('user_approved')->nullable();
            $table->date('date_approved')->nullable();
            $table->date('date_jan')->nullable();
            $table->unsignedInteger('user_jan')->nullable();
            $table->date('date_feb')->nullable();
            $table->unsignedInteger('user_feb')->nullable();
            $table->date('date_mar')->nullable();
            $table->unsignedInteger('user_mar')->nullable();
            $table->date('date_apr')->nullable();
            $table->unsignedInteger('user_apr')->nullable();
            $table->date('date_may')->nullable();
            $table->unsignedInteger('user_may')->nullable();
            $table->date('date_jun')->nullable();
            $table->unsignedInteger('user_jun')->nullable();
            $table->date('date_jul')->nullable();
            $table->unsignedInteger('user_jul')->nullable();
            $table->date('date_aug')->nullable();
            $table->unsignedInteger('user_aug')->nullable();
            $table->date('date_sep')->nullable();
            $table->unsignedInteger('user_sep')->nullable();
            $table->date('date_oct')->nullable();
            $table->unsignedInteger('user_oct')->nullable();
            $table->date('date_nov')->nullable();
            $table->unsignedInteger('user_nov')->nullable();
            $table->date('date_dec')->nullable();
            $table->unsignedInteger('user_dec')->nullable();

            $table->integer('created_by');
            $table->integer('updated_by');
            $table->timestamps(); //create column created_at and updated_at
            
            $table->foreign('promotion_circle_id')->references('id')->on('promotion_circles');
            $table->foreign('user_approved')->references('id')->on('users');
            $table->foreign('user_jan')->references('id')->on('users');
            $table->foreign('user_feb')->references('id')->on('users');
            $table->foreign('user_mar')->references('id')->on('users');
            $table->foreign('user_apr')->references('id')->on('users');
            $table->foreign('user_may')->references('id')->on('users');
            $table->foreign('user_jun')->references('id')->on('users');
            $table->foreign('user_jul')->references('id')->on('users');
            $table->foreign('user_aug')->references('id')->on('users');
            $table->foreign('user_sep')->references('id')->on('users');
            $table->foreign('user_oct')->references('id')->on('users');
            $table->foreign('user_nov')->references('id')->on('users');
            $table->foreign('user_dec')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('activity_approvals');
    }
}
