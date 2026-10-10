<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Deposit;
use App\Models\Gateway;
use App\Traits\ApiResponse;
use App\Traits\Notify;
use App\Traits\PaymentValidationCheck;
use App\Traits\Upload;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Facades\App\Services\BasicService;

class DepositController extends Controller
{
    use ApiResponse, PaymentValidationCheck, Notify, Upload;


    public function addFund()
    {
        $basic = basicControl();
        $gateways = Gateway::where('status', 1)->orderBy('sort_by', 'ASC')->get();

        $gateways = $gateways->map(function ($gateway) {
            $gateway->image = getFile($gateway->driver, $gateway->image);
            return $gateway;
        });

        $data['gateways'] = $gateways;
        $data['currency_symbol'] = $basic->currency_symbol;
        $data['base_currency'] = $basic->base_currency;

        return response()->json($this->withSuccess($data));
    }

    public function checkAmount(Request $request)
    {
        $rules = [
            'select_gateway' => 'required',
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
            $selectGateway = $request->select_gateway;
            $selectedCryptoCurrency = $request->selectedCryptoCurrency;
            $checkAmountData = $this->checkAmountValidate($amount, $selectedCurrency, $selectGateway, $selectedCryptoCurrency);

            $data['deposit_summary'] = [
                'amount_in_text' => 'Amount In ' . $checkAmountData['currency'],
                'amount_in_value' => $checkAmountData['amount'] . ' ' . $checkAmountData['currency'],
                'charge' => $checkAmountData['charge'] . ' ' . $checkAmountData['currency'],
                'payable_amount' => $checkAmountData['payable_amount'] . ' ' . $checkAmountData['currency'],
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
                    'payable_amount' => $checkAmountData['payable_amount'],
                    'charge' => $checkAmountData['charge'],
                    'conversion_rate' => $checkAmountData['conversion_rate'],
                    'amount_in_base_currency' => $checkAmountData['amount_in_base_currency'],
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

    public function checkAmountValidate($amount, $selectedCurrency, $selectGateway, $selectedCryptoCurrency = null)
    {
        $selectGateway = Gateway::where('id', $selectGateway)->where('status', 1)->first();
        if (!$selectGateway) {
            return response()->json($this->withError('Payment method not available for this transaction'));
        }

        $requestCurrency = $selectedCryptoCurrency ?? $selectedCurrency;

        if (999 < $selectGateway->id) {
            $isCrypto = false;
        } else {
            $isCrypto = (checkTo($selectGateway->currencies, $requestCurrency) == 1) ? true : false;
        }


        if ($isCrypto == false) {
            $selectedCurrency = array_search($selectedCurrency, $selectGateway->supported_currency);
            if ($selectedCurrency !== false) {
                $selectedPayCurrency = $selectGateway->supported_currency[$selectedCurrency];
            } else {
                return response()->json($this->withError("Please choose the currency you'd like to use for payment"));

            }
        }


        if ($isCrypto == true) {
            $selectedCurrency = array_search($selectedCryptoCurrency, $selectGateway->supported_currency);
            if ($selectedCurrency !== false) {
                $selectedPayCurrency = $selectGateway->supported_currency[$selectedCurrency];
            } else {
                return response()->json($this->withError("Please choose the currency you'd like to use for payment"));
            }
        }

        if ($selectGateway) {
            $receivableCurrencies = $selectGateway->receivable_currencies;
            if (is_array($receivableCurrencies)) {
                if ($selectGateway->id < 999) {
                    $currencyInfo = collect($receivableCurrencies)->where('name', $selectedPayCurrency)->first();
                } else {
                    if ($isCrypto == false) {
                        $currencyInfo = collect($receivableCurrencies)->where('currency', $selectedPayCurrency)->first();
                    } else {
                        $currencyInfo = collect($receivableCurrencies)->where('currency', $selectedCryptoCurrency)->first();
                    }
                }
            } else {
                return null;
            }
        }


        $currencyType = $selectGateway->currency_type;
        $limit = $currencyType == 0 ? 8 : 2;
        $amount = getAmount($amount, $limit);
        $status = false;

        if ($currencyInfo) {
            $percentage = getAmount($currencyInfo->percentage_charge, $limit);
            $percentage_charge = getAmount(($amount * $percentage) / 100, $limit);
            $fixed_charge = getAmount($currencyInfo->fixed_charge, $limit);
            $min_limit = getAmount($currencyInfo->min_limit, $limit);
            $max_limit = getAmount($currencyInfo->max_limit, $limit);
            $charge = getAmount($percentage_charge + $fixed_charge, $limit);
        }

        $basicControl = basicControl();
        $payable_amount = getAmount($amount + $charge, $limit);
        $amount_in_base_currency = getAmount($payable_amount / $currencyInfo->conversion_rate ?? 1, $limit);

        if ($amount < $min_limit || $amount > $max_limit) {
            $message = "minimum payment $min_limit and maximum payment limit $max_limit";
        } else {
            $status = true;
            $message = "Amount : $amount" . " " . $selectedPayCurrency;
        }

        $data['status'] = $status;
        $data['message'] = $message;
        $data['fixed_charge'] = $fixed_charge;
        $data['percentage'] = $percentage;
        $data['percentage_charge'] = $percentage_charge;
        $data['min_limit'] = $min_limit;
        $data['max_limit'] = $max_limit;
        $data['payable_amount'] = $payable_amount;
        $data['charge'] = $charge;
        $data['amount'] = $amount;
        $data['conversion_rate'] = $currencyInfo->conversion_rate ?? 1;
        $data['amount_in_base_currency'] = number_format($amount_in_base_currency, 2);
        $data['currency'] = (!$isCrypto) ? ($currencyInfo->name ?? $currencyInfo->currency) : $cryptoCurrency ?? 'USD';
        $data['base_currency'] = $basicControl->base_currency;
        $data['currency_limit'] = $limit;


        return $data;
    }

    public function paymentRequest(Request $request)
    {
        $amount = $request->amount;
        $gateway = $request->gateway_id;
        $currency = $request->supported_currency;
        $cryptoCurrency = $request->supported_crypto_currency;

        try {

            $checkAmountValidate = $this->validationCheck($amount, $gateway, $currency, $cryptoCurrency);

            if ($checkAmountValidate['status'] == 'error') {
                return response()->json($this->withError($checkAmountValidate['msg']));
            }

            $deposit = Deposit::create([
                'user_id' => Auth::user()->id,
                'payment_method_id' => $checkAmountValidate['data']['gateway_id'],
                'payment_method_currency' => $checkAmountValidate['data']['currency'],
                'amount' => $amount,
                'percentage_charge' => $checkAmountValidate['data']['percentage_charge'],
                'fixed_charge' => $checkAmountValidate['data']['fixed_charge'],
                'payable_amount' => $checkAmountValidate['data']['payable_amount'],
                'base_currency_charge' => $checkAmountValidate['data']['base_currency_charge'],
                'payable_amount_in_base_currency' => $checkAmountValidate['data']['payable_amount_base_in_currency'],
                'status' => 0,
            ]);

            $transaction_id = $deposit->trx_id;
            $redirectUrl = route('payment.process', $deposit->trx_id);
            $data = [
                'trx_id' => $transaction_id,
//                'redirect_url' => $redirectUrl,
            ];

            return response()->json($this->withSuccess($data));
        } catch (\Exception $e) {
            return response()->json($this->withError($e->getMessage()));
        }
    }

    public function paymentDone(Request $request)
    {
        $rules = [
            'trx_id' => 'required',
        ];

        $validate = Validator::make($request->all(), $rules);

        if ($validate->fails()) {
            return response()->json($this->withError(collect($validate->errors())->collapse()));
        }

        $deposit = Deposit::latest()->where('trx_id', $request->trx_id)->where('status', 0)->first();

        if (!$deposit) {
            return response()->json($this->withError('Record not found'));
        }

        BasicService::preparePaymentUpgradation($deposit);
        return response()->json($this->withSuccess('Payment has been completed'));
    }

    public function cardPayment(Request $request)
    {

        $rules = [
            'trx_id' => 'required',
            'card_number' => 'required',
            'card_name' => 'required',
            'expiry_month' => 'required',
            'expiry_year' => 'required',
            'card_cvc' => 'required',
        ];

        $validate = Validator::make($request->all(), $rules);

        if ($validate->fails()) {
            return response()->json($this->withError(collect($validate->errors())->collapse()));
        }

        $deposit = Deposit::latest()->where('trx_id', $request->trx_id)->where('status', 0)->first();

        if (!$deposit) {
            return response()->json($this->withError('Record not found'));
        }

        $getwayObj = 'App\\Services\\Gateway\\' . $deposit->gateway->code . '\\Payment';

        $data = $getwayObj::mobileIpn($request, $deposit->gateway, $deposit);

        if ($data == 'success') {
            return response()->json($this->withSuccess('Payment has been complete'));
        } else {
            return response()->json($this->withError('unsuccessful transaction.'));
        }
    }

    public function otherPayment(Request $request)
    {
        $rules = [
            'trx_id' => 'required',
        ];

        $validate = Validator::make($request->all(), $rules);

        if ($validate->fails()) {
            return response()->json($this->withError(collect($validate->errors())->collapse()));
        }

        $deposit = Deposit::latest()->where('trx_id', $request->trx_id)->where('status', 0)->first();

        if (!$deposit) {
            return response()->json($this->withError('Record not found'));
        }

        $val['url'] = route('paymentView', $deposit->id);

        return response()->json($this->withSuccess($val));
    }

    public function paymentView($depositId)
    {
        $deposit = Deposit::latest()->find($depositId);
        try {
            if ($deposit) {
                $getwayObj = 'App\\Services\\Gateway\\' . $deposit->gateway->code . '\\Payment';
                $data = $getwayObj::prepareData($deposit, $deposit->gateway);
                $data = json_decode($data);

                if (isset($data->error)) {
                    $result['status'] = false;
                    $result['message'] = $data->message;
                    return response($result, 200);
                }

                if (isset($data->redirect)) {
                    return redirect($data->redirect_url);
                }



                if ($data->view) {
                    $parts = explode(".", $data->view);
                    $desiredValue = end($parts);
//                    $newView = template() . 'user.payment.' . $desiredValue;
                    $newView = 'mobile-payment.' . $desiredValue;

                    return view($newView, compact('data', 'deposit'));
                }

                abort(404);
            }
        } catch (\Exception $e) {
            return response()->json($this->withError($e->getMessage()));
        }
    }

    public function manualPayment(Request $request)
    {
        $validator = validator()->make($request->all(), [
            'trx_id' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->withError(collect($validator->messages())->collapse());
        }

        $deposit = Deposit::latest()->where('trx_id', $request->trx_id)->where('status', 0)->first();

        if (!$deposit) {
            return response()->json($this->withError('Record not found'));
        }

        try {
            $params = optional($deposit->gateway)->parameters;
            $reqData = $request->except('_token', '_method');
            $rules = [];
            if ($params !== null) {
                foreach ($params as $key => $cus) {
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

            $validator = Validator::make($reqData, $rules);
            if ($validator->fails()) {
                return response()->json($this->withError(collect($validator->errors())->collapse()));
            }

            $reqField = [];
            if ($params != null) {
                foreach ($request->except('_token', '_method', 'type') as $k => $v) {
                    foreach ($params as $inKey => $inVal) {
                        if ($k == $inKey) {
                            if ($inVal->type == 'file' && $request->hasFile($inKey)) {
                                try {
                                    $file = $this->fileUpload($request[$inKey], config('filelocation.deposit.path'), config('filesystems.default'), null, null, null, null, 40);
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
            }

            $deposit->update([
                'information' => $reqField,
                'created_at' => Carbon::now(),
                'status' => 2,
            ]);

            $msg = [
                'username' => optional($deposit->user)->username,
                'amount' => currencyPosition($deposit->payable_amount_in_base_currency),
                'gateway' => optional($deposit->gateway)->name
            ];
            $action = [
                "name" => optional($deposit->user)->firstname . ' ' . optional($deposit->user)->lastname,
                "image" => getFile(optional($deposit->user)->image_driver, optional($deposit->user)->image),
                "link" => route('admin.user.payment', $deposit->user_id),
                "icon" => "fa fa-money-bill-alt text-white"
            ];

            $this->adminPushNotification('PAYMENT_REQUEST', $msg, $action);
            $this->adminFirebasePushNotification('PAYMENT_REQUEST', $msg, $action);
            $this->adminMail('PAYMENT_REQUEST', $msg);

            return response()->json($this->withSuccess('You request has been taken.'));

        } catch (\Exception $e) {
            return response()->json($this->withError($e->getMessage()));
        }
    }
}
