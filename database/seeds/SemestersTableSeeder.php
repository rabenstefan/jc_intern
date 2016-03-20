<?php

use Illuminate\Database\Seeder;

class SemestersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('semesters')->insert([
            [
                'id' => 0,
                'start' => '2015-10-01',
                'end' => '2016-03-31',
                'label' => 'Wintersemester 15/16',
            ],[
                'id' => 1,
                'start' => '2016-04-01',
                'end' => '2016-09-31',
                'label' => 'Sommersemester 16',
            ]
        ]);
    }
}
