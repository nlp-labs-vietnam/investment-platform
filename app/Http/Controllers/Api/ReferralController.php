<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Traits\ApiResponse;
use Illuminate\Http\Request;

class ReferralController extends Controller
{
    use ApiResponse;

    public function referral()
    {
        $user = auth()->user();
        $referrals = getLevelUser($user->id);

        $formattedReferrals = collect($referrals)->map(function ($referral, $key) {
            return [
                'level_text' => 'Level',
                'level_number' => $key,
                'referral_link' => route('register.sponsor',[auth()->user()->username]),
                'user' => $referral->map(function ($user) {
                    return [
                        'id' => $user->id,
                        'name' => $user->fullname,
                        'username' => $user->username,
                        'email' => $user->email,
                        'phone' => $user->phone,
                        'joined_at' => customDate($user->created_at),
                    ];
                }),
            ];
        })->values();

        return response()->json($this->withSuccess($formattedReferrals));

    }

    public function referralBonus()
    {
        $user = auth()->user();
        $referralBonuses = $user->referralBonusLog()
            ->with('bonusBy')
            ->orderBy('id', 'DESC')
            ->paginate(basicControl()->paginate);



        $totalReferralTransaction = $referralBonuses->groupBy('type')->mapWithKeys(function ($group, $type) {
            return [$type => $group->sum('amount')];
        })->toArray();

        $totalReferralTransaction['total_referral_bonus'] = array_sum($totalReferralTransaction);

        $bonusTypes = ['joining_bonus', 'deposit', 'invest', 'profit_commission', 'rank'];
        foreach ($bonusTypes as $type) {
            $totalReferralTransaction[$type] = fractionNumber(getAmount($totalReferralTransaction[$type] ?? 0));
        }

        $totalReferralTransaction['base_currency'] = basicControl()->base_currency;
        $totalReferralTransaction['currency_symbol'] = basicControl()->currency_symbol;


        $referralBonuses->getCollection()->transform(function ($referralBonus, $key) {
            return [
                'sl' => $key + 1,
                'id' => $referralBonus->id,
                'transaction_id' => $referralBonus->transaction,
                'bonus_from' => $referralBonus->bonusBy ? $referralBonus->bonusBy->fullname : null,
                'amount' => fractionNumber(getAmount($referralBonus->amount)).' '.basicControl()->base_currency,
                'remarks' => $referralBonus->remarks,
                'bonus_type' => $referralBonus->type,
                'bonus_level' => $referralBonus->level,
                'time' => customDate($referralBonus->created_at),
            ];
        });



        $data['referral_commissions'] = $totalReferralTransaction;

        $data['referral_bonuses'] = $referralBonuses;

        return response()->json($this->withSuccess($data));
    }

}
