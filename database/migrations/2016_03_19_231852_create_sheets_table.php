<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSheetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sheets', function (Blueprint $table) {
            $table->increments('id');
            $table->string('label');
            $table->integer('amount')->unsigned();
            $table->timestamps();
        });

        Schema::create('sheet_user', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->integer('sheet_id')->unsigned()->default(0);
            $table->foreign('sheet_id')->references('id')->on('sheets')->onDelete('cascade');

            $table->integer('number')->unsigned(); // Seriennummer des Heftes.
            $table->string('status');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('sheet_user');
        Schema::drop('sheets');
    }
}
