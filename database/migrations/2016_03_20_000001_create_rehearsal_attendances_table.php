<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRehearsalAttendancesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rehearsal_attendances', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->integer('rehearsal_id')->unsigned();
            $table->foreign('rehearsal_id')->references('id')->on('rehearsals')->onDelete('cascade');

            $table->integer('attendance')->default(0); // 0 = attending, 1 = maybe, 2 = not attending
            $table->string('comment')->nullable();
            $table->string('internal_comment')->nullable();
            $table->boolean('missed')->default(false);
            $table->timestamps();
        });

        Schema::create('rehearsal_attendance_user', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->integer('rehearsal_attendance_id')->unsigned()->default(1);
            $table->foreign('rehearsal_attendance_id')->references('id')->on('rehearsal_attendances')->onDelete('cascade');

            $table->timestamps();
        });

        Schema::create('rehearsal_attendance_rehearsal', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('rehearsal_id')->unsigned();
            $table->foreign('rehearsal_id')->references('id')->on('rehearsals')->onDelete('cascade');

            $table->integer('rehearsal_attendance_id')->unsigned()->default(1);
            $table->foreign('rehearsal_attendance_id')->references('id')->on('rehearsal_attendances')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::drop('rehearsal_attendance_user');
        Schema::drop('rehearsal_attendance_rehearsal');
        Schema::drop('rehearsal_attendances');
    }
}
