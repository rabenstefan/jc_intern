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
            $table->string('description')->nullable();
            $table->dateTime('start');
            $table->dateTime('end');
            $table->string('place')->nullable();

            $table->integer('voice_id')->unsigned()->default(1);
            $table->foreign('voice_id')->references('id')->on('voices')->onDelete('cascade');

            $table->integer('semester_id')->unsigned();
            $table->foreign('semester_id')->references('id')->on('semesters')->onDelete('cascade');

            $table->boolean('binary_answer')->default(true);
            $table->boolean('mandatory')->default(true);
            $table->float('weight', 4, 2)->default(1.0); // Only if mandatory: How much does a miss weight?
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
