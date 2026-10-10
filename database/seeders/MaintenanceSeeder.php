<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class MaintenanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $maintenance_modes = array(
            array('id' => '1', 'heading' => 'The website under maintenance!', 'description' => '<p>We are currently undergoing scheduled maintenance to improve our services and enhance your user experience. During this time, our website/system will be temporarily unavailable.
</p><p><br></p><p>
We apologize for any inconvenience this may cause and appreciate your patience. Please rest assured that we are working diligently to complete the maintenance as quickly as possible.</p>', 'image' => 'maintenanceMode/3jXAnm42OZuYy3kVDcHKUjW3gyiG8eSo96rlgg19.png', 'image_driver' => 'local', 'created_at' => '2023-10-04 04:44:32', 'updated_at' => '2024-02-05 10:00:13')
        );

        DB::table('maintenance_modes')->insert($maintenance_modes);
    }
}
