<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PageDetailsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('page_details')->insert([
            [
                'id' => 1,
                'page_id' => 2,
                'language_id' => 1,
                'name' => 'Home',
                'content' => '<div class="custom-block" contenteditable="false"><div class="custom-block-content">[[hero]]</div>
                    <span class="delete-block">×</span>
                    <span class="up-block">↑</span>
                    <span class="down-block">↓</span></div><p><br></p><div class="custom-block" contenteditable="false"><div class="custom-block-content">[[brand]]</div>
                    <span class="delete-block">×</span>
                    <span class="up-block">↑</span>
                    <span class="down-block">↓</span></div><p><br></p><div class="custom-block" contenteditable="false"><div class="custom-block-content">[[features]]</div>
                    <span class="delete-block">×</span>
                    <span class="up-block">↑</span>
                    <span class="down-block">↓</span></div><p><br></p><div class="custom-block" contenteditable="false"><div class="custom-block-content">[[footer]]</div>
                    <span class="delete-block">×</span>
                    <span class="up-block">↑</span>
                    <span class="down-block">↓</span></div><p><br></p>',
                'sections' => '["hero","brand","features","footer"]',
                'created_at' => '2024-08-28 12:28:36',
                'updated_at' => '2024-08-29 04:44:05'
            ],
            [
                'id' => 2,
                'page_id' => 4,
                'language_id' => 1,
                'name' => 'Feature',
                'content' => '<div class="custom-block" contenteditable="false"><div class="custom-block-content">[[features]]</div>
                    <span class="delete-block">×</span>
                    <span class="up-block">↑</span>
                    <span class="down-block">↓</span></div><p><br></p><div class="custom-block" contenteditable="false"><div class="custom-block-content">[[footer]]</div>
                    <span class="delete-block">×</span>
                    <span class="up-block">↑</span>
                    <span class="down-block">↓</span></div><p><br></p>',
                'sections' => '["features","footer"]',
                'created_at' => '2024-08-29 04:45:13',
                'updated_at' => '2024-08-29 04:45:13'
            ]
        ]);
    }
}
