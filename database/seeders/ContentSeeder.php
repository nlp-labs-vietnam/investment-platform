<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('contents')->insert([
            [
                'id' => 1,
                'name' => 'hero',
                'type' => 'single',
                'media' => '{"image":{"path":"contents\/gif1XANopxGByXyNGcuMx2z8ANjERv.webp","driver":"local"}}',
                'created_at' => '2024-08-28 12:29:22',
                'updated_at' => '2024-08-28 12:29:23'
            ],
            [
                'id' => 2,
                'name' => 'blog',
                'type' => 'single',
                'media' => NULL,
                'created_at' => '2024-08-29 04:46:37',
                'updated_at' => '2024-08-29 04:46:37'
            ]
        ]);
    }
}
