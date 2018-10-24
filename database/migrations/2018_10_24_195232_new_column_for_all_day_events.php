<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class NewColumnForAllDayEvents extends Migration
{

    /**
     * Old algorithm that determined if an event is allday
     *
     * @return bool
     */
    private function oldIsAllDay($date) {
        return $date->getStart()->startOfDay() == $date->getStart() && $date->getEnd()->startOfDay() == $date->getEnd();
    }

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('gigs', function(Blueprint $table) {
            $table->boolean('all_day')->after('end')->default(false);
        });

        Schema::table('rehearsals', function(Blueprint $table) {
            $table->boolean('all_day')->after('end')->default(false);
        });

        /*
         * Missing from this file: Use \Illuminate\Support\Facades\DB to get all events and use $this->oldIsAllDay to determine
         * the all_day-status. Then, set end = end->subDay()->endOfDay().
         *
         * However, there are no all-day events in the current production database. Hence, I skipped coding this.
         */
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

        /*
         * Missing from this file: See comment in $this->up();
         */

        Schema::table('rehearsals', function(Blueprint $table) {
            $table->dropColumn('all_day');
        });

        Schema::table('gigs', function(Blueprint $table) {
            $table->dropColumn('all_day');
        });
    }
}
