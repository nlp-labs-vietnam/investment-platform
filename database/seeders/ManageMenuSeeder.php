<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ManageMenuSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        DB::table('manage_menus')->insert([
            [
                'id' => 3,
                'menu_section' => 'header',
                'menu_items' => '{"0":"home","3":"feature"}',
                'created_at' => '2023-10-16 06:54:10',
                'updated_at' => '2024-08-29 04:48:08'
            ],
            [
                'id' => 4,
                'menu_section' => 'footer',
                'menu_items' => '{"useful_link":["home"],"support_link":["feature"]}',
                'created_at' => '2023-10-16 06:54:10',
                'updated_at' => '2024-08-29 04:51:54'
            ]
        ]);
    }
}
