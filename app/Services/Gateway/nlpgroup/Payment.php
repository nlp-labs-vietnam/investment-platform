<?php

namespace App\Services\Gateway\nlpgroup;

use App\Models\Deposit;
use Facades\App\Services\BasicService;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * NLP Group Native VietQR Gateway
 *
 * Gateway nội bộ của NLP Group — xác nhận giao dịch trực tiếp qua
 * MB Bank Business API (không qua middleman SePay/Casso).
 *
 * Luồng:
 *   1. prepareData()      → Tạo QR code + gọi MB Bank API tạo payment request
 *   2. ipn()              → Nhận callback POST từ MB Bank → xác thực HMAC → nạp tiền
 *   3. pollTransaction()  → Polling chủ động qua MB Bank Transaction Inquiry API
 *
 * Cấu hình gateway (parameters trong DB):
 *   - mb_consumer_id     : MB Bank API Consumer ID (từ MB Business Portal)
 *   - mb_consumer_secret : MB Bank API Consumer Secret
 *   - mb_account_no      : Số tài khoản doanh nghiệp NLP Group (MB Bank)
 *   - mb_account_name    : Tên chủ tài khoản
 *   - callback_secret    : HMAC secret để xác thực callback từ MB Bank
 *
 * Callback URL đăng ký trên MB Bank portal:
 *   https://api.nlpgroup.vn/nlpgroup/callback
 *
 * MB Bank Sandbox: https://developers.mbbank.com.vn
 * MB Bank Live:    https://api.mbbank.com.vn
 */
class Payment
{
    private const CONTENT_PREFIX = 'NLP';

    // MB Bank API endpoints
    private const MB_SANDBOX_HOST = 'https://developers.mbbank.com.vn';
    private const MB_LIVE_HOST    = 'https://api.mbbank.com.vn';

    // ─────────────────────────────────────────────────────────────────────────
    // 1. PREPARE — Tạo QR code hiển thị cho user
    // ─────────────────────────────────────────────────────────────────────────
    public static function prepareData($deposit, $gateway)
    {
        $params      = $gateway->parameters;
        $accountNo   = $params->mb_account_no ?? '';
        $accountName = $params->mb_account_name ?? basicControl()->site_title;
        $isSandbox   = ($gateway->environment === 'test');

        // Nội dung CK = NLP + 8 ký tự cuối trx_id → nhận dạng không trùng
        $content = self::CONTENT_PREFIX . strtoupper(substr($deposit->trx_id, -8));
        $amount  = (int) round($deposit->payable_amount);

        // ── Lấy access token MB Bank ──────────────────────────────────────
        $accessToken = self::getMbAccessToken($params, $isSandbox);

        // ── Tạo Dynamic QR qua MB Bank API ───────────────────────────────
        $qrData = self::createMbQr($accessToken, $params, $amount, $content, $deposit->trx_id, $isSandbox);

        // Fallback: nếu MB API lỗi → dùng VietQR public image API
        if ($qrData['success']) {
            $qrUrl = $qrData['qr_data_url']; // base64 PNG hoặc URL từ MB
        } else {
            Log::warning('NLP Group: MB Bank QR API failed, using fallback', ['error' => $qrData['error']]);
            $bankBin = '970422'; // MB Bank BIN
            $qrUrl   = "https://img.vietqr.io/image/{$bankBin}-{$accountNo}-qr_only.png"
                     . "?amount={$amount}&addInfo=" . rawurlencode($content)
                     . "&accountName=" . rawurlencode($accountName);
        }

        $send['view']         = 'user.payment.nlpgroup';
        $send['qr_url']       = $qrUrl;
        $send['amount']       = $amount;
        $send['currency']     = $deposit->payment_method_currency;
        $send['account_no']   = $accountNo;
        $send['account_name'] = $accountName;
        $send['bank_name']    = 'MB Bank';
        $send['bank_logo']    = 'https://api.vietqr.io/img/MB.png';
        $send['content']      = $content;
        $send['trx_id']       = $deposit->trx_id;
        $send['timeout']      = 900;
        $send['is_sandbox']   = $isSandbox;

        return json_encode($send);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // 2. IPN — Nhận callback POST từ MB Bank
    // ─────────────────────────────────────────────────────────────────────────
    public static function ipn($request, $gateway, $deposit = null, $trx = null, $type = null)
    {
        $params = $gateway->parameters;
        $secret = $params->callback_secret ?? '';

        // ── Xác thực HMAC-SHA256 ─────────────────────────────────────────
        if (!self::verifyMbCallback($request, $secret)) {
            Log::warning('NLP Group IPN: HMAC verification failed', [
                'ip' => $request->ip(),
            ]);
            return response()->json(['code' => '01', 'message' => 'Invalid signature'], 401);
        }

        // ── Parse payload MB Bank callback ──────────────────────────────
        // MB Bank callback payload:
        // { "transactionCode", "bankAccountNo", "creditAmount",
        //   "description", "transactionTime", "referenceNumber" }
        $payload = $request->all();
        $amount  = (int) ($payload['creditAmount'] ?? 0);
        $content = strtoupper(trim($payload['description'] ?? ''));
        $refCode = $payload['transactionCode'] ?? '';

        if (!$amount || !$content) {
            return response()->json(['code' => '01', 'message' => 'Empty payload'], 400);
        }

        // ── Tìm deposit ──────────────────────────────────────────────────
        if (!$deposit) {
            $suffix  = strtoupper(substr($content, strpos($content, self::CONTENT_PREFIX)));
            $suffix  = substr($suffix, strlen(self::CONTENT_PREFIX), 8);
            $deposit = Deposit::with('user')
                ->where('status', 0)
                ->whereRaw("UPPER(RIGHT(trx_id, 8)) = ?", [$suffix])
                ->first();
        }

        if (!$deposit) {
            Log::info('NLP Group IPN: deposit not found', ['content' => $content, 'ref' => $refCode]);
            // Vẫn trả 200 để MB Bank không retry
            return response()->json(['code' => '00', 'message' => 'Transaction noted']);
        }

        // ── Kiểm tra số tiền ─────────────────────────────────────────────
        if (abs($amount - round($deposit->payable_amount)) > 1) {
            Log::warning('NLP Group IPN: amount mismatch', [
                'received' => $amount,
                'expected' => round($deposit->payable_amount),
                'trx'      => $deposit->trx_id,
            ]);
            return response()->json(['code' => '01', 'message' => 'Amount mismatch'], 422);
        }

        // ── Xác nhận nạp tiền ────────────────────────────────────────────
        BasicService::preparePaymentUpgradation($deposit);

        // MB Bank yêu cầu response code 00 = thành công
        return response()->json(['code' => '00', 'message' => 'Success']);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // 3. POLL — Chủ động truy vấn lịch sử GD từ MB Bank (dùng bởi polling endpoint)
    // ─────────────────────────────────────────────────────────────────────────
    public static function pollTransaction(Deposit $deposit, $gateway): bool
    {
        try {
            $params    = $gateway->parameters;
            $isSandbox = ($gateway->environment === 'test');
            $token     = self::getMbAccessToken($params, $isSandbox);
            $host      = $isSandbox ? self::MB_SANDBOX_HOST : self::MB_LIVE_HOST;
            $content   = self::CONTENT_PREFIX . strtoupper(substr($deposit->trx_id, -8));

            $response = Http::withToken($token)
                ->timeout(10)
                ->get("{$host}/ms/mb-ebank-sx/transaction-inquiry/v1.0/transactions", [
                    'accountNo'   => $params->mb_account_no,
                    'fromDate'    => now()->subMinutes(30)->format('Y-m-d'),
                    'toDate'      => now()->format('Y-m-d'),
                    'description' => $content,
                ]);

            if (!$response->successful()) {
                return false;
            }

            $transactions = $response->json('transactions') ?? [];
            foreach ($transactions as $tx) {
                $txAmount  = (int) ($tx['creditAmount'] ?? 0);
                $txContent = strtoupper($tx['description'] ?? '');
                if (
                    str_contains($txContent, $content)
                    && abs($txAmount - round($deposit->payable_amount)) <= 1
                    && $deposit->status == 0
                ) {
                    BasicService::preparePaymentUpgradation($deposit);
                    return true;
                }
            }
        } catch (\Exception $e) {
            Log::error('NLP Group poll error: ' . $e->getMessage());
        }
        return false;
    }

    // ─────────────────────────────────────────────────────────────────────────
    // PRIVATE HELPERS
    // ─────────────────────────────────────────────────────────────────────────

    private static function getMbAccessToken($params, bool $isSandbox): string
    {
        $host = $isSandbox ? self::MB_SANDBOX_HOST : self::MB_LIVE_HOST;
        try {
            $response = Http::asForm()
                ->timeout(8)
                ->post("{$host}/ms/mb-ebank-sx/oauth2/v1.0/token", [
                    'grant_type'    => 'client_credentials',
                    'client_id'     => $params->mb_consumer_id ?? '',
                    'client_secret' => $params->mb_consumer_secret ?? '',
                ]);
            return $response->json('access_token') ?? '';
        } catch (\Exception $e) {
            Log::warning('NLP Group: MB token error: ' . $e->getMessage());
            return '';
        }
    }

    private static function createMbQr(string $token, $params, int $amount, string $content, string $trxId, bool $isSandbox): array
    {
        if (empty($token)) {
            return ['success' => false, 'error' => 'No access token'];
        }
        $host = $isSandbox ? self::MB_SANDBOX_HOST : self::MB_LIVE_HOST;
        try {
            $response = Http::withToken($token)
                ->timeout(10)
                ->post("{$host}/ms/mb-ebank-sx/qr-generate/v1.0/generate", [
                    'bankAccountNo' => $params->mb_account_no ?? '',
                    'amount'        => $amount,
                    'description'   => $content,
                    'orderId'       => $trxId,
                    'callbackUrl'   => rtrim(config('app.api_url'), '/') . '/nlpgroup/callback',
                ]);

            if ($response->successful() && $response->json('qrDataURL')) {
                return ['success' => true, 'qr_data_url' => $response->json('qrDataURL')];
            }
            return ['success' => false, 'error' => $response->body()];
        } catch (\Exception $e) {
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    private static function verifyMbCallback($request, string $secret): bool
    {
        if (empty($secret)) {
            return true; // dev mode — bỏ qua xác thực
        }
        // MB Bank gửi header X-MB-Signature: HMAC-SHA256(rawBody, secret)
        $signature = $request->header('X-MB-Signature', '');
        $rawBody   = $request->getContent();
        $expected  = hash_hmac('sha256', $rawBody, $secret);
        return hash_equals($expected, $signature);
    }
}
