<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Only insert if not already present
        $exists = DB::table('languages')->where('short_name', 'vi')->exists();
        if (!$exists) {
            DB::table('languages')->insert([
                'name'           => 'Tiếng Việt',
                'short_name'     => 'vi',
                'flag'           => null,
                'flag_driver'    => 'local',
                'status'         => 1,
                'rtl'            => 0,
                'default_status' => 0,
                'created_at'     => now(),
                'updated_at'     => now(),
            ]);
        }
    }

    public function down(): void
    {
        DB::table('languages')->where('short_name', 'vi')->delete();
    }
};
