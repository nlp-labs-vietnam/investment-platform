<?php

namespace Database\Seeders;

use App\Models\Investment;
use App\Models\Property;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

//        $this->seedPropertiesInChunks();
//        $this->seedInvestmentsInChunks();

        /*$this->call([
            AdminSeeder::class, BasicControlSeeder::class, FileStorageSeeder::class, GatewaySeeder::class,
            LanguageSeeder::class, MaintenanceSeeder::class, NotificationSeeder::class, ManageMenuSeeder::class,
            PageSeeder::class, PayoutMethod::class, ContentSeeder::class, ContentDetailsSeeder::class,
            PageDetailsSeeder::class,
        ]);*/

    }


    private function seedPropertiesInChunks(): void
    {
        $chunkSize = 10;
        $totalRecords = 100;
        for ($i = 0; $i < $totalRecords / $chunkSize; $i++) {
            DB::transaction(function () use ($chunkSize) {
                Property::factory()->count($chunkSize)->create();
            });
            $inserted = ($i + 1) * $chunkSize;
            echo "Inserted $inserted records\n";
        }
        echo "Seeding complete. Total records: $totalRecords\n";
    }

    private function seedInvestmentsInChunks(): void
    {
        $chunkSize = 100;
        $totalRecords = 10000;
        for ($i = 0; $i < $totalRecords / $chunkSize; $i++) {
            DB::transaction(function () use ($chunkSize) {
                Investment::factory()->count($chunkSize)->create();
            });
            $inserted = ($i + 1) * $chunkSize;
            echo "Inserted $inserted investment records\n";
        }
        echo "Seeding complete. Total investment records: $totalRecords\n";
    }


}
