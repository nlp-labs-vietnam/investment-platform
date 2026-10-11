<?php

namespace App\Services\Gateway\vietqr;

use Facades\App\Services\BasicService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * VietQR Auto Gateway
 *
 * Tích hợp VietQR thông qua dịch vụ webhook trung gian (SePay / Casso).
 * Luồng:
 *   1. prepareData()  → Tạo QR code URL từ VietQR API (img.vietqr.io) + hiển thị view
 *   2. ipn()          → Nhận webhook POST từ SePay/Casso → xác thực → nạp tiền
 *
 * Cấu hình gateway (parameters trong DB):
 *   - bank_id          : Mã ngân hàng VietQR  (vd: "970422" = MB Bank)
 *   - account_no       : Số tài khoản thụ hưởng
 *   - account_name     : Tên chủ tài khoản
 *   - webhook_secret   : Secret key để xác thực chữ ký từ SePay/Casso
 *   - webhook_provider : "sepay" | "casso"  (default: sepay)
 */
class Payment
{
    /** Độ dài nội dung chuyển khoản: 8 ký tự hex từ trx_id */
    private const CONTENT_PREFIX = 'NLP';

    public static function prepareData($deposit, $gateway)
    {
        $params   = $gateway->parameters;
        $bankId   = $params->bank_id ?? '';
        $accountNo = $params->account_no ?? '';
        $accountName = rawurlencode($params->account_name ?? basicControl()->site_title);

        // Nội dung chuyển khoản = prefix + 8 ký tự cuối trx_id (dễ đối soát)
        $content  = self::CONTENT_PREFIX . strtoupper(substr($deposit->trx_id, -8));
        $amount   = (int) round($deposit->payable_amount);

        // VietQR public image API — không cần API key
        $qrUrl = "https://img.vietqr.io/image/{$bankId}-{$accountNo}-qr_only.png"
               . "?amount={$amount}"
               . "&addInfo=" . rawurlencode($content)
               . "&accountName={$accountName}";

        $send['view']         = 'user.payment.vietqr';
        $send['qr_url']       = $qrUrl;
        $send['amount']       = $amount;
        $send['currency']     = $deposit->payment_method_currency;
        $send['bank_id']      = $bankId;
        $send['account_no']   = $accountNo;
        $send['account_name'] = $params->account_name ?? basicControl()->site_title;
        $send['content']      = $content;
        $send['trx_id']       = $deposit->trx_id;
        $send['timeout']      = 900; // giây — countdown timer hiển thị trên UI

        return json_encode($send);
    }

    /**
     * IPN / Webhook handler
     *
     * SePay payload (JSON POST):
     *   { "id", "gateway", "transactionDate", "accountNumber",
     *     "subAccount", "transferType", "transferAmount",
     *     "accumulated", "code", "content", "referenceCode",
     *     "description" }
     *
     * Casso payload (JSON POST):
     *   { "data": [{ "id","tid","description","amount","cusum_balance",
     *                "when","bank_sub_acc_id","subAccId","bankName",
     *                "bankAbbreviation","corresponsiveName",
     *                "corresponsiveAccount","corresponsiveBankId",
     *                "corresponsiveBankName" }] }
     */
    public static function ipn($request, $gateway, $deposit = null, $trx = null, $type = null)
    {
        $params   = $gateway->parameters;
        $provider = $params->webhook_provider ?? 'sepay';
        $secret   = $params->webhook_secret ?? '';

        // ── Xác thực chữ ký ──────────────────────────────────────────────
        if (!self::verifySignature($request, $provider, $secret)) {
            Log::warning('VietQR IPN: invalid signature', ['provider' => $provider]);
            return ['redirect' => route('failed'), 'status' => 'error', 'msg' => 'Invalid signature.'];
        }

        // ── Lấy thông tin giao dịch từ payload ──────────────────────────
        [$amount, $content, $accountNo] = self::extractPayload($request, $provider);

        if (!$amount || !$content) {
            return ['redirect' => route('failed'), 'status' => 'error', 'msg' => 'Empty payload.'];
        }

        // ── Tìm deposit theo nội dung chuyển khoản ───────────────────────
        if (!$deposit) {
            $suffix  = strtoupper(substr($content, strlen(self::CONTENT_PREFIX)));
            $deposit = \App\Models\Deposit::with('user')
                ->where('status', 0)
                ->whereRaw("UPPER(RIGHT(trx_id, 8)) = ?", [$suffix])
                ->first();
        }

        if (!$deposit) {
            Log::info('VietQR IPN: deposit not found', ['content' => $content]);
            return ['redirect' => route('failed'), 'status' => 'error', 'msg' => 'Deposit not found.'];
        }

        // ── Kiểm tra số tiền (±1 VND dung sai làm tròn) ─────────────────
        if (abs($amount - round($deposit->payable_amount)) > 1) {
            Log::warning('VietQR IPN: amount mismatch', [
                'received' => $amount,
                'expected' => round($deposit->payable_amount),
            ]);
            return ['redirect' => route('failed'), 'status' => 'error', 'msg' => 'Amount mismatch.'];
        }

        // ── Nạp tiền ─────────────────────────────────────────────────────
        BasicService::preparePaymentUpgradation($deposit);

        return ['redirect' => route('success'), 'status' => 'success', 'msg' => 'Payment confirmed.'];
    }

    // ─────────────────────────────────────────────────────────────────────
    private static function verifySignature($request, string $provider, string $secret): bool
    {
        if (empty($secret)) {
            return true; // Không bật xác thực — chấp nhận tất cả (chỉ dùng khi dev)
        }

        if ($provider === 'sepay') {
            // SePay gửi header: Authorization: Apikey <secret>
            $authHeader = $request->header('Authorization', '');
            return $authHeader === "Apikey {$secret}";
        }

        if ($provider === 'casso') {
            // Casso gửi header: secure-token: <secret>
            return $request->header('secure-token', '') === $secret;
        }

        return false;
    }

    private static function extractPayload($request, string $provider): array
    {
        $body = $request->all();

        if ($provider === 'sepay') {
            return [
                (int) ($body['transferAmount'] ?? 0),
                strtoupper(trim($body['content'] ?? '')),
                $body['accountNumber'] ?? '',
            ];
        }

        if ($provider === 'casso') {
            $item = collect($body['data'] ?? [])->first();
            return [
                (int) ($item['amount'] ?? 0),
                strtoupper(trim($item['description'] ?? '')),
                $item['bank_sub_acc_id'] ?? '',
            ];
        }

        return [0, '', ''];
    }
}
