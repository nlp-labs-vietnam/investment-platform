<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\MoneyTransfer;
use App\Models\Transaction;
use App\Models\User;
use App\Traits\Notify;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class MoneyTransferController extends Controller
{

    use Notify;
    public function __construct()
    {
        $this->middleware(['auth']);
        $this->middleware(function ($request, $next) {
            $this->user = auth()->user();
            return $next($request);
        });
        $this->theme = template();
    }

    public function moneyTransfer()
    {
        return view(template() . 'user.money_transfer.moneyTransfer');
    }

    public function moneyTransferStore(Request $request)
    {
        $this->validate($request, [
            'email' => 'required',
            'amount' => 'required',
            'wallet_type' => ['required', Rule::in(['balance', 'interest_balance'])],
            'password' => 'required'
        ], [
            'wallet_type.required' => 'Please Select a wallet'
        ]);

        $basic = basicControl();
        $email = trim($request->email);

        $receiver = User::where('email', $email)->first();

        if (!$receiver) {
            session()->flash('error', 'This Email could not Found!');
            return back();
        }
        if ($receiver->id == Auth::id()) {
            session()->flash('error', 'This Email could not Found!');
            return back()->withInput();
        }

        if ($receiver->status == 0) {
            session()->flash('error', 'Invalid User!');
            return back()->withInput();
        }


        if ($request->amount < $basic->min_transfer) {
            session()->flash('error', 'Minimum Transfer Amount ' . $basic->min_transfer . ' ' . $basic->base_currency);
            return back()->withInput();
        }

        if ($request->amount > $basic->max_transfer) {
            session()->flash('error', 'Maximum Transfer Amount ' . $basic->max_transfer . ' ' . $basic->base_currency);
            return back()->withInput();
        }

        $transferCharge = ($request->amount * $basic->transfer_charge) / 100;

        $user = Auth::user();
        $wallet_type = $request->wallet_type;
        if ($user[$wallet_type] >= ($request->amount + $transferCharge)) {

            if (Hash::check($request->password, $user->password)) {

                $sendMoneyCheck = MoneyTransfer::where('sender_id', $user->id)->where('receiver_id', $receiver->id)->latest()->first();



                if (isset($sendMoneyCheck) && Carbon::parse($sendMoneyCheck->send_at) > Carbon::now()) {

                    $time = $sendMoneyCheck->send_at;
                    $delay = $time->diffInSeconds(Carbon::now());
                    $delay = gmdate('i:s', $delay);

                    session()->flash('error', 'You can send money to this user after  delay ' . $delay . ' minutes');
                    return back()->withInput();
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

                    $currentDate = dateTime(Carbon::now());
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

                    return back()->with('success', 'Balance Transfer  has been Successful');
                }
            } else {
                session()->flash('error', 'Password Do Not Match!');
                return back()->withInput();
            }
        } else {
            session()->flash('error', 'Insufficient Balance!');
            return back()->withInput();
        }
    }

    public function moneyTransferHistory(Request $request)
    {
        $search = $request->all();
        $fromDate = \Carbon\Carbon::parse($request->from_date);
        $toDate = Carbon::parse($request->to_date)->addDay();

        $data['title'] = "Money Transfer History";
        $user = $this->user;

        $data['balanceTransferHistory'] = MoneyTransfer::with('sender', 'receiver')
            ->when(isset($search['trx']), function ($query) use ($search) {
                return $query->where('trx', 'LIKE', $search['trx']);
            })
            ->when(isset($search['username']), function ($query) use ($search) {
                return $query->whereHas('receiver', function ($q) use ($search) {
                    $q->where('username', $search['username']);
                });
            })
            ->when(isset($search['from_date']), function ($q2) use ($fromDate) {
                return $q2->whereDate('created_at', '>=', $fromDate);
            })
            ->when(isset($search['to_date']), function ($q2) use ($fromDate, $toDate) {
                return $q2->whereBetween('created_at', [$fromDate, $toDate]);
            })
            ->where('sender_id', $user->id)
            ->orWhere('receiver_id', $user->id)
            ->orderBy('created_at', 'DESC')
            ->paginate(20);

        return view(template() . 'user.money_transfer.moneyTransferHistory', $data);
    }

}
