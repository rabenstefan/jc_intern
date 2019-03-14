<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddDoubleUniqueForAttendances extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('gig_attendances', function (Blueprint $table) {
            $table->unique(['gig_id', 'user_id'], 'unique_gig_attendance_for_user');
        });

        Schema::table('rehearsal_attendances', function (Blueprint $table) {
            $table->unique(['rehearsal_id', 'user_id'], 'unique_rehearsal_attendance_for_user');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('gig_attendances', function (Blueprint $table) {
            $table->dropUnique('unique_gig_attendance_for_user');
        });

        Schema::table('rehearsal_attendances', function (Blueprint $table) {
            $table->dropUnique('unique_rehearsal_attendance_for_user');
        });
    }
}
