<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'id' => 0,
            'first_name' => 'Felix',
            'last_name' => 'Rossmann',
            'email' => 'test@gmail.com',
            'password' => bcrypt('secret'),
            'voice_id' => 10,
            'last_echo' => 0,
        ]);
    }
}
