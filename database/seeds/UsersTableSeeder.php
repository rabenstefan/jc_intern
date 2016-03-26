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
            'last_echo' => 1,
        ]);

        DB::table('users')->insert([
            'id' => 1,
            'first_name' => 'Stimm',
            'last_name' => 'Führer',
            'email' => 'test1@gmail.com',
            'password' => bcrypt('secret'),
            'voice_id' => 6,
            'last_echo' => 1,
        ]);

        DB::table('users')->insert([
            'id' => 2,
            'first_name' => 'Chor',
            'last_name' => 'Leiter',
            'email' => 'test2@gmail.com',
            'password' => bcrypt('secret'),
            'voice_id' => 0,
            'last_echo' => 1,
        ]);

        factory(App\User::class, 20)->create();
    }
}
