<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRehearsalAttendancesTable extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('rehearsal_attendances', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

            $table->integer('rehearsal_id')->unsigned();
            $table->foreign('rehearsal_id')->references('id')->on('rehearsals')->onDelete('cascade');

            $table->integer('attendance')->default(2); // 0 = not attending, 1 = maybe, 2 = attending
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
    public function down() {
        Schema::drop('rehearsal_attendance_user');
        Schema::drop('rehearsal_rehearsal_attendance');
        Schema::drop('rehearsal_attendances');
    }
}
