<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommitmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('commitments', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('user')->unsigned();
            $table->foreign('user')->references('id')->on('users')->onDelete('cascade');

            $table->integer('gig')->unsigned();
            $table->foreign('gig')->references('id')->on('gig')->onDelete('cascade');

            $table->integer('attendance')->default(0); // 0 = attending, 1 = maybe, 2 = not attending
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
        Schema::drop('commitments');
    }
}
