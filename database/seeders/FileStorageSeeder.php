<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class FileStorageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $file_storages = array(
            array('id' => '1','code' => 's3','name' => 'Amazon S3','logo' => 'driver/GJrBdvIxtnEprk0kHylgzNh6LcGcfOUcA205IIK5.png','driver' => 'local','status' => '0','parameters' => '{"access_key_id":"xys6","secret_access_key":"xys","default_region":"xys5","bucket":"xys6","url":"xysds"}','created_at' => NULL,'updated_at' => '2024-03-06 08:13:56'),
            array('id' => '2','code' => 'sftp','name' => 'SFTP','logo' => 'driver/q8E08YsobyRZGOLHHeKGhwysWsi25F186EbaNNRx.png','driver' => 'local','status' => '0','parameters' => '{"sftp_username":"xys6","sftp_password":"xys"}','created_at' => NULL,'updated_at' => '2023-06-10 23:28:03'),
            array('id' => '3','code' => 'do','name' => 'Digitalocean Spaces','logo' => 'driver/iA8q685PBCnOAkmctLXZWhyqSoh7cJMOewpW4S8r.png','driver' => 'local','status' => '0','parameters' => '{"spaces_key":"hj","spaces_secret":"vh","spaces_endpoint":"jk","spaces_region":"sfo2","spaces_bucket":"assets-coral"}','created_at' => NULL,'updated_at' => '2023-06-10 23:45:21'),
            array('id' => '4','code' => 'ftp','name' => 'FTP','logo' => 'driver/wIwEOAJ45KgVGw0PL80WNfcbosB4IuUlxStfeHCX.png','driver' => 'local','status' => '0','parameters' => '{"ftp_host":"xys6","ftp_username":"xys","ftp_password":"xys6"}','created_at' => NULL,'updated_at' => '2023-06-10 23:27:43'),
            array('id' => '5','code' => 'local','name' => 'Local Storage','logo' => '','driver' => NULL,'status' => '1','parameters' => NULL,'created_at' => NULL,'updated_at' => '2024-03-06 08:13:56')
        );


        DB::table('file_storages')->insert($file_storages);
    }
}
