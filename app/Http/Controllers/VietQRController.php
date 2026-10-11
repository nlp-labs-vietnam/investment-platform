<?php

namespace App\Http\Controllers;

use App\Models\Deposit;
use Illuminate\Http\Request;

/**
 * VietQR helper controller — dùng cho polling từ frontend.
 * Trả JSON { status: 0|1 } để client biết giao dịch đã xác nhận chưa.
 */
class VietQRController extends Controller
{
    public function check(Request $request, string $trxId)
    {
        $deposit = Deposit::where('trx_id', $trxId)
            ->select('status')
            ->first();

        if (!$deposit) {
            return response()->json(['status' => -1], 404);
        }

        return response()->json(['status' => (int) $deposit->status]);
    }
}
