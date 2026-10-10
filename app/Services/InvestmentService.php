<?php

namespace App\Services;

use App\Models\Property;
use App\Models\Transaction;
use App\Traits\ApiResponse;
use App\Traits\Notify;
use App\Traits\Upload;
use App\Models\Investment;
use App\Models\User;
use Carbon\Carbon;
use Facades\App\Services\NotifyMailService;
use Facades\App\Services\BasicService;
use Illuminate\Support\Facades\DB;

class InvestmentService
{
    use ApiResponse, Upload, Notify;

    /**
     * @param $property
     * @param $request
     */

    public $net_profit, $net_loss;


    public function profitLossCalculate($request = null, $property = null)
    {
        // PROFIT Calculation
        if ($property->profit_type == 1 && $property->is_invest_type == 1 && $property->available_funding != $request->amount) {
            $this->net_profit = ($property->fixed_amount * $property->profit) / 100;
        } elseif ($property->profit_type == 1 && $property->is_invest_type == 1 && $property->available_funding == $request->amount) {
            $this->net_profit = ($request->amount * $property->profit) / 100;
        } elseif ($property->profit_type == 1 && $property->is_invest_type == 0) {
            $this->net_profit = ($request->amount * $property->profit) / 100;
        } else {
            $this->net_profit = $property->profit;
        }

        // LOSS Calculation
        if ($property->loss_type == 1 && $property->is_invest_type == 1 && $property->available_funding != $request->amount) {
            $this->net_loss = ($property->fixed_amount * $property->loss) / 100;
        } elseif ($property->loss_type == 1 && $property->is_invest_type == 1 && $property->available_funding == $request->amount) {
            $this->net_loss = ($request->amount * $property->loss) / 100;
        } elseif ($property->loss_type == 1 && $property->is_invest_type == 0) {
            $this->net_loss = ($request->amount * $property->loss) / 100;
        } else {
            $this->net_loss = $property->loss;
        }
    }

    public function forAllInvest($user, $property, $amount, $pay_installment, $trx)
    {
        DB::beginTransaction();

        try {
            $net_profit = $this->net_profit;
            $net_loss = $this->net_loss;

            // For Fixed invest
            if (($property->fixed_amount != null && ($property->fixed_amount == $amount || $property->available_funding == $amount)) && $pay_installment == null) {
                $investStatus = 1;
                $is_installment = 0;
                BasicService::makeInvest($user, $property, $amount, $net_profit, $net_loss, $investStatus, $is_installment, $trx);

            // For installment invest
            } elseif (($pay_installment != null && $amount == $property->installment_amount) && $property->fixed_amount != null) {
                $investStatus = 0;
                $is_installment = 1;
                BasicService::makeInvest($user, $property, $amount, $net_profit, $net_loss, $investStatus, $is_installment, $trx);

            // For range invest
            } elseif ($property->fixed_amount == null) {
                $investStatus = 1;
                $is_installment = 0;
                BasicService::makeInvest($user, $property, $amount, $net_profit, $net_loss, $investStatus, $is_installment, $trx);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
        }
    }

    public function dueInstallmentPayment($user, $request, $pay_installment, $investment, $amount, $balance_type)
    {
        DB::beginTransaction();
        try {
            if ($pay_installment != null) {
                if ($investment->getInstallmentDate() == 0) {
                    $installmentAmount = optional($investment->property)->installment_amount;
                    $installmentLateFee = optional($investment->property)->installment_late_fee;
                    $totalInstallmentAmount = $installmentAmount + $installmentLateFee;
                    if ($amount > $totalInstallmentAmount || $amount < $totalInstallmentAmount) {
                        if ($request->wantsJson()) {
                            return response()->json($this->withError('Invalid amount request. Please pay ' . $totalInstallmentAmount));
                        } else {
                            return back()->with('error', 'Invalid amount request. Please pay ' . $totalInstallmentAmount);
                        }
                    }
                } else {
                    $installmentAmount = optional($investment->property)->installment_amount;
                    if ($amount > $installmentAmount || $amount < $installmentAmount) {
                        if ($request->wantsJson()) {
                            return response()->json($this->withError('Invalid amount request. please pay ' . $installmentAmount));
                        } else {
                            return back()->with('error', 'Invalid amount request. please pay ' . $installmentAmount);
                        }
                    }
                }
            } else {
                if ($investment->getInstallmentDate() == 0) {
                    $dueAmount = (int)optional($investment->property)->fixed_amount - (int)$investment->amount;
                    $installmentLateFee = optional($investment->property)->installment_late_fee;
                    $totalDueAmountWithLateFee = $dueAmount + $installmentLateFee;
                    if ($amount > $totalDueAmountWithLateFee || $amount < $totalDueAmountWithLateFee) {
                        if ($request->wantsJson()) {
                            return response()->json($this->withError('Invalid amount request. please pay ' . $totalDueAmountWithLateFee));
                        } else {
                            return back()->with('error', 'Invalid amount request. please pay ' . $totalDueAmountWithLateFee);
                        }
                    }
                } else {
                    $dueAmount = (int)optional($investment->property)->fixed_amount - (int)$investment->amount;
                    if ($amount > $dueAmount || $amount < $dueAmount) {
                        if ($request->wantsJson()) {
                            return response()->json($this->withError('Invalid amount request. please pay ' . $dueAmount));
                        } else {
                            return back()->with('error', 'Invalid amount request. please pay ' . $dueAmount);
                        }
                    }
                }
            }

            if ($pay_installment != null) {
                $subWithAvailableFund = optional($investment->property)->installment_amount;
                $investment->amount += optional($investment->property)->installment_amount;
                if ($investment->getInstallmentDate() == 0) {
                    $investment->total_installment_late_fee += optional($investment->property)->installment_late_fee;
                }
            } else {
                $totalDueAmount = (int)optional($investment->property)->fixed_amount - (int)$investment->amount;
                $subWithAvailableFund = $totalDueAmount;
                $investment->amount += $totalDueAmount;

                if ($investment->getInstallmentDate() == 0) {
                    $investment->total_installment_late_fee += optional($investment->property)->installment_late_fee;
                }
            }

            if ($pay_installment == null) {
                $dueInstallments = $investment->due_installments - $investment->due_installments;
            } else {
                $dueInstallments = $investment->due_installments - 1;
            }

            if ($dueInstallments == 0) {
                $investment->invest_status = 1;
                $investment->next_installment_date_start = null;
                $investment->next_installment_date_end = null;
                $returnTimeType = strtolower($investment->property->managetime->time_type);
                $returnTime = $investment->property->managetime->time;
                $func = $returnTimeType == 'days' ? 'addDays' : ($returnTimeType == 'months' ? 'addMonths' : 'addYears');
                $returnDate = now()->$func($returnTime);
                $investment->return_date = $returnDate;
            } else {
                $previousInstallmentLastDate = $investment->next_installment_date_end;
                $func2 = strtolower(optional($investment->property)->installment_duration_type) == 'days' ? 'addDays' : (strtolower(optional($investment->property)->installment_duration_type) == 'months' ? 'addMonths' : 'addYears');
                $nextInstallmentTimeStart = Carbon::parse($previousInstallmentLastDate)->$func2(optional($investment->property)->installment_duration);
                $nextInstallmentTimeEnd = $nextInstallmentTimeStart->copy()->$func2(optional($investment->property)->installment_duration);
                $investment->next_installment_date_start = $nextInstallmentTimeStart;
                $investment->next_installment_date_end = $nextInstallmentTimeEnd;
            }

            $investment->due_installments = $dueInstallments;
            $investment->save();

            $new_balance = $user->$balance_type - $amount; // new balance = user new balance
            $user->$balance_type = $new_balance;
            $user->total_invest += $request->amount;
            $user->save();

            $manageProperty = Property::findOrFail(optional($investment->property)->id);
            $new_available_fund = (int)$manageProperty->available_funding - $subWithAvailableFund;
            $manageProperty->available_funding = $new_available_fund;
            $manageProperty->save();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            info($e->getMessage());
        }

    }

    public function investmentProfitReturn($investment, $nextReturnDate, $lastReturnDate, $now, $basic, $amount)
    {
        DB::beginTransaction();
        try {

            // $investment = Investment::with(['user', 'property:id,title'])->where('id', $investment->id)->first();
            $user = $investment->user;
            $property = $investment->property;

            if (!$investment || !$user || !$property) {
                info('Investment, User, or Property not found');
            }

            if ($investment->how_many_times === null) {
                $investment->return_date = $nextReturnDate;       // next profit return date
                $investment->last_return_date = $lastReturnDate;  // last profit return date
                $investment->save();

                // Update user's interest balance
                $user->interest_balance += $amount;
                $user->total_interest_balance += $amount;
                $user->save();

                // Create transaction and send notification
                $remarks = currencyPosition($amount) . ' Interest From ' . $property->title;
                $transaction = BasicService::makeTransaction($user, $amount, 0, '+', 'interest_balance', strRandom(), $remarks, $property, $investment);

                NotifyMailService::investmentProfit($user, $investment, $basic, $transaction, $amount);
            } elseif ($investment->how_many_times !== null && $investment->how_many_times > 0) {
                $investment->return_date = $nextReturnDate;  // next profit return date
                $investment->last_return_date = $now;        // last profit return date
                $investment->how_many_times -= 1;
                $investment->save();

                // Update user's interest balance
                $user->interest_balance += $amount;
                $user->total_interest_balance += $amount;
                $user->save();

                // Create transaction and send notification
                $remarks = currencyPosition($amount) . ' Interest From ' . $property->title;
                $transaction = BasicService::makeTransaction($user, $amount, 0, '+', 'interest_balance', strRandom(), $remarks, $property, $investment);

                NotifyMailService::investmentProfit($user, $investment, $basic, $transaction, $amount);

                if ($investment->how_many_times === 0) {
                    $investment->return_date = null;
                    $investment->status = 1;
                    $investment->save();

                    if ($investment->capital_back == 1) {
                        $capital = $investment->amount;
                        $user->interest_balance += $capital;
                        $user->save();

                        $remarks = currencyPosition($capital) . ' Capital Back From ' . $property->title;
                        $transaction = BasicService::makeTransaction($user, $capital, 0, '+', 'interest_balance', strRandom(), $remarks, $property, $investment);

                        NotifyMailService::investmentCapitalBack($user, $investment, $basic, $transaction, $capital);
                    }
                }
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            info($e->getMessage());
        }
    }

}
