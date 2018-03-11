<?php

use Illuminate\Database\Seeder;

class VoicesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('voices')->insert([
            [
                'id' => 1,
                'name' => 'Gesamter Chor',
                'super_group' => 'NULL',
                'child_group' => false,
            ], [
                'id' => 2,
                'name' => 'Sopran',
                'super_group' => 1,
                'child_group' => false,
            ], [
                'id' => 3,
                'name' => 'Alt',
                'super_group' => 1,
                'child_group' => false,
            ], [
                'id' => 4,
                'name' => 'Tenor',
                'super_group' => 1,
                'child_group' => false,
            ], [
                'id' => 5,
                'name' => 'Bass',
                'super_group' => 1,
                'child_group' => false,
            ], [
                'id' => 6,
                'name' => 'Sopran 1',
                'super_group' => 2,
                'child_group' => true,
            ], [
                'id' => 7,
                'name' => 'Sopran 2',
                'super_group' => 2,
                'child_group' => true,
            ], [
                'id' => 8,
                'name' => 'Alt 1',
                'super_group' => 3,
                'child_group' => true,
            ], [
                'id' => 9,
                'name' => 'Alt 2',
                'super_group' => 3,
                'child_group' => true,
            ], [
                'id' => 10,
                'name' => 'Tenor 1',
                'super_group' => 4,
                'child_group' => true,
            ], [
                'id' => 11,
                'name' => 'Tenor 2',
                'super_group' => 4,
                'child_group' => true,
            ], [
                'id' => 12,
                'name' => 'Bass 1',
                'super_group' => 5,
                'child_group' => true,
            ], [
                'id' => 13,
                'name' => 'Bass 2',
                'super_group' => 5,
                'child_group' => true,
            ]
        ]);
    }
}
