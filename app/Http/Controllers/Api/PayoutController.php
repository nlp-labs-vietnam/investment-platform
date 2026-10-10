<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Payout;
use App\Models\PayoutMethod;
use App\Traits\ApiResponse;
use App\Traits\Notify;
use App\Traits\Upload;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

use Facades\App\Services\BasicService;
use Facades\App\Http\Controllers\User\PayoutController as UserPayoutController;

class PayoutController extends Controller
{
    use ApiResponse, Upload, Notify;

    public function payout()
    {
        $basic = basicControl();
        $data['gateways'] = PayoutMethod::where('is_active', 1)->get()->map(function ($gateway) {
            $gateway->logo = getFile($gateway->driver, $gateway->logo);
            return $gateway;
        });

        $data['currency_symbol'] = $basic->currency_symbol;
        $data['base_currency'] = $basic->base_currency;
        return response()->json($this->withSuccess($data));
    }

    public function checkAmount(Request $request)
    {
        $rules = [
            'selected_payout_method' => 'required',
            'amount' => 'required',
            'selected_currency' => 'required',
        ];

        $validate = Validator::make($request->all(), $rules);

        if ($validate->fails()) {
            return response()->json($this->withError(collect($validate->errors())->collapse()));
        }

        try {
            $amount = $request->amount;
            $selectedCurrency = $request->selected_currency;
            $selectedPayoutMethod = $request->selected_payout_method;
            $checkAmountData = $this->checkAmountValidate($amount, $selectedCurrency, $selectedPayoutMethod);

            $data['payout_summary'] = [
                'amount_in_text' => 'Amount In ' . $checkAmountData['currency'],
                'amount_in_value' => $checkAmountData['amount'] . ' ' . $checkAmountData['currency'],
                'charge' => $checkAmountData['charge'] . ' ' . $checkAmountData['currency'],
                'payable_amount' => $checkAmountData['net_payout_amount'] . ' ' . $checkAmountData['currency'],
                'exchange_rate_left_value' => '1 ' . $checkAmountData['base_currency'],
                'exchange_rate_arrow_left' => '←',
                'exchange_rate_arrow_right' => '→',
                'exchange_rate_right_value' => $checkAmountData['conversion_rate'] . ' ' . $checkAmountData['currency'],
                'payable_amount_in_base_currency' => $checkAmountData['amount_in_base_currency'] . ' ' . $checkAmountData['base_currency'],
                'optional' => [
                    'status' => $checkAmountData['status'],
                    'message' => $checkAmountData['message'],
                    'fixed_charge' => $checkAmountData['fixed_charge'],
                    'percentage' => $checkAmountData['percentage'],
                    'percentage_charge' => $checkAmountData['percentage_charge'],
                    'min_limit' => $checkAmountData['min_limit'],
                    'max_limit' => $checkAmountData['max_limit'],
                    'net_payout_amount' => $checkAmountData['net_payout_amount'],
                    'charge' => $checkAmountData['charge'],
                    'amount' => $checkAmountData['amount'],
                    'payout_charge' => $checkAmountData['payout_charge'],
                    'amount_in_base_currency' => $checkAmountData['amount_in_base_currency'],
                    'charge_in_base_currency' => $checkAmountData['charge_in_base_currency'],
                    'net_amount_in_base_currency' => $checkAmountData['net_amount_in_base_currency'],
                    'conversion_rate' => $checkAmountData['conversion_rate'],
                    'currency' => $checkAmountData['currency'],
                    'base_currency' => $checkAmountData['base_currency'],
                    'currency_limit' => $checkAmountData['currency_limit'],
                ],
            ];

            return response()->json($this->withSuccess($data));
        } catch (\Exception $exception) {
            return response()->json($this->withError($exception->getMessage()));
        }

    }

    public function checkAmountValidate($amount, $selectedCurrency, $selectedPayoutMethod)
    {
        $selectedPayoutMethod = PayoutMethod::where('id', $selectedPayoutMethod)->where('is_active', 1)->first();

        if (!$selectedPayoutMethod) {
            return response()->json($this->withError('Payment method not available for this transaction'));
        }

        $selectedCurrency = array_search($selectedCurrency, $selectedPayoutMethod->supported_currency);

        if ($selectedCurrency !== false) {
            $selectedPayCurrency = $selectedPayoutMethod->supported_currency[$selectedCurrency];
        } else {
            return response()->json($this->withError("Please choose the currency you'd like to use for payment"));
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

        $basicControl = basicControl();

        if ($amount < $min_limit || $amount > $max_limit) {
            $message = "minimum payment $min_limit and maximum payment limit $max_limit";
        } else {
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

    public function payoutGetBankList(Request $request)
    {
        try {
            $currencyCode = $request->currencyCode;
            $methodObj = 'App\\Services\\Payout\\paystack\\Card';
            $data = $methodObj::getBank($currencyCode);
            return response()->json($this->withSuccess($data));
        } catch (\Exception $e) {
            return response()->json($this->withError($e->getMessage()));
        }
    }

    public function payoutGetBankFrom(Request $request)
    {
        try {
            $bankName = $request->bankName;
            $bankArr = config('banks.' . $bankName);
            $value['bank'] = null;
            if ($bankArr['api'] != null) {
                $methodObj = 'App\\Services\\Payout\\flutterwave\\Card';
                $data = $methodObj::getBank($bankArr['api']);
                $value['bank'] = $data;
            }
            $value['input_form'] = $bankArr['input_form'];
            return response()->json($this->withSuccess($value));
        } catch (\Exception $e) {
            return response()->json($this->withError($e->getMessage()));
        }
    }

    public function payoutPaystackSubmit(Request $request)
    {
        $rules = [
            'gateway' => 'required|integer',
            'currency' => 'required',
            'amount' => ['required', 'numeric']
        ];

        $validateUser = Validator::make($request->all(), $rules);

        if ($validateUser->fails()) {
            return response()->json($this->withError(collect($validateUser->errors())->collapse()));
        }

        try {

            if (!config('withdrawaldays')[date('l')]) {
                return response()->json($this->withError('Withdraw processing is off today'));
            }

            $amount = $request->amount;
            $payoutMethod = PayoutMethod::findOrFail($request->gateway);
            $supportedCurrency = $request->currency;

            $checkAmountValidateData = UserPayoutController::checkAmountValidate($amount, $supportedCurrency, $payoutMethod->id);


            if (!$checkAmountValidateData['status']) {
                return response()->json($this->withError($checkAmountValidateData['message']));
            }


            $user = Auth::user();

            if ($user->balance < $checkAmountValidateData['net_amount_in_base_currency']) {
                return response()->json($this->withError('Insufficient Balance'));
            }

            if (empty($request->bank)) {
                return response()->json($this->withError('Bank field is required'));
            }

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
            $payout->save();


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
                return response()->json($this->withError(collect($validate->errors())->collapse()));
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
                                return response()->json($this->withError('Could not upload your ' . $inKey));
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

            updateBalance($payout->user_id, $payout->net_amount_in_base_currency, 0); //update user balance

            BasicService::makeTransaction($payout->user, $payout->net_amount_in_base_currency, $payout->charge_in_base_currency, '-', 'balance',
                $payout->trx_id, 'Amount debited for payout', null, null, $payout->id, Payout::class);

            return response()->json($this->withSuccess('Withdraw request Successfully Submitted. Wait For Confirmation.'));

        } catch (\Exception $e) {
            return response()->json($this->withError($e->getMessage()));
        }
    }


    public function payoutFlutterwaveSubmit(Request $request)
    {
        $validateUser = Validator::make($request->all(),
            [
                'gateway' => 'required|integer',
                'currency' => 'required',
                'amount' => ['required', 'numeric']
            ]);

        if ($validateUser->fails()) {
            return response()->json($this->withError(collect($validateUser->errors())->collapse()));
        }

        try {

            if (!config('withdrawaldays')[date('l')]) {
                return response()->json($this->withError('Withdraw processing is off today'));
            }

            $amount = $request->amount;
            $payoutMethod = PayoutMethod::findOrFail($request->gateway);
            $supportedCurrency = $request->currency;

            $checkAmountValidateData = UserPayoutController::checkAmountValidate($amount, $supportedCurrency, $payoutMethod->id);

            if (!$checkAmountValidateData['status']) {
                return response()->json($this->withError($checkAmountValidateData['message']));
            }
            $user = Auth::user();

            if ($user->balance < $checkAmountValidateData['net_amount_in_base_currency']) {
                return response()->json($this->withError('Insufficient Balance'));
            }

            $purifiedData = $request->all();
            if (empty($purifiedData['transfer_name'])) {
                return response()->json($this->withError('Transfer field is required'));
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


            $validate = Validator::make($request->all(), $rules);
            if ($validate->fails()) {
                return response()->json($this->withError(collect($validate->errors())->collapse()));
            }

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
            $payout->save();

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
                                $metaField[$inKey] = [
                                    'field_name' => $k,
                                    'field_value' => $v,
                                    'type' => 'text',
                                ];
                            } else {
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

                $payoutCurrencies = $payoutMethod->payout_currencies;
                $currencyInfo = collect($payoutCurrencies)->where('name', $request->currency)->first();

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
            $payout->payout_currency_code = $request->currency;
            $payout->save();

            updateBalance($payout->user_id, $payout->net_amount_in_base_currency, 0); //update user balance
            BasicService::makeTransaction($payout->user, $payout->net_amount_in_base_currency, $payout->charge_in_base_currency, '-', 'balance',
                $payout->trx_id, 'Amount debited for payout', null, null, $payout->id, Payout::class);

            return response()->json($this->withSuccess('Withdraw request Successfully Submitted. Wait For Confirmation.'));

        } catch (\Exception $e) {
            return response()->json($this->withError($e->getMessage()));
        }
    }

    public function payoutSubmit(Request $request)
    {
        $validateUser = Validator::make($request->all(),
            [
                'gateway' => 'required|integer',
                'currency_code' => 'required',
                'amount' => ['required', 'numeric']
            ]);

        if ($validateUser->fails()) {
            return response()->json($this->withError(collect($validateUser->errors())->collapse()));
        }

        try {
            if (!config('withdrawaldays')[date('l')]) {
                return response()->json($this->withError('Withdraw processing is off today'));
            }

            $amount = $request->amount;
            $payoutMethod = PayoutMethod::findOrFail($request->gateway);
            $supportedCurrency = $request->currency_code;

            $checkAmountValidateData = UserPayoutController::checkAmountValidate($amount, $supportedCurrency, $payoutMethod->id);

            if (!$checkAmountValidateData['status']) {
                return response()->json($this->withError($checkAmountValidateData['message']));
            }
            $user = Auth::user();

            if ($user->balance < $checkAmountValidateData['net_amount_in_base_currency']) {
                return response()->json($this->withError('Insufficient Balance'));
            }


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
                return response()->json($this->withError(collect($validator->errors())->collapse()));
            }

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
            $payout->save();

            $reqField = [];
            foreach ($request->except('_token', '_method', 'type', 'currency_code', 'bank') as $k => $v) {
                foreach ($params as $inKey => $inVal) {
                    if ($k == $inVal->field_name) {
                        if ($inVal->type == 'file' && $request->hasFile($inKey)) {
                            try {
                                $file = $this->fileUpload($request[$inKey], config('filelocation.payoutLog.path'), null, null, 'webp', 60);
                                $reqField[$inKey] = [
                                    'field_name' => $inVal->field_name,
                                    'field_value' => $file['path'],
                                    'field_driver' => $file['driver'],
                                    'validation' => $inVal->validation,
                                    'type' => $inVal->type,
                                ];
                            } catch (\Exception $exp) {
                                return response()->json($this->withError('Could not upload your ' . $inKey));
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

            $payoutCurrencies = $payoutMethod->payout_currencies;
            if ($payoutMethod->is_automatic == 1) {
                $currencyInfo = collect($payoutCurrencies)->where('name', $request->currency_code)->first();
            } else {
                $currencyInfo = collect($payoutCurrencies)->where('currency_symbol', $request->currency_code)->first();
            }
            $reqField['amount'] = [
                'field_name' => 'amount',
                'field_value' => currencyPosition($payout->amount / $currencyInfo->conversion_rate),
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

            updateBalance($payout->user_id, $payout->net_amount_in_base_currency, 0); //update user balance
            BasicService::makeTransaction($payout->user, $payout->net_amount_in_base_currency, $payout->charge_in_base_currency, '-', 'balance',
                $payout->trx_id, 'Amount debited for payout', null, null, $payout->id, Payout::class);

            $this->userNotify($user, $payout); // send user notification

            $payout->save();

            return response()->json($this->withSuccess('Withdraw request Successfully Submitted. Wait For Confirmation.'));

        } catch (\Exception $e) {
            return response()->json($this->withError($e->getMessage()));
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
