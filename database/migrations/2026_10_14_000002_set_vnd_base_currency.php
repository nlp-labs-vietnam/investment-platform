<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

/**
 * Đổi base_currency từ USD → VND và cập nhật currency_symbol.
 * fraction_number = 0 (VND không dùng số thập phân).
 */
return new class extends Migration
{
    public function up(): void
    {
        DB::table('basic_controls')->where('id', 1)->update([
            'base_currency'    => 'VND',
            'currency_symbol'  => '₫',
            'fraction_number'  => 0,
            'updated_at'       => now(),
        ]);

        // Xóa cache cài đặt để force reload ngay
        Cache::forget('basic_controls');
        Cache::flush();
    }

    public function down(): void
    {
        DB::table('basic_controls')->where('id', 1)->update([
            'base_currency'    => 'USD',
            'currency_symbol'  => '$',
            'fraction_number'  => 2,
            'updated_at'       => now(),
        ]);

        Cache::forget('basic_controls');
    }
};
