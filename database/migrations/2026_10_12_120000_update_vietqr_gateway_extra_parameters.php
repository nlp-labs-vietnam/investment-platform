<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Cập nhật extra_parameters cho gateway vietqr:
 * Thêm webhook_url → route 'vietqr.ipn' (POST /ipn/{code}/vietqr)
 * để hiển thị Callback URL trên trang chỉnh sửa Payment Method.
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::table('gateways')
            ->where('code', 'vietqr')
            ->update([
                'extra_parameters' => json_encode(['webhook_url' => 'vietqr.ipn']),
                'updated_at'       => now(),
            ]);
    }

    public function down(): void
    {
        DB::table('gateways')
            ->where('code', 'vietqr')
            ->update([
                'extra_parameters' => null,
                'updated_at'       => now(),
            ]);
    }
};
