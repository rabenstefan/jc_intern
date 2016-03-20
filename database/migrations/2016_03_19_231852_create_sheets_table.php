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

        Schema::create('user_sheets', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('user')->unsigned();
            $table->foreign('user')->references('id')->on('users')->onDelete('cascade');

            $table->integer('sheet')->unsigned()->default(0);
            $table->foreign('sheet')->references('id')->on('sheets')->onDelete('cascade');

            $table->integer('number')->unsigned(); // Seriennummer des Heftes.

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
        Schema::drop('user_sheets');
        Schema::drop('sheets');
    }
}
