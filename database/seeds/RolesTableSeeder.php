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
            'id' => 1,
            'label' => 'Vorstand',
            'can_plan_rehearsal' => true,
            'can_plan_gig' => true,
            'can_send_mail' => true,
            'can_configure_system' => true,
            'only_own_voice' => false,
        ]);

        DB::table('roles')->insert([
            'id' => 2,
            'label' => 'Stimmführer',
            'can_plan_rehearsal' => true,
            'can_plan_gig' => false,
            'can_send_mail' => false,
            'can_configure_system' => false,
            'only_own_voice' => true,
        ]);

        DB::table('roles')->insert([
            'id' => 3,
            'label' => 'Musikalische Leitung',
            'can_plan_rehearsal' => true,
            'can_plan_gig' => true,
            'can_send_mail' => true,
            'can_configure_system' => true,
            'musical_leadership' => true,
            'only_own_voice' => false,
        ]);

        DB::table('role_user')->insert([
            'user_id' => 1,
            'role_id' => 1,
        ]);

        DB::table('role_user')->insert([
            'user_id' => 2,
            'role_id' => 2,
        ]);

        DB::table('role_user')->insert([
            'user_id' => 3,
            'role_id' => 3,
        ]);
    }
}
