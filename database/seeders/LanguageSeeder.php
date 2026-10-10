<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LanguageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $languages = array(
            array('id' => '1', 'name' => 'English', 'short_name' => 'en', 'flag' => 'language/Tffwh41UiRo9GqB9P9OHiWP7R5lujb.avif', 'flag_driver' => 'local', 'status' => '1', 'rtl' => '0', 'default_status' => '1', 'created_at' => '2023-06-17 04:35:53', 'updated_at' => '2024-03-04 13:09:56'),
            array('id' => '2', 'name' => 'Spanish', 'short_name' => 'es', 'flag' => 'language/2NMm9l0d94BSpKWvJ4naT4W02i29Z6.avif', 'flag_driver' => 'local', 'status' => '1', 'rtl' => '0', 'default_status' => '0', 'created_at' => '2023-06-17 04:10:02', 'updated_at' => '2024-03-04 13:09:56')
        );
        DB::table('languages')->insert($languages);
    }
}
