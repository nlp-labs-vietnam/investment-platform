<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ContentDetailsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('content_details')->insert([
            [
                'id' => 1,
                'content_id' => 1,
                'language_id' => 1,
                'description' => '{"heading":"Digital Business provide The optimal Solutions.","sub_heading":"We are totally focused on delivering high quality Cloud Service &amp; software solution. We have completed development projects for international clients in many market sectors, including market research, transport, test and measurement and manufacturing."}',
                'created_at' => '2024-08-28 12:29:23',
                'updated_at' => '2024-08-28 12:29:23'
            ],
            [
                'id' => 2,
                'content_id' => 2,
                'language_id' => 1,
                'description' => '{"heading":"Improve the sales of Digital Business Products.","sub_heading":"Trying To Find A Template For Your Project"}',
                'created_at' => '2024-08-29 04:46:37',
                'updated_at' => '2024-08-29 04:46:37'
            ]
        ]);
    }
}
