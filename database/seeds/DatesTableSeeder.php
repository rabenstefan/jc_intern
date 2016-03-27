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
            'title'       => 'Schnupper-Probe',
            'start'       => '2016-04-13 19:30:00',
            'end'         => '2016-04-13 21:45:00',
            'place'       => 'Hörsaal X',
            'mandatory'   => true,
            'weight'      => 1.0,
        ]);

        DB::table('rehearsals')->insert([
            'semester_id' => 1,
            'title'       => 'Schnupper-Probe',
            'start'       => '2016-04-20 19:30:00',
            'end'         => '2016-04-20 21:45:00',
            'place'       => 'Hörsaal X',
            'mandatory'   => true,
            'weight'      => 1.0,
        ]);

        factory(App\Rehearsal::class, 15)->create();

        DB::table('gigs')->insert([
            'semester_id' => 1,
            'title'       => 'Frühkonzert',
            'description' => 'Echt verdammt früh!',
            'start'       => '2016-04-22 19:30:00',
            'end'         => '2016-04-22 21:00:00',
            'place'       => 'Franziskuskirche',
        ]);

        factory(App\Gig::class, 5)->create();
    }
}
