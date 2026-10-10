<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Payout;
use App\Models\PayoutMethod;
use App\Traits\Notify;
use App\Traits\Upload;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Stevebauman\Purify\Facades\Purify;

class PayoutController extends Controller
{

    use Upload, Notify;

    public function __construct()
    {
        $this->middleware(['auth']);
        $this->middleware(function ($request, $next) {
            $this->user = auth()->user();
            return $next($request);
        });
        $this->theme = template();
    }

    public function payoutHistory()
    {
        $payouts = Payout::with('user')
            ->where(['user_id' => Auth::id()])
            ->orderBy('id', 'desc')->paginate(basicControl()->paginate);
        return view(template() . 'user.payout.index', compact('payouts'));
    }

    public function payoutHistorySearch(Request $request)
    {
        $search = $request->all();
        $fromDate = Carbon::parse($request->from_date);
        $toDate = Carbon::parse($request->to_date)->addDay();

        $payouts = Payout::orderBy('id', 'DESC')->where('user_id', $this->user->id)
            ->when(isset($search['trx_id']), function ($query) use ($search) {
                return $query->where('trx_id', 'LIKE', $search['trx_id']);
            })
            ->when(isset($search['status']), function ($query) use ($search) {
                return $query->where('status', $search['status']);
            })
            ->when(isset($search['from_date']), function ($q2) use ($fromDate) {
                return $q2->whereDate('created_at', '>=', $fromDate);
            })
            ->when(isset($search['to_date']), function ($q2) use ($fromDate, $toDate) {
                return $q2->whereBetween('created_at', [$fromDate, $toDate]);
            })
            ->paginate(basicControl()->paginate);

        $payouts->appends($search);

        return view(template() . 'user.payout.index', compact('payouts'));

    }

    public function payout()
    {
        $data['basic'] = basicControl();
        $data['payoutMethod'] = PayoutMethod::where('is_active', 1)->get();
        return view(template() . 'user.payout.request', $data);
    }

    public function payoutSupportedCurrency(Request $request)
    {
        $gateway = PayoutMethod::where('id', $request->gateway)->firstOrFail();
        return $gateway->supported_currency;
    }

    public function checkAmount(Request $request)
    {
        if ($request->ajax()) {
            $amount = $request->amount;
            $selectedCurrency = $request->selected_currency;
            $selectedPayoutMethod = $request->selected_payout_method;
            $data = $this->checkAmountValidate($amount, $selectedCurrency, $selectedPayoutMethod);
            return response()->json($data);
        }
        return response()->json(['error' => 'Invalid request'], 400);
    }

    public function checkAmountValidate($amount, $selectedCurrency, $selectedPayoutMethod)
    {
        $withdrawalDays = config('withdrawaldays');
        $today = now()->format('l');
        if (!($withdrawalDays[$today] ?? 0)) {
            return ['status' => false, 'message' => "Withdrawals are not available today. Please try again on a day when withdrawals are permitted"];
        }

        $selectedPayoutMethod = PayoutMethod::where('id', $selectedPayoutMethod)->where('is_active', 1)->first();
        if (!$selectedPayoutMethod) {
            return ['status' => false, 'message' => "Payment method not available for this transaction"];
        }

        $selectedCurrency = array_search($selectedCurrency, $selectedPayoutMethod->supported_currency);
        if ($selectedCurrency !== false) {
            $selectedPayCurrency = $selectedPayoutMethod->supported_currency[$selectedCurrency];
        } else {
            return ['status' => false, 'message' => "Please choose the currency you'd like to use for payment"];
        }

        if ($selectedPayoutMethod) {
            $payoutCurrencies = $selectedPayoutMethod->payout_currencies;

            if (is_array($payoutCurrencies)) {

                if ($selectedPayoutMethod->is_automatic == 1) {
                    $currencyInfo = collect($payoutCurrencies)->where('name', $selectedPayCurrency)->first();
                } else {
                    $currencyInfo = collect($payoutCurrencies)->where('currency_symbol', $selectedPayCurrency)->first();

                }
            } else {
                return null;
            }
        }

        $currencyType = $selectedPayoutMethod->currency_type;
        $limit = $currencyType == 0 ? 8 : 2;

        $status = false;
        $amount = getAmount($amount, $limit);

        if ($currencyInfo) {
            $percentage = getAmount($currencyInfo->percentage_charge, $limit);
            $percentage_charge = getAmount(($amount * $percentage) / 100, $limit);
            $fixed_charge = getAmount($currencyInfo->fixed_charge, $limit);
            $min_limit = getAmount($currencyInfo->min_limit, $limit);
            $max_limit = getAmount($currencyInfo->max_limit, $limit);
            $charge = getAmount($percentage_charge + $fixed_charge, $limit);
        }

        $payout_amount = getAmount($amount + $charge, $limit);
        $payout_amount_in_base_currency = getAmount($amount / $currencyInfo->conversion_rate ?? 1, $limit);
        $charge_in_base_currency = getAmount($charge / $currencyInfo->conversion_rate ?? 1, $limit);
        $net_amount_in_base_currency = $payout_amount_in_base_currency + $charge_in_base_currency;

        $user = auth()->user();
        if (!$user) {
            return ['status' => false, 'message' => "Invalid user"];
        }

        $userBalance = $user->balance;
        $basicControl = basicControl();
        if ($amount < $min_limit || $amount > $max_limit) {
            $message = "minimum payment $min_limit and maximum payment limit $max_limit";
        }
        elseif ($net_amount_in_base_currency > $userBalance) {
            $message = "Insufficient Balance. Your wallet balance is ".currencyPosition($userBalance);
        }
        else {
            $status = true;
            $message = "Amount : $amount" . " " . $selectedPayCurrency;
        }

        $data['status'] = $status;
        $data['message'] = $message;
        $data['payout_method_id'] = $selectedPayoutMethod->id;
        $data['fixed_charge'] = $fixed_charge;
        $data['percentage'] = $percentage;
        $data['percentage_charge'] = $percentage_charge;
        $data['min_limit'] = $min_limit;
        $data['max_limit'] = $max_limit;
        $data['charge'] = $charge;
        $data['amount'] = $amount;
        $data['payout_charge'] = $charge;
        $data['net_payout_amount'] = $payout_amount;
        $data['amount_in_base_currency'] = $payout_amount_in_base_currency;
        $data['charge_in_base_currency'] = $charge_in_base_currency;
        $data['net_amount_in_base_currency'] = $net_amount_in_base_currency;
        $data['conversion_rate'] = getAmount($currencyInfo->conversion_rate) ?? 1;
        $data['currency'] = $currencyInfo->name ?? $currencyInfo->currency_symbol;
        $data['base_currency'] = $basicControl->base_currency;
        $data['currency_limit'] = $limit;

        return $data;

    }

    public function payoutRequest(Request $request)
    {
        try {
            $amount = $request->amount;
            $payoutMethod = $request->payout_method_id;
            $supportedCurrency = $request->supported_currency;

            $checkAmountValidateData = $this->checkAmountValidate($amount, $supportedCurrency, $payoutMethod);

            if (!$checkAmountValidateData['status']) {
                return back()->withInput()->with('error', $checkAmountValidateData['message']);
            }

            $user = Auth::user();
            $payout = new Payout();
            $payout->user_id = $user->id;
            $payout->payout_method_id = $checkAmountValidateData['payout_method_id'];
            $payout->payout_currency_code = $checkAmountValidateData['currency'];
            $payout->amount = $checkAmountValidateData['amount'];
            $payout->charge = $checkAmountValidateData['payout_charge'];
            $payout->net_amount = $checkAmountValidateData['net_payout_amount'];
            $payout->amount_in_base_currency = $checkAmountValidateData['amount_in_base_currency'];
            $payout->charge_in_base_currency = $checkAmountValidateData['charge_in_base_currency'];
            $payout->net_amount_in_base_currency = $checkAmountValidateData['net_amount_in_base_currency'];
            $payout->information = null;
            $payout->feedback = null;
            $payout->status = 0;
            $payout->trx_id = strRandom();
            $payout->save();
            return redirect(route('user.payout.confirm', $payout->trx_id))->with('success', 'Payout initiated successfully');
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function confirmPayout(Request $request, $trx_id)
    {
        $payout = Payout::where('trx_id', $trx_id)->first();
        $payoutMethod = PayoutMethod::findOrFail($payout->payout_method_id);
        $basic = basicControl();

        if ($request->isMethod('get')) {
            if ($payoutMethod->code == 'flutterwave') {
                return view(template() . 'user.payout.gateway.' . $payoutMethod->code, compact('payout', 'payoutMethod', 'basic'));
            } elseif ($payoutMethod->code == 'paystack') {
                return view(template() . 'user.payout.gateway.' . $payoutMethod->code, compact('payout', 'payoutMethod', 'basic'));
            }
            return view(template() . 'user.payout.confirm', compact('payout', 'payoutMethod'));

        } elseif ($request->isMethod('post')) {
            $params = $payoutMethod->inputForm;

            $rules = [];
            if ($params !== null) {
                foreach ($params as $key => $cus) {
                    $rules[$key] = [$cus->validation == 'required' ? $cus->validation : 'nullable'];
                    if ($cus->type === 'file') {
                        $rules[$key][] = 'image';
                        $rules[$key][] = 'mimes:jpeg,jpg,png';
                        $rules[$key][] = 'max:4048';
                    } elseif ($cus->type === 'text') {
                        $rules[$key][] = 'max:191';
                    } elseif ($cus->type === 'number') {
                        $rules[$key][] = 'integer';
                    } elseif ($cus->type === 'textarea') {
                        $rules[$key][] = 'min:3';
                        $rules[$key][] = 'max:300';
                    }
                }
            }


            $validator = Validator::make($request->all(), $rules);
            if ($validator->fails()) {
                return back()->withErrors($validator)->withInput();
            }

            $checkAmountValidate = $this->checkAmountValidate($payout->amount_in_base_currency, $payout->payout_currency_code, $payout->payout_method_id);

            if (!$checkAmountValidate['status']) {
                return back()->withInput()->with('error', $checkAmountValidate['message']);
            }


            $params = $payoutMethod->inputForm;
            $reqField = [];
            foreach ($request->except('_token', '_method', 'type', 'currency_code', 'bank') as $k => $v) {
                foreach ($params as $inKey => $inVal) {
                    if ($k == $inVal->field_name) {
                        if ($inVal->type == 'file' && $request->hasFile($inKey)) {
                            try {
                                $file = $this->fileUpload($request[$inKey], config('filelocation.payoutLog.path'));
                                $reqField[$inKey] = [
                                    'field_name' => $inVal->field_name,
                                    'field_value' => $file['path'],
                                    'field_driver' => $file['driver'],
                                    'validation' => $inVal->validation,
                                    'type' => $inVal->type,
                                ];
                            } catch (\Exception $exp) {
                                session()->flash('error', 'Could not upload your ' . $inKey);
                                return back()->withInput();
                            }
                        } else {
                            $reqField[$inKey] = [
                                'field_name' => $inVal->field_name,
                                'validation' => $inVal->validation,
                                'field_value' => $v,
                                'type' => $inVal->type,
                            ];
                        }
                    }
                }
            }


            $payoutMethod = PayoutMethod::find($payout->payout_method_id);
            $payoutCurrencies = $payoutMethod->payout_currencies;
            if ($payoutMethod->is_automatic == 1) {
                $currencyInfo = collect($payoutCurrencies)->where('name', $request->currency_code)->first();
            } else {
                $currencyInfo = collect($payoutCurrencies)->where('currency_symbol', $request->currency_code)->first();
            }

            $reqField['amount'] = [
                'field_name' => 'amount',
                'field_value' => currencyPosition($payout->amount_in_base_currency),
                'type' => 'text',
            ];


            if ($payoutMethod->code == 'paypal') {
                $reqField['recipient_type'] = [
                    'field_name' => 'receiver',
                    'validation' => $inVal->validation,
                    'field_value' => $request->recipient_type,
                    'type' => 'text',
                ];
            }
            $payout->information = $reqField;
            $payout->status = 1;

            $user = Auth::user();
            $updateBalance = updateBalance($payout->user_id, $payout->net_amount_in_base_currency, 0);
            $this->userNotify($user, $payout);

            $payout->save();
            return redirect(route('user.payout.history'))->with('success', 'Payout generated successfully');

        }
    }

    public function paystackPayout(Request $request, $trx_id)
    {

        $basicControl = basicControl();
        $payout = Payout::where('trx_id', $trx_id)->first();
        $payoutMethod = PayoutMethod::findOrFail($payout->payout_method_id);

        if (empty($request->bank)) {
            return back()->with('error', 'Bank field is required')->withInput();
        }

        $checkAmountValidate = $this->checkAmountValidate($payout->amount_in_base_currency, $payout->payout_currency_code, $payout->payout_method_id);
        if (!$checkAmountValidate['status']) {
            return back()->withInput()->with('error', $checkAmountValidate['message']);
        }


        $rules = [];
        if ($payoutMethod->inputForm != null) {
            foreach ($payoutMethod->inputForm as $key => $cus) {
                $rules[$key] = [$cus->validation == 'required' ? $cus->validation : 'nullable'];
                if ($cus->type === 'file') {
                    $rules[$key][] = 'image';
                    $rules[$key][] = 'mimes:jpeg,jpg,png';
                    $rules[$key][] = 'max:2048';
                } elseif ($cus->type === 'text') {
                    $rules[$key][] = 'max:191';
                } elseif ($cus->type === 'number') {
                    $rules[$key][] = 'integer';
                } elseif ($cus->type === 'textarea') {
                    $rules[$key][] = 'min:3';
                    $rules[$key][] = 'max:300';
                }
            }
        }

        $validate = Validator::make($request->all(), $rules);
        if ($validate->fails()) {
            return back()->withErrors($validate)->withInput();
        }

        $params = $payoutMethod->inputForm;
        $reqField = [];
        foreach ($request->except('_token', '_method', 'type', 'currency_code', 'bank') as $k => $v) {
            foreach ($params as $inKey => $inVal) {
                if ($k == $inVal->field_name) {
                    if ($inVal->type == 'file' && $request->hasFile($inKey)) {
                        try {
                            $file = $this->fileUpload($request[$inKey], config('filelocation.payoutLog.path'));
                            $reqField[$inKey] = [
                                'field_name' => $inVal->field_name,
                                'field_value' => $file['path'],
                                'field_driver' => $file['driver'],
                                'validation' => $inVal->validation,
                                'type' => $inVal->type,
                            ];
                        } catch (\Exception $exp) {
                            session()->flash('error', 'Could not upload your ' . $inKey);
                            return back()->withInput();
                        }
                    } else {
                        $reqField[$inKey] = [
                            'field_name' => $inVal->field_name,
                            'validation' => $inVal->validation,
                            'field_value' => $v,
                            'type' => $inVal->type,
                        ];
                    }
                }
            }
        }

        $reqField['type'] = [
            'field_name' => "type",
            'field_value' => $request->type,
            'type' => 'text',
        ];
        $reqField['bank_code'] = [
            'field_name' => "bank_id",
            'field_value' => $request->bank,
            'type' => 'number',
        ];

        $payout->information = $reqField;
        $payout->status = 1;
        $payout->save();

        if (optional($payout->payoutMethod)->is_automatic == 1 && $basicControl->automatic_payout_permission) {
            $this->automaticPayout($payout);
            return redirect()->route('user.payout.history');
        }

        return redirect()->route('user.payout.history')->with('success', 'Payout generated successfully');
    }


    public function flutterwavePayout(Request $request, $trx_id)
    {
        $user = Auth::user();
        $payout = Payout::with('method')->where('trx_id', $trx_id)->first();
        $purifiedData = Purify::clean($request->all());
        if (empty($purifiedData['transfer_name'])) {
            return back()->with('alert', 'Transfer field is required');
        }
        $validation = config('banks.' . $purifiedData['transfer_name'] . '.validation');


        $rules = [];
        if ($validation != null) {
            foreach ($validation as $key => $cus) {
                $rules[$key] = 'required';
            }
        }

        if ($request->transfer_name == 'NGN BANK' || $request->transfer_name == 'NGN DOM' || $request->transfer_name == 'GHS BANK'
            || $request->transfer_name == 'KES BANK' || $request->transfer_name == 'ZAR BANK' || $request->transfer_name == 'ZAR BANK') {
            $rules['bank'] = 'required';
        }

        $rules['currency_code'] = 'required';


        $validate = Validator::make($request->all(), $rules);
        if ($validate->fails()) {

            return back()->withErrors($validate)->withInput();
        }

        $checkAmountValidate = $this->checkAmountValidate($payout->amount, $payout->payout_currency_code, $payout->payout_method_id);

        if (!$checkAmountValidate['status']) {
            return back()->withInput()->with('error', $checkAmountValidate['message']);
        }


        $collection = collect($purifiedData);
        $reqField = [];
        $metaField = [];

        if (config('banks.' . $purifiedData['transfer_name'] . '.input_form') != null) {
            foreach ($collection as $k => $v) {
                foreach (config('banks.' . $purifiedData['transfer_name'] . '.input_form') as $inKey => $inVal) {
                    if ($k != $inKey) {
                        continue;
                    } else {
                        if ($inVal == 'meta') {
                            $metaField[$inKey] = $v;
                            $metaField[$inKey] = [
                                'field_name' => $k,
                                'field_value' => $v,
                                'type' => 'text',
                            ];
                        } else {
                            $reqField[$inKey] = $v;
                            $reqField[$inKey] = [
                                'field_name' => $k,
                                'field_value' => $v,
                                'type' => 'text',
                            ];
                        }
                    }
                }
            }


            if ($request->transfer_name == 'NGN BANK' || $request->transfer_name == 'NGN DOM' || $request->transfer_name == 'GHS BANK'
                || $request->transfer_name == 'KES BANK' || $request->transfer_name == 'ZAR BANK' || $request->transfer_name == 'ZAR BANK') {

                $reqField['account_bank'] = [
                    'field_name' => 'Account Bank',
                    'field_value' => $request->bank,
                    'type' => 'text',
                ];
            } elseif ($request->transfer_name == 'XAF/XOF MOMO') {
                $reqField['account_bank'] = [
                    'field_name' => 'MTN',
                    'field_value' => 'MTN',
                    'type' => 'text',
                ];
            } elseif ($request->transfer_name == 'FRANCOPGONE' || $request->transfer_name == 'mPesa' || $request->transfer_name == 'Rwanda Momo'
                || $request->transfer_name == 'Uganda Momo' || $request->transfer_name == 'Zambia Momo') {
                $reqField['account_bank'] = [
                    'field_name' => 'MPS',
                    'field_value' => 'MPS',
                    'type' => 'text',
                ];
            }

            if ($request->transfer_name == 'Barter') {
                $reqField['account_bank'] = [
                    'field_name' => 'barter',
                    'field_value' => 'barter',
                    'type' => 'text',
                ];
            } elseif ($request->transfer_name == 'flutterwave') {
                $reqField['account_bank'] = [
                    'field_name' => 'barter',
                    'field_value' => 'barter',
                    'type' => 'text',
                ];
            }


            $payoutMethod = PayoutMethod::find($payout->payout_method_id);
            $payoutCurrencies = $payoutMethod->payout_currencies;
            $currencyInfo = collect($payoutCurrencies)->where('name', $request->currency_code)->first();

            $reqField['amount'] = [
                'field_name' => 'amount',
                'field_value' => $payout->amount * $currencyInfo->conversion_rate,
                'type' => 'text',
            ];

            $payout->information = $reqField;
            $payout->meta_field = $metaField;


        } else {
            $payout->information = null;
            $payout->meta_field = null;
        }

        $payout->status = 1;
        $payout->payout_currency_code = $request->currency_code;
        $payout->save();
        return redirect()->route('user.payout.history')->with('success', 'Payout generated successfully');

    }

    public function getBankList(Request $request)
    {
        $currencyCode = $request->currencyCode;
        $methodObj = 'App\\Services\\Payout\\paystack\\Card';
        $data = $methodObj::getBank($currencyCode);
        return $data;
    }

    public function getBankForm(Request $request)
    {
        $bankName = $request->bankName;
        $bankArr = config('banks.' . $bankName);

        if ($bankArr['api'] != null) {

            $methodObj = 'App\\Services\\Payout\\flutterwave\\Card';
            $data = $methodObj::getBank($bankArr['api']);
            $value['bank'] = $data;
        }
        $value['input_form'] = $bankArr['input_form'];
        return $value;
    }

    public function automaticPayout($payout)
    {
        $methodObj = 'App\\Services\\Payout\\' . optional($payout->payoutMethod)->code . '\\Card';
        $data = $methodObj::payouts($payout);

        if (!$data) {
            return back()->with('alert', 'Method not available or unknown errors occur');
        }

        if ($data['status'] == 'error') {
            $payout->last_error = $data['data'];
            $payout->status = 3;
            $payout->save();
            return back()->with('error', $data['data']);
        }
    }

    public function userNotify($user, $payout)
    {
        $params = [
            'sender' => $user->name,
            'amount' => currencyPosition($payout->amount),
            'transaction' => $payout->trx_id,
        ];

        $action = [
            "link" => route('admin.payout.log'),
            "icon" => "fa fa-money-bill-alt text-white",
            "name" => optional($payout->user)->firstname . ' ' . optional($payout->user)->lastname,
            "image" => getFile(optional($payout->user)->image_driver, optional($payout->user)->image),
        ];
        $firebaseAction = route('admin.payout.log');
        $this->adminMail('PAYOUT_REQUEST_TO_ADMIN', $params);
        $this->adminPushNotification('PAYOUT_REQUEST_TO_ADMIN', $params, $action);
        $this->adminFirebasePushNotification('PAYOUT_REQUEST_TO_ADMIN', $params, $firebaseAction);

        $params = [
            'amount' => currencyPosition($payout->amount),
            'transaction' => $payout->trx_id,
        ];
        $action = [
            "link" => "#",
            "icon" => "fa fa-money-bill-alt text-white"
        ];
        $firebaseAction = "#";
        $this->sendMailSms($user, 'PAYOUT_REQUEST_FROM', $params);
        $this->userPushNotification($user, 'PAYOUT_REQUEST_FROM', $params, $action);
        $this->userFirebasePushNotification($user, 'PAYOUT_REQUEST_FROM', $params, $firebaseAction);
    }

}
