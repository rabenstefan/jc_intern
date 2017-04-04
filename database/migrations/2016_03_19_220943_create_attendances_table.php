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

        Schema::create('attendance_user', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->integer('attendance_id')->unsigned()->default(0);
            $table->foreign('attendance_id')->references('id')->on('attendances')->onDelete('cascade');

            $table->timestamps();
        });

        Schema::create('attendance_rehearsal', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('rehearsal_id')->unsigned();
            $table->foreign('rehearsal_id')->references('id')->on('rehearsals')->onDelete('cascade');

            $table->integer('attendance_id')->unsigned()->default(0);
            $table->foreign('attendance_id')->references('id')->on('attendances')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::drop('attendance_user');
        Schema::drop('attendance_rehearsal');
        Schema::drop('attendances');
    }
}
