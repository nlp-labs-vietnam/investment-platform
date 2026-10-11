<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Seed NLP Group Native Gateway vào bảng gateways.
 *
 * id = 601  (< 1000 → automatic gateway)
 * code = "nlpgroup"  → App\Services\Gateway\nlpgroup\Payment
 *
 * parameters (admin cấu hình qua Payment Methods):
 *   mb_consumer_id      : MB Bank Business API Consumer ID
 *   mb_consumer_secret  : MB Bank Business API Consumer Secret
 *   mb_account_no       : Số tài khoản doanh nghiệp NLP Group tại MB Bank
 *   mb_account_name     : Tên tài khoản (hiển thị trên QR)
 *   callback_secret     : HMAC-SHA256 secret xác thực callback từ MB Bank
 */
return new class extends Migration
{
    public function up(): void
    {
        if (DB::table('gateways')->where('code', 'nlpgroup')->exists()) {
            return;
        }

        $parameters = json_encode([
            'mb_consumer_id' => [
                'field_name'  => 'mb_consumer_id',
                'field_label' => 'MB Bank Consumer ID (Business API)',
                'type'        => 'text',
                'validation'  => 'required',
            ],
            'mb_consumer_secret' => [
                'field_name'  => 'mb_consumer_secret',
                'field_label' => 'MB Bank Consumer Secret',
                'type'        => 'text',
                'validation'  => 'required',
            ],
            'mb_account_no' => [
                'field_name'  => 'mb_account_no',
                'field_label' => 'MB Bank Account Number (NLP Group)',
                'type'        => 'text',
                'validation'  => 'required',
            ],
            'mb_account_name' => [
                'field_name'  => 'mb_account_name',
                'field_label' => 'Account Holder Name',
                'type'        => 'text',
                'validation'  => 'required',
            ],
            'callback_secret' => [
                'field_name'  => 'callback_secret',
                'field_label' => 'HMAC Secret (xác thực callback từ MB Bank)',
                'type'        => 'text',
                'validation'  => 'required',
            ],
        ]);

        $receivableCurrencies = json_encode([
            [
                'name'              => 'VND',
                'currency_symbol'   => 'VND',
                'conversion_rate'   => 1,
                'min_limit'         => 10000,
                'max_limit'         => 500000000,
                'percentage_charge' => 0,
                'fixed_charge'      => 0,
            ],
        ]);

        DB::table('gateways')->insert([
            'id'                    => 601,
            'code'                  => 'nlpgroup',
            'name'                  => 'NLP Group (MB Bank)',
            'sort_by'               => 61,
            'image'                 => null,
            'driver'                => null,
            'status'                => 0,
            'parameters'            => $parameters,
            'currencies'            => null,
            'extra_parameters'      => null,
            'supported_currency'    => json_encode(['VND']),
            'receivable_currencies' => $receivableCurrencies,
            'description'           => 'Thanh toán qua QR chuyển khoản MB Bank — NLP Group tự xác nhận qua MB Bank Business API (không qua bên thứ ba).',
            'currency_type'         => 1,
            'is_sandbox'            => 0,
            'environment'           => 'test',   // mặc định sandbox đến khi cấu hình live
            'is_manual'             => 0,
            'note'                  => 'Vui lòng chuyển khoản đúng <strong>nội dung</strong> và <strong>số tiền</strong> hiển thị. Hệ thống xác nhận tự động qua MB Bank Business API.<br>Callback URL: <code>https://api.nlpgroup.vn/nlpgroup/callback</code>',
            'created_at'            => now(),
            'updated_at'            => now(),
        ]);
    }

    public function down(): void
    {
        DB::table('gateways')->where('code', 'nlpgroup')->delete();
    }
};
