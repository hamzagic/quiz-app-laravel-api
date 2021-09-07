<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('role')->insert([
            'role_title' => 'director'
        ]);

        DB::table('role')->insert([
            'role_title' => 'teacher'
        ]);

        DB::table('role')->insert([
            'role_title' => 'coordinator'
        ]);
    }
}
