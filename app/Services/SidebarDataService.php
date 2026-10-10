<?php

namespace App\Services;


use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class SidebarDataService
{
    public static function getSidebarCounts(): object
    {

        try {

            $userCounts = DB::table('users')
                ->select([
                    DB::raw('COUNT(CASE WHEN status = 1 THEN 1 END) as active_users'),
                    DB::raw('COUNT(CASE WHEN status = 0 THEN 1 END) as blocked_users'),
                    DB::raw('COUNT(CASE WHEN email_verification = 0 THEN 1 END) as email_unverified'),
                    DB::raw('COUNT(CASE WHEN sms_verification = 0 THEN 1 END) as sms_unverified'),
                    DB::raw('COUNT(CASE WHEN balance > 0 THEN 1 END) as users_with_balance')
                ])
                ->first();

            $kycCounts = DB::table('user_kycs')
                ->select([
                    DB::raw('COUNT(CASE WHEN status = 0 THEN 1 END) as kyc_pending'),
                    DB::raw('COUNT(CASE WHEN status = 1 THEN 1 END) as kyc_verified'),
                    DB::raw('COUNT(CASE WHEN status = 2 THEN 1 END) as kyc_rejected'),
                ])
                ->first();

            $depositCounts = DB::table('deposits')
                ->select([
                    DB::raw('COUNT(CASE WHEN status = 2 THEN 1 END) as deposit_pending'),
                    DB::raw('COUNT(CASE WHEN status = 3 THEN 1 END) as deposit_rejected')
                ])
                ->first();

            $payoutCounts = DB::table('payouts')
                ->select([
                    DB::raw('COUNT(CASE WHEN status = 1 THEN 1 END) as payout_pending'),
                    DB::raw('COUNT(CASE WHEN status = 2 THEN 1 END) as payout_approved')
                ])
                ->first();

            return (object)array_merge((array)$userCounts, (array)$kycCounts, (array)$depositCounts, (array)$payoutCounts);

        } catch (\Throwable $e) {
            Log::error('Error fetching sidebar counts: ' . $e->getMessage());
            return (object)[
                'active_users' => 0,
                'blocked_users' => 0,
                'email_unverified' => 0,
                'sms_unverified' => 0,
                'users_with_balance' => 0,
                'kyc_pending' => 0,
                'kyc_verified' => 0,
                'kyc_rejected' => 0,
                'deposit_pending' => 0,
                'deposit_rejected' => 0,
                'payout_pending' => 0,
                'payout_approved' => 0,
            ];
        }

    }
}
