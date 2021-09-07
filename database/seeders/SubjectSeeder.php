<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SubjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('subject')->insert([
            'subject_name' => 'math'
        ]);

        DB::table('subject')->insert([
            'subject_name' => 'biology'
        ]);

        DB::table('subject')->insert([
            'subject_name' => 'history'
        ]);

        DB::table('subject')->insert([
            'subject_name' => 'english'
        ]);
    }
}
