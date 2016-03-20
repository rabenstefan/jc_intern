<?php

use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('roles')->insert([
            'id' => 0,
            'label' => 'Admin',
            'can_plan_rehearsal' => true,
            'can_plan_gig' => true,
            'can_send_mail' => true,
            'can_configure_system' => true,
            'only_own_voice' => false,
        ]);

        DB::table('user_role')->insert([
            'user_id' => 0,
            'role_id' => 0,
        ]);
    }
}
