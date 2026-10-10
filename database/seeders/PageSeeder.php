<?php

namespace Database\Seeders;

use App\Models\Page;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PageSeeder extends Seeder
{
    public function run(): void
    {
//        $pages = [
//            ['name' => 'Property', 'slug' => 'property', 'template_name' => 'green', 'type' => 2],
//        ];
//
//        foreach ($pages as $page) {
//            Page::updateOrCreate(
//                ['name' => $page['name'], 'template_name' => $page['template_name']],
//                [
//                    'slug' => $page['slug'],
//                    'template_name' => $page['template_name'],
//                    'type' => $page['type'],
//                ],
//                [
//                    'created_at' => \Illuminate\Support\Carbon::now(),
//                    'updated_at' => Carbon::now(),
//                ]
//            );
//        }

        $pages = [
            ['name' => 'Blog', 'slug' => 'blog', 'template_name' => 'green', 'type' => 2],
        ];

        foreach ($pages as $page) {
            Page::updateOrCreate(
                ['name' => $page['name'], 'template_name' => $page['template_name']],
                [
                    'slug' => $page['slug'],
                    'template_name' => $page['template_name'],
                    'type' => $page['type'],
                ],
                [
                    'created_at' => \Illuminate\Support\Carbon::now(),
                    'updated_at' => Carbon::now(),
                ]
            );
        }
    }
}
