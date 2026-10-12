<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

/**
 * Cập nhật gateway currencies sang VND và điều chỉnh ngưỡng chuyển tiền.
 *
 * Thay đổi:
 * - Gateway 600 (VietQR) & 601 (NLP Group): set currencies JSON = VND
 * - Gateway 1000 (Bank Transfer thủ công): supported_currency = VND
 * - basic_controls: min_transfer/max_transfer/transfer_charge sang VND
 * - Tắt currency_layer auto update (không cần convert nếu chỉ dùng VND)
 */
return new class extends Migration
{
    private const VND_CURRENCIES = '{"0":{"VND":"VND"}}';

    public function up(): void
    {
        $now = now();

        // 1. Gateway VietQR & NLP Group — set currencies JSON
        DB::table('gateways')
            ->whereIn('id', [600, 601])
            ->update([
                'currencies'           => self::VND_CURRENCIES,
                'supported_currency'   => '["VND"]',
                'updated_at'           => $now,
            ]);

        // 2. Bank Transfer manual — switch supported currency to VND
        DB::table('gateways')
            ->where('id', 1000)
            ->update([
                'currencies'           => self::VND_CURRENCIES,
                'supported_currency'   => '["VND"]',
                'updated_at'           => $now,
            ]);

        // 3. basic_controls — ngưỡng chuyển tiền phù hợp VND
        //    min: 10,000 VND   max: 500,000,000 VND   charge: 5,000 VND
        DB::table('basic_controls')
            ->where('id', 1)
            ->update([
                'min_transfer'              => 10000,
                'max_transfer'              => 500000000,
                'transfer_charge'           => 5000,
                'currency_layer_auto_update' => 0,  // tắt auto update tỷ giá
                'updated_at'                => $now,
            ]);

        Cache::forget('basic_controls');
        Cache::flush();
    }

    public function down(): void
    {
        $now = now();

        DB::table('gateways')
            ->whereIn('id', [600, 601])
            ->update([
                'currencies'         => null,
                'supported_currency' => '["VND"]',
                'updated_at'         => $now,
            ]);

        DB::table('gateways')
            ->where('id', 1000)
            ->update([
                'currencies'         => null,
                'supported_currency' => '["BDT","USD"]',
                'updated_at'         => $now,
            ]);

        DB::table('basic_controls')
            ->where('id', 1)
            ->update([
                'min_transfer'              => 10,
                'max_transfer'              => 10000,
                'transfer_charge'           => 1,
                'currency_layer_auto_update' => 1,
                'updated_at'                => $now,
            ]);

        Cache::forget('basic_controls');
    }
};
