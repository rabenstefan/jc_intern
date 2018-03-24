<?php

use Illuminate\Database\Seeder;

class SheetsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('sheets')->insert([
            'id' => 1,
            'label' => 'Modern Talking - Greatest Hits',
            'amount' => 60,
        ]);

        DB::table('sheets')->insert([
            'id' => 2,
            'label' => '𝕸𝖔𝖗𝖌𝖊𝖓 𝖒𝖆𝖗𝖘𝖈𝖍𝖎𝖊𝖗𝖊𝖓 𝖜𝖎𝖗 - Liederbuch des deutschen Soldaten',
            'amount' => 60,
        ]);

        DB::table('sheets')->insert([
            'id' => 3,
            'label' => 'Mundorgel',
            'amount' => 60,
        ]);

        DB::table('sheets')->insert([
            'id' => 4,
            'label' => 'Death Growls For Beginners',
            'amount' => 60,
        ]);


        DB::table('sheet_user')->insert([
            'id' => 1,
            'user_id' => 1,
            'sheet_id' => 1,
            'number' => '1',
            'status' => 'borrowed'
        ]);

        DB::table('sheet_user')->insert([
            'id' => 2,
            'user_id' => 1,
            'sheet_id' => 2,
            'number' => '1',
            'status' => 'borrowed'
        ]);

        DB::table('sheet_user')->insert([
            'id' => 3,
            'user_id' => 2,
            'sheet_id' => 2,
            'number' => '2',
            'status' => 'borrowed'
        ]);

        DB::table('sheet_user')->insert([
            'id' => 4,
            'user_id' => 2,
            'sheet_id' => 4,
            'number' => '1',
            'status' => 'lost'
        ]);

        DB::table('sheet_user')->insert([
            'id' => 5,
            'user_id' => 2,
            'sheet_id' => 4,
            'number' => '2',
            'status' => 'borrowed'
        ]);

        DB::table('sheet_user')->insert([
            'id' => 6,
            'user_id' => 2,
            'sheet_id' => 3,
            'number' => '1',
            'status' => 'borrowed'
        ]);

        DB::table('sheet_user')->insert([
            'id' => 7,
            'user_id' => 3,
            'sheet_id' => 3,
            'number' => '2',
            'status' => 'borrowed'
        ]);

        DB::table('sheet_user')->insert([
            'id' => 8,
            'user_id' => 4,
            'sheet_id' => 3,
            'number' => '3',
            'status' => 'borrowed'
        ]);


        DB::table('sheet_user')->insert([
            'id' => 9,
            'user_id' => 5,
            'sheet_id' => 2,
            'number' => '3',
            'status' => 'bought'
        ]);

        DB::table('sheet_user')->insert([
            'id' => 10,
            'user_id' => 5,
            'sheet_id' => 4,
            'number' => '3',
            'status' => 'lost'
        ]);

        DB::table('sheet_user')->insert([
            'id' => 11,
            'user_id' => 19,
            'sheet_id' => 4,
            'number' => '4',
            'status' => 'bought'
        ]);

    }
}
