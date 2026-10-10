<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MoneyTransfer;
use App\Models\Transaction;
use App\Models\User;
use App\Traits\ApiResponse;
use App\Traits\Notify;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class MoneyTransferController extends Controller
{
    use ApiResponse, Notify;

    public function moneyTransfer()
    {
        $basic = basicControl();
        $user = \auth()->user();
        $authUserBalance = [
            'currency_symbol' => $basic->currency_symbol,
            'base_currency' => $basic->base_currency,
            'user_main_balance' => getAmount($user->balance),
            'user_interest_balance' => $basic->currency_symbol . getAmount($user->interest_balance),
        ];

        return response()->json($this->withSuccess($authUserBalance));
    }

    public function moneyTransferStore(Request $request)
    {
        $rules = [
            'email' => 'required',
            'amount' => 'required',
            'wallet_type' => ['required', Rule::in(['balance', 'interest_balance'])],
            'password' => 'required'
        ];

        $message = [
            'wallet_type.required' => 'Please select a wallet'
        ];

        $validate = Validator::make($request->all(), $rules, $message);

        if ($validate->fails()) {
            return response()->json($this->withError(collect($validate->errors())->collapse()));
        }

        try {
            $basic = basicControl();
            $email = trim($request->email);

            $receiver = User::where('email', $email)->first();

            if (!$receiver) {
                return response()->json($this->withError('This Email could not Found!'));
            }

            if ($receiver->id == Auth::id()) {
                return response()->json($this->withError('This Email could not Found!'));
            }

            if ($receiver->status == 0) {
                return response()->json($this->withError('Invalid User!'));
            }


            if ($request->amount < $basic->min_transfer) {
                return response()->json($this->withError('Minimum Transfer Amount ' . $basic->min_transfer . ' ' . $basic->base_currency));
            }

            if ($request->amount > $basic->max_transfer) {
                return response()->json($this->withError('Maximum Transfer Amount ' . $basic->max_transfer . ' ' . $basic->base_currency));
            }

            $transferCharge = ($request->amount * $basic->transfer_charge) / 100;

            $user = Auth::user();
            $wallet_type = $request->wallet_type;
            if ($user[$wallet_type] >= ($request->amount + $transferCharge)) {

                if (Hash::check($request->password, $user->password)) {

                    $sendMoneyCheck = MoneyTransfer::where('sender_id', $user->id)->where('receiver_id', $receiver->id)->latest()->first();


                    if (isset($sendMoneyCheck) && \Illuminate\Support\Carbon::parse($sendMoneyCheck->send_at) > Carbon::now()) {

                        $time = $sendMoneyCheck->send_at;
                        $delay = $time->diffInSeconds(Carbon::now());
                        $delay = gmdate('i:s', $delay);

                        return response()->json($this->withError("You can send money to this user after  delay ' . $delay . ' minutes"));
                    } else {
                        $user[$wallet_type] = round(($user[$wallet_type] - ($transferCharge + $request->amount)), 2);
                        $user->save();

                        $receiver[$wallet_type] += round($request->amount, 2);
                        $receiver->save();

                        $trans = strRandom();

                        $sendTaka = new MoneyTransfer();
                        $sendTaka->sender_id = $user->id;
                        $sendTaka->receiver_id = $receiver->id;
                        $sendTaka->amount = round($request->amount, 2);
                        $sendTaka->charge = $transferCharge;
                        $sendTaka->trx = $trans;
                        $sendTaka->send_at = now();
                        $sendTaka->save();

                        $transaction = new Transaction();
                        $transaction->user_id = $user->id;
                        $transaction->amount = round($request->amount, 2);
                        $transaction->charge = $transferCharge;
                        $transaction->trx_type = '-';
                        $transaction->balance_type = $wallet_type;
                        $transaction->remarks = 'Balance Transfer to  ' . $receiver->email;
                        $transaction->trx_id = $trans;
                        $transaction->balance = $user[$wallet_type];

                        $sendTaka->transactional()->save($transaction);

                        $transaction = new Transaction();
                        $transaction->user_id = $receiver->id;
                        $transaction->amount = round($request->amount, 2);
                        $transaction->charge = 0;
                        $transaction->trx_type = '+';
                        $transaction->balance_type = $wallet_type;
                        $transaction->remarks = 'Balance Transfer From  ' . $user->email;
                        $transaction->trx_id = $trans;
                        $transaction->balance = $receiver[$wallet_type];
                        $sendTaka->transactional()->save($transaction);

                        $msg = [
                            'send_user' => $user->fullname,
                            'to_user' => $receiver->fullname,
                            'amount' => $request->amount,
                            'currency' => $basic->base_currency,
                        ];
                        $action = [
                            "link" => "#",
                            "icon" => "fa fa-money-bill-alt text-white"
                        ];

                        $userAction = [
                            "link" => "#",
                            "icon" => "fa fa-money-bill-alt text-white"
                        ];

                        $this->adminPushNotification('ADMIN_NOTIFY_BALANCE_TRANSFER', $msg, $action);
                        $this->userPushNotification($user, 'SENDER_NOTIFY_BALANCE_TRANSFER', $msg, $userAction);
                        $this->userPushNotification($receiver, 'RECEIVER_NOTIFY_BALANCE_TRANSFER', $msg, $userAction);

                        return response()->json($this->withSuccess("Balance Transfer has been Successful"));
                    }
                } else {
                    return response()->json($this->withError("Password Do Not Match!"));
                }
            } else {
                return response()->json($this->withError("Insufficient Balance!"));
            }

        } catch (\Exception $e) {
            return response()->json($this->withError($e->getMessage()));
        }
    }


    public function moneyTransferHistory()
    {
        $balanceTransferHistory = MoneyTransfer::with('sender', 'receiver')
            ->orderBy('id', 'DESC')
            ->paginate(20);

        $formattedBalanceTransferHistory = $balanceTransferHistory->map(function ($balanceTransferHistory, $key) {
            return [
                'SL' => $key + 1,
                'id' => $balanceTransferHistory->id,
                'trx_id' => $balanceTransferHistory->trx,
                'amount' => basicControl()->currency_symbol.fractionNumber(getAmount($balanceTransferHistory->amount)),
                'time' => customDate($balanceTransferHistory->send_at),
                'type' => auth()->id() == $balanceTransferHistory->sender_id ? 'Send' : 'Receive',
                'user' => $balanceTransferHistory->receiver ? [
                    'id' => $balanceTransferHistory->receiver->id,
                    'name' => $balanceTransferHistory->receiver->firstname . ' ' . $balanceTransferHistory->receiver->lastname,
                    'username' => $balanceTransferHistory->receiver->username,
                ] : null,
            ];
        });

        return response()->json($this->withSuccess($formattedBalanceTransferHistory));
    }
}
