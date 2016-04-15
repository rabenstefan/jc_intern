<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttendancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->integer('rehearsal_id')->unsigned();
            $table->foreign('rehearsal_id')->references('id')->on('rehearsals')->onDelete('cascade');

            $table->boolean('excused')->default(false); // Not excused means "wants to attend"
            $table->string('comment')->nullable();
            $table->string('internal_comment')->nullable();
            $table->boolean('missed')->default(false);
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
        Schema::drop('attendances');
    }
}
