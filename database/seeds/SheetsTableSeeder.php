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


        DB::table('sheet_user')->insert([
            'id' => 1,
            'user_id' => 0,
            'sheet_id' => 1,
            'number' => '1',
            'status' => 'borrowed'
        ]);
        DB::table('sheet_user')->insert([
            'id' => 2,
            'user_id' => 0,
            'sheet_id' => 2,
            'number' => '1',
            'status' => 'borrowed'
        ]);
        DB::table('sheet_user')->insert([
            'id' => 3,
            'user_id' => 1,
            'sheet_id' => 2,
            'number' => '2',
            'status' => 'borrowed'
        ]);
    }
}
