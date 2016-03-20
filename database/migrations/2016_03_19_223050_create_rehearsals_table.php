<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRehearsalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rehearsals', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('description');
            $table->dateTime('date');
            $table->string('place');

            $table->integer('for_voice')->unsigned()->nullable();
            $table->foreign('for_voice')->references('id')->on('voices')->onDelete('cascade');

            $table->integer('semester')->unsigned();
            $table->foreign('semester')->references('id')->on('semesters')->onDelete('cascade');
            
            $table->boolean('mandatory');
            $table->float('weight', 4, 2); // Only if mandatory: How much does a miss weight?
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
        Schema::drop('rehearsals');
    }
}
