<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class BlockAttendanceEditingAfterCertainTime extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('gigs', function (Blueprint $table) {
            $table->dateTime('answer_deadline')->after('binary_answer')->nullable();
        });

        Schema::table('rehearsals', function (Blueprint $table) {
            $table->dateTime('answer_deadline')->after('binary_answer')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('rehearsals', function (Blueprint $table) {
            $table->dropColumn('answer_deadline');
        });

        Schema::table('gigs', function (Blueprint $table) {
            $table->dropColumn('answer_deadline');
        });
    }
}
