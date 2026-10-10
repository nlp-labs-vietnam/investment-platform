<?php

namespace Database\Seeders;

use Faker\Factory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RandomSeeder extends Seeder
{

    public function run(): void
    {
        $this->seedTableWithRandomDates('investments', '2025-01-01', '2025-12-30');
        // $this->seedTableWithRandomColumn('investments', 'user_id');
    }



    private function seedTableWithRandomDates(string $table, string $startDate, string $endDate): void
    {
        $faker = Factory::create();
        $batchSize = 1000;

        DB::table($table)->orderBy('id')->chunk($batchSize, function ($rows) use ($faker, $startDate, $endDate, $table) {
            $updates = [];
            foreach ($rows as $row) {
                $updates[] = [
                    'id' => $row->id,
                    'created_at' => $faker->dateTimeBetween($startDate, $endDate)->format('Y-m-d H:i:s'),
                    'updated_at' => $faker->dateTimeBetween($startDate, $endDate)->format('Y-m-d H:i:s'),
                ];
            }
            // Bulk update with a single query
            DB::table($table)->upsert($updates, ['id'], ['created_at', 'updated_at']);
        });

        $this->command->info("Table {$table} seeded with random date data between {$startDate} and {$endDate}.");
    }


    private function seedTableWithRandomColumn(string $table, string $column): void
    {
        $faker = Factory::create();
        $batchSize = 1000;

        DB::table($table)->orderBy('id')->chunk($batchSize, function ($rows) use ($column, $faker, $table) {
            $updates = [];
            foreach ($rows as $row) {
                $randomValue = $faker->numberBetween(3, 5);
                $updates[] = [
                    'id' => $row->id,
                    $column => $randomValue,
                ];
            }
            DB::table($table)->upsert($updates, ['id'], [$column]);
        });

        $this->command->info("Table {$table} column {$column} seeded with random values.");
    }


}
