<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ActivityAttachment extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activity_attachments', function (Blueprint $table) {
            $table->increments('id');   
            $table->unsignedInteger('activity_id');
            $table->unsignedInteger('attachment_id');
            $table->string('FileType')->nullable();
            $table->timestamps();       
            $table->foreign('activity_id')->references('id')->on('activities');
            $table->foreign('attachment_id')->references('id')->on('attachments');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('activity_attachments');
    }
}
