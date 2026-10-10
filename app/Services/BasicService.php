<?php

namespace App\Services;


use App\Models\Deposit;
use App\Models\Investment;
use App\Models\Rank;
use App\Models\Referral;
use App\Models\ReferralBonus;
use App\Models\Transaction;
use App\Models\User;
use App\Traits\Notify;
use Carbon\Carbon;
use GPBMetadata\Google\Api\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class BasicService
{
    use Notify;

    public function setEnv($value)
    {
        $envPath = base_path('.env');
        $env = file($envPath);
        foreach ($env as $env_key => $env_value) {
            $entry = explode("=", $env_value, 2);
            $env[$env_key] = array_key_exists($entry[0], $value) ? $entry[0] . "=" . $value[$entry[0]] . "\n" : $env_value;
        }
        $fp = fopen($envPath, 'w');
        fwrite($fp, implode($env));
        fclose($fp);
    }

    public function preparePaymentUpgradation($deposit)
    {
        try {
            if ($deposit->status == 0 ||$deposit->status == 2) {
                $deposit->status = 1;
                $deposit->save();

                if ($deposit->user) {
                    $user = $deposit->user;
                    $user->balance += $deposit->payable_amount_in_base_currency;
                    $user->total_deposit += $deposit->payable_amount_in_base_currency;
                    $user->save();

                    $transaction = new Transaction();
                    $transaction->user_id = $deposit->user_id;
                    $transaction->amount = $deposit->payable_amount_in_base_currency;
                    $transaction->charge = getAmount($deposit->base_currency_charge);
                    $transaction->balance = getAmount($user->balance);
                    $transaction->trx_type = '+';
                    $transaction->trx_id = $deposit->trx_id;
                    $transaction->remarks = 'Deposit Via ' . optional($deposit->gateway)->name;
                    $deposit->transactional()->save($transaction);
                    $deposit->save();

                    if (basicControl()->is_first_deposit_bonus == 1 && $user->is_bonus_amount == 0) {
                        $userFirstDeposit = Deposit::where('user_id', $user->id)->where('status', 1)->count();
                        if ($userFirstDeposit == 1 && $deposit->payable_amount_in_base_currency >= basicControl()->minimum_first_deposit) {
                            $user->balance += basicControl()->first_deposit_bonus;
                            $user->is_bonus_amount = 1;
                            $user->save();
                            $this->makeTransaction($user, getAmount(basicControl()->first_deposit_bonus), 0, $trx_type = '+', $balance_type = 'balance', $bonus_type = 'First Deposit', $deposit->trx_id, $remarks = 'First deposit bonus');
                        }
                    }

                    $params = [
                        'amount' => currencyPosition($deposit->payable_amount_in_base_currency),
                        'transaction' => $deposit->trx_id,
                    ];

                    $action = [
                        "link" => "#",
                        "icon" => "fa fa-money-bill-alt text-white"
                    ];
                    $firebaseAction = '#';
                    $this->sendMailSms($deposit->user, 'ADD_FUND_USER_USER', $params);
                    $this->userPushNotification($deposit->user, 'ADD_FUND_USER_USER', $params, $action);
                    $this->userFirebasePushNotification($deposit->user, 'ADD_FUND_USER_USER', $params, $firebaseAction);

                    $params = [
                        'user' => optional($deposit->user)->username,
                        'amount' => getAmount($deposit->amount),
                        'currency' => $deposit->payment_method_currency,
                        'transaction' => $deposit->trx_id,
                    ];
                    $actionAdmin = [
                        "name" => optional($deposit->user)->firstname . ' ' . optional($deposit->user)->lastname,
                        "image" => getFile(optional($deposit->user)->image_driver, optional($deposit->user)->image),
                        "link" => "#",
                        "icon" => "fas fa-ticket-alt text-white"
                    ];

                    $firebaseAction = "#";
                    $this->adminMail('ADD_FUND_USER_ADMIN', $params, $action);
                    $this->adminPushNotification('ADD_FUND_USER_ADMIN', $params, $actionAdmin);
                    $this->adminFirebasePushNotification('ADD_FUND_USER_ADMIN', $params, $firebaseAction);
                }

                return true;
            }
        } catch (\Exception $e) {
        }


    }

    public function cryptoQR($wallet, $amount, $crypto = null)
    {
        $cryptoQr = $wallet . "?amount=" . $amount;
        return "https://quickchart.io/chart?cht=qr&chl=$cryptoQr";
    }


    public function makeInvest($user, $property, $amount, $profit, $loss, $investStatus, $is_installment, $trx, $payment_status = 1)
    {
        $returnTimeType = strtolower($property->return_time_type);
        $func = $returnTimeType == 'days' ? 'addDays' : ($returnTimeType == 'months' ? 'addMonths' : 'addYears');
        $returnTime = (int) $property->return_time;
        $returnDate = Carbon::parse($property->expire_date)->$func($returnTime);

        $func2 = strtolower($property->installment_duration_type) == 'days' ? 'addDays' : (strtolower($property->installment_duration_type) == 'months' ? 'addMonths' : 'addYears');
        $nextInstallmentTimeStart = now()->$func2($property->installment_duration);
        $nextInstallmentTimeEnd = $nextInstallmentTimeStart->copy()->$func2($property->installment_duration);


        if ($property->total_installments && $investStatus == 0) {
            $dueInstallments = $property->total_installments - 1;
            if ($dueInstallments == 0) {
                $investStatus = 1;
            }
        } else {
            $dueInstallments = null;
        }

        $invest = new Investment();
        $invest->user_id = $user->id;
        $invest->property_id = $property->id;
        $invest->amount = $amount;
        $invest->profit = $property->profit;
        $invest->profit_type = $property->profit_type;
        $invest->net_profit = $profit;
        $invest->loss = $property->loss;
        $invest->loss_type = $property->loss_type;
        $invest->net_loss = $loss;
        $invest->is_return_type = $property->is_return_type;
        $invest->return_time = $returnTime;
        $invest->return_time_type = $returnTimeType;
        $invest->return_date = ($dueInstallments != 0 && $dueInstallments != null ? null : $returnDate);
        $invest->how_many_times = ($property->how_many_times == null ? null : $property->how_many_times);
        $invest->last_return_date = null;
        $invest->is_installment = ($is_installment == 1 ? $property->is_installment : 0);
        $invest->total_installments = ($is_installment == 1 ? $property->total_installments : null);
        $invest->due_installments = ($is_installment == 1 ? $property->total_installments - 1 : null);
        $invest->next_installment_date_start = ($is_installment == 1 ? $nextInstallmentTimeStart : null);
        $invest->next_installment_date_end = ($is_installment == 1 ? $nextInstallmentTimeEnd : null);
        $invest->invest_status = $investStatus;
        $invest->payment_status = $payment_status;
        $invest->status = 0;
        $invest->capital_back = $property->is_capital_back;
        $invest->trx = $trx;
        $invest->save();

        if ($amount > $property->available_funding) {
            $property->available_funding = $property->available_funding - $property->available_funding;
            $property->save();
        } else {
            $property->available_funding = $property->available_funding - $amount;
            $property->save();
        }
    }

    public function getInvestorCurrentRank($user)
    {

        $interestBalance = $user->total_interest_balance;
        $investBalance = $user->total_invest;
        $depositBalance = $user->total_deposit;

        $ranks = Rank::where([
            ['min_invest', '<=', $investBalance],
            ['min_deposit', '<=', $depositBalance],
            ['min_earning', '<=', $interestBalance]])->where('status', 1)->get();

        $userPreviousRank = (json_decode($user->all_ranks) == null ? [] : json_decode($user->all_ranks));

        if (count($ranks) > 0) {
            $userRank = [
                'min_invest' => '0',
                'min_deposit' => '0',
                'min_earning' => '0'
            ];

            $allRanks = [];

            foreach ($ranks as $rank) {
                $userRank = (object)$userRank;
                if (($userRank->min_invest <= $rank->min_invest) && ($userRank->min_deposit <= $rank->min_deposit) && ($userRank->min_earning <= $rank->min_earning)) {
                    $userRank = $rank;
                    $user->last_level = $userRank->id;
                    $allRanks [] = $userRank->id;
                    $user->save();
                }
            }

            $recentUpdateRanks = array_diff($allRanks, $userPreviousRank);

            $isRankBonus = basicControl()->is_rank_bonus;
            $basicControl = basicControl();

            if (count($recentUpdateRanks) > 0 && $isRankBonus) {
                foreach ($recentUpdateRanks as $recentRank) {
                    $singleRank = Rank::find($recentRank);
                    $user->total_rank_bonous = (int)$user->total_rank_bonous + (int)$singleRank->bonus;
                    $user->interest_balance = (int)$user->interest_balance + (int)$singleRank->bonus;
                    $user->total_interest_balance = (int)$user->total_interest_balance + (int)$singleRank->bonus;
                    $user->save();

                    $amount = $singleRank->bonus;
                    $trx = strRandom();
                    $remarks = 'You got ' . $basicControl->currency_symbol . $singleRank->bonus . ' ' . $singleRank->rank_level . ' ranking bonus';
                    BasicService::makeTransaction($user, $amount, 0, $trx_type = '+', 'interest_balance', $trx, $remarks);

                    if ($basicControl->rank_commission == 1) {
                        $this->setBonus($user, $singleRank->bonus, $type = 'rank_commission', $singleRank);
                    }
                }
            }

            $user->all_ranks = $allRanks;
            $user->save();
            return $userRank;
        } else {
            $userRank = null;
            return $userRank;
        }
    }


    public function makeTransaction($user, $amount, $charge, $trx_type = null, $balance_type, $trx_id, $remarks = null, $property = null, $investment = null)
    {
        $transaction = new Transaction();
        $transaction->user_id = $user->id;
        $transaction->property_id = $property ? $property->id : null;
        $transaction->investment_id = $investment ? $investment->id : null;
        $transaction->amount = getAmount($amount);
        $transaction->charge = $charge;
        $transaction->trx_type = $trx_type;
        $transaction->balance_type = $balance_type;
        $transaction->balance = $user[$balance_type];
        $transaction->trx_id = $trx_id;
        $transaction->remarks = $remarks;
        $transaction->transactional_id = $user->id;
        $transaction->transactional_type = get_class($user);
        $transaction->save();
        return $transaction;
    }


    public function setBonus($user, $amount, $commissionType = '', $model = null)
    {
        $userId = $user->id;
        $i = 1;

        $commissions = Referral::where('commission_type', $commissionType)
            ->orderBy('level', 'asc')
            ->get()
            ->keyBy('level');
        $levelCount = $commissions->count();
        if ($levelCount == 0) {
            return 0;
        }

        DB::beginTransaction();
        try {
            while ($i <= $levelCount) {
                $me = User::with('referral')->has('referral')->find($userId);
                $refer = $me?->referral;
                if (!$refer) {
                    break;
                }
                /*Get the commission for the current level*/
                $commission = $commissions->get($i);
                if (!$commission) {
                    break;
                }

                $extra_com = 0;
                if ($refer->premium_user == 1) {
                    $extra_com = ($amount * (int)$commission->extra_bonus) / 100;
                }

                $com = (($amount * $commission->percent) / 100) + $extra_com;
                $new_bal = getAmount($refer->interest_balance + $com);
                $refer->interest_balance = $new_bal;
                $refer->total_interest_balance += $com;

                $this->updateReferralBonusFields($refer, $com, $commissionType);
                $refer->save();

                $trx = strRandom();
                $balance_type = 'interest_balance';
                $remarks = ' level ' . $i . ' Referral bonus From ' . $user->username;

                $this->createReferralBonus($refer, $user, $com, $trx, $commissionType, $i, get_class($model), $model->id);
                $this->makeTransaction($refer, $com, 0, '+', $balance_type, $trx, $remarks, $property = null, $investment = $model);
                $this->sendReferralNotifications($refer, $trx, $com, $user, $i);

                $userId = $refer->id;
                $i++;
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error($e->getMessage());
        }
        return 0;
    }


    protected function updateReferralBonusFields($refer, $com, $commissionType)
    {
        switch ($commissionType) {
            case 'deposit':
                $refer->deposit_referral_bonous += $com;
                break;
            case 'invest':
                $refer->invest_referral_bonous += $com;
                break;
            case 'profit_commission':
                $refer->profit_referral_bonous += $com;
                break;
            case 'rank_commission':
                $refer->rank_referral_bonous += $com;
                break;
            case 'joining_bonus':
                $refer->total_referral_joining_bonus += $com;
                break;
        }
        $refer->total_referral_bonous += $com;
    }

    protected function createReferralBonus($refer, $user, $com, $trx, $commissionType, $level, $modelType = null, $modelId = null)
    {
        $bonus = new ReferralBonus();
        $bonus->from_user_id = $user->id;
        $bonus->to_user_id = $refer->id;
        $bonus->level = $level;
        $bonus->amount = getAmount($com);
        $bonus->main_balance = getAmount($refer->interest_balance);
        $bonus->transaction = $trx;
        $bonus->type = $commissionType;
        $bonus->remarks = 'level ' . $level . ' Referral bonus From ' . $user->username;
        $bonus->model_type = $modelType;
        $bonus->model_id = $modelId;
        $bonus->save();
    }

    protected function sendReferralNotifications($refer, $trx, $com, $user, $i)
    {
        $this->sendMailSms($refer, 'REFERRAL_BONUS', [
            'transaction_id' => $trx,
            'amount' => getAmount($com),
            'currency' => basicControl()->currency_symbol,
            'bonus_from' => $user->username,
            'final_balance' => $refer->interest_balance,
            'level' => $i
        ]);

        $msg = [
            'bonus_from' => $user->username,
            'amount' => getAmount($com),
            'currency' => basicControl()->currency_symbol,
            'level' => $i
        ];
        $action = [
            "link" => route('user.referral.bonus'),
            "icon" => "fa fa-money-bill-alt"
        ];
        $this->userPushNotification($refer, 'REFERRAL_BONUS', $msg, $action);
    }


}
