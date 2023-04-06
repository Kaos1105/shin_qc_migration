<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsermasterTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //2100
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('user_code', 10)->unique();
            $table->string('name',100);
            $table->tinyInteger('role_indicator');
            $table->string('position',100)->nullable();
            $table->string('email',100)->nullable();
            $table->string('phone',20)->nullable();
            $table->string('login_id', 100)->unique();
            $table->string('password', 100);
            $table->string('password_encrypt', 100);
            $table->rememberToken();
            $table->tinyInteger('access_authority');
            
            $table->integer('display_order' );
            $table->tinyInteger('statistic_classification');
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
        Schema::dropIfExists('users');
    }
}
