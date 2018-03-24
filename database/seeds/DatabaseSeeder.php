<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //DB::statement('SET FOREIGN_KEY_CHECKS=0');
        $this->call(VoicesTableSeeder::class);
        $this->call(SemestersTableSeeder::class);

        $this->call(UsersTableSeeder::class);
        $this->call(RolesTableSeeder::class);
        
        $this->call(DatesTableSeeder::class);

        $this->call(SheetsTableSeeder::class);
        //DB::statement('SET FOREIGN_KEY_CHECKS=1');
    }
}
