<?php

namespace App\Http\Controllers;

use App\Models\Deposit;
use App\Models\Gateway;
use App\Services\Gateway\nlpgroup\Payment as NlpGroupPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

/**
 * NLP Group Native Callback Controller
 *
 * Hai endpoint:
 *   POST /nlpgroup/callback  → nhận callback từ MB Bank (HMAC-SHA256 protected)
 *   GET  /nlpgroup/check/{trx_id} → polling trạng thái deposit (frontend + chủ động query MB Bank)
 */
class NlpGroupCallbackController extends Controller
{
    /**
     * MB Bank gọi vào đây sau mỗi giao dịch thành công.
     * Payload JSON:
     * {
     *   "transactionCode": "FT24123456789",
     *   "bankAccountNo":   "0123456789",
     *   "creditAmount":    500000,
     *   "description":     "NLP AB12CD34 chuyen khoan",
     *   "transactionTime": "2025-01-15 10:30:00",
     *   "referenceNumber": "REF001"
     * }
     */
    public function callback(Request $request)
    {
        $gateway = Gateway::where('code', 'nlpgroup')->where('status', 1)->first();

        if (!$gateway) {
            Log::warning('NLP Group callback: gateway not found or inactive');
            return response()->json(['code' => '01', 'message' => 'Gateway inactive'], 503);
        }

        // Delegate hoàn toàn sang Payment::ipn() — trả về JsonResponse
        return NlpGroupPayment::ipn($request, $gateway);
    }

    /**
     * Polling endpoint — frontend gọi mỗi 5 giây.
     * Nếu deposit chưa xác nhận (status=0) → chủ động query MB Bank Transaction API.
     * Trả { status: 0|1|-1 }
     */
    public function check(Request $request, string $trxId)
    {
        $deposit = Deposit::where('trx_id', $trxId)
            ->select('id', 'status', 'payment_method_id', 'payable_amount', 'trx_id')
            ->first();

        if (!$deposit) {
            return response()->json(['status' => -1], 404);
        }

        // Đã xác nhận rồi — trả về ngay
        if ($deposit->status == 1) {
            return response()->json(['status' => 1]);
        }

        // status=0 → chủ động query MB Bank để không phụ thuộc hoàn toàn vào callback
        $gateway = Gateway::where('code', 'nlpgroup')->first();
        if ($gateway) {
            $confirmed = NlpGroupPayment::pollTransaction($deposit, $gateway);
            if ($confirmed) {
                return response()->json(['status' => 1]);
            }
        }

        return response()->json(['status' => (int) $deposit->status]);
    }
}
