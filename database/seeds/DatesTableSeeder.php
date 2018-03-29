<?php

use Illuminate\Database\Seeder;

class DatesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('rehearsals')->insert([
            'semester_id' => 1,
            'title'       => 'Erste Probe',
            'start'       => '2018-03-11 19:30:00',
            'end'         => '2018-03-11 21:45:00',
            'place'       => 'Hörsaal X',
            'binary_answer'   => true,
            'mandatory'   => true,
            'weight'      => 1.0,
        ]);

        DB::table('rehearsals')->insert([
            'semester_id' => 1,
            'title'       => 'Schnupper-Probe',
            'start'       => '2018-04-11 19:30:00',
            'end'         => '2018-04-11 21:45:00',
            'place'       => 'Hörsaal X',
            'binary_answer'   => true,
            'mandatory'   => true,
            'weight'      => 1.0,
        ]);

        DB::table('rehearsals')->insert([
            'semester_id' => 1,
            'title'       => 'Schnupper-Probe',
            'start'       => '2018-04-18 19:30:00',
            'end'         => '2018-04-18 21:45:00',
            'place'       => 'Hörsaal X',
            'binary_answer'   => true,
            'mandatory'   => true,
            'weight'      => 1.0,
        ]);

        factory("App\Rehearsal", 15)->create();

        DB::table('gigs')->insert([
            'semester_id' => 1,
            'title'       => 'Frühkonzert',
            'description' => 'Echt verdammt früh!',
            'start'       => '2018-04-21 19:30:00',
            'end'         => '2018-04-21 21:00:00',
            'place'       => 'Franziskuskirche',
            'binary_answer'   => true,
        ]);

        factory("App\Gig", 5)->create();
    }
}
