<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SchoolSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('school')->insert([
            'school_name' => 'Kaspa School',
            'school_address' => '123 Main St.',
            'phone_number' => '2345545555'
        ]);
    }
}
