<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ActivityApprovalsStatistics extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activity_approvals_statistics', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('circle_id');
            $table->integer('year')->nullable();
            $table->integer('kaizen_month_1')->default(0)->nullable();
            $table->integer('kaizen_month_2')->default(0)->nullable();
            $table->integer('kaizen_month_3')->default(0)->nullable();
            $table->integer('kaizen_month_4')->default(0)->nullable();
            $table->integer('kaizen_month_5')->default(0)->nullable();
            $table->integer('kaizen_month_6')->default(0)->nullable();
            $table->integer('kaizen_month_7')->default(0)->nullable();
            $table->integer('kaizen_month_8')->default(0)->nullable();
            $table->integer('kaizen_month_9')->default(0)->nullable();
            $table->integer('kaizen_month_10')->default(0)->nullable();
            $table->integer('kaizen_month_11')->default(0)->nullable();
            $table->integer('kaizen_month_12')->default(0)->nullable();

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
        Schema::dropIfExists('activity_approvals_statistics');
    }
}
