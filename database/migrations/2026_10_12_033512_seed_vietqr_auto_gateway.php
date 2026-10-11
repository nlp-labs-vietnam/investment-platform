<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

/**
 * Seed VietQR Auto Gateway vào bảng gateways.
 *
 * id = 600  (< 1000 → automatic gateway, xử lý qua Service class)
 * code = "vietqr"  → map tới App\Services\Gateway\vietqr\Payment
 *
 * parameters (JSON) — admin cấu hình qua trang Payment Methods:
 *   bank_id          : BIN ngân hàng (vd: 970422 = MB Bank)
 *   account_no       : Số tài khoản thụ hưởng
 *   account_name     : Tên chủ tài khoản (hiển thị trên QR)
 *   webhook_secret   : API key / secure-token từ SePay hoặc Casso
 *   webhook_provider : "sepay" hoặc "casso"
 */
return new class extends Migration
{
    public function up(): void
    {
        // Không insert nếu đã tồn tại (idempotent)
        if (DB::table('gateways')->where('code', 'vietqr')->exists()) {
            return;
        }

        $parameters = json_encode([
            'bank_id' => [
                'field_name'  => 'bank_id',
                'field_label' => 'Bank BIN / Bank ID (vd: 970422 = MB Bank)',
                'type'        => 'text',
                'validation'  => 'required',
            ],
            'account_no' => [
                'field_name'  => 'account_no',
                'field_label' => 'Account Number',
                'type'        => 'text',
                'validation'  => 'required',
            ],
            'account_name' => [
                'field_name'  => 'account_name',
                'field_label' => 'Account Holder Name',
                'type'        => 'text',
                'validation'  => 'required',
            ],
            'webhook_secret' => [
                'field_name'  => 'webhook_secret',
                'field_label' => 'Webhook Secret (SePay Apikey / Casso secure-token)',
                'type'        => 'text',
                'validation'  => 'required',
            ],
            'webhook_provider' => [
                'field_name'  => 'webhook_provider',
                'field_label' => 'Webhook Provider (sepay / casso)',
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

        $supportedCurrency = json_encode(['VND']);

        DB::table('gateways')->insert([
            'id'                   => 600,
            'code'                 => 'vietqr',
            'name'                 => 'VietQR (Auto)',
            'sort_by'              => 60,
            'image'                => null,
            'driver'               => null,
            'status'               => 0,            // mặc định tắt — admin bật sau khi cấu hình
            'parameters'           => $parameters,
            'currencies'           => null,
            'extra_parameters'     => null,
            'supported_currency'   => $supportedCurrency,
            'receivable_currencies'=> $receivableCurrencies,
            'description'          => 'Thanh toán qua mã QR ngân hàng Việt Nam (VietQR). Tự động xác nhận qua webhook SePay hoặc Casso.',
            'currency_type'        => 1,
            'is_sandbox'           => 0,
            'environment'          => 'live',
            'is_manual'            => 0,
            'note'                 => 'Vui lòng chuyển khoản đúng <strong>nội dung</strong> và <strong>số tiền</strong> hiển thị trên màn hình. Giao dịch sẽ được xác nhận tự động trong vòng 30 giây sau khi hệ thống nhận webhook.',
            'created_at'           => now(),
            'updated_at'           => now(),
        ]);
    }

    public function down(): void
    {
        DB::table('gateways')->where('code', 'vietqr')->delete();
    }
};
