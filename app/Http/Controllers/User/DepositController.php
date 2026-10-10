<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Deposit;
use App\Models\Fund;
use App\Models\Gateway;
use Illuminate\Http\Request;
use App\Traits\PaymentValidationCheck;
use Illuminate\Support\Facades\Auth;

class DepositController extends Controller
{

    use PaymentValidationCheck;

    public function supportedCurrency(Request $request)
    {
        $gateway = Gateway::where('id', $request->gateway)->first();
        $pmCurrency = $gateway->receivable_currencies[0]->name ?? $gateway->receivable_currencies[0]->currency;
        $isCrypto = $gateway->id < 1000 && checkTo($gateway->currencies, $pmCurrency) == 1;


        return response([
            'success' => true,
            'data' => $gateway->supported_currency,
            'currencyType' => $isCrypto ? 0 : 1,
        ]);
    }

    public function checkAmount(Request $request)
    {
        if ($request->ajax()) {
            $amount = $request->amount;
            $selectedCurrency = $request->selected_currency;
            $selectGateway = $request->select_gateway;
            $selectedCryptoCurrency = $request->selectedCryptoCurrency;
            $data = $this->checkAmountValidate($amount, $selectedCurrency, $selectGateway, $selectedCryptoCurrency);
            return response()->json($data);
        }
        return response()->json(['error' => 'Invalid request'], 400);
    }

    public function checkAmountValidate($amount, $selectedCurrency, $selectGateway, $selectedCryptoCurrency = null)
    {
        $selectGateway = Gateway::where('id', $selectGateway)->where('status', 1)->first();
        if (!$selectGateway) {
            return ['status' => false, 'message' => "Payment method not available for this transaction"];
        }

        $requestCurrency = $selectedCryptoCurrency ? $selectedCryptoCurrency : $selectedCurrency;

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
                return ['status' => false, 'message' => "Please choose the currency you'd like to use for payment"];
            }
        }


        if ($isCrypto == true) {
            $selectedCurrency = array_search($selectedCryptoCurrency, $selectGateway->supported_currency);
            if ($selectedCurrency !== false) {
                $selectedPayCurrency = $selectGateway->supported_currency[$selectedCurrency];
            } else {
                return ['status' => false, 'message' => "Please choose the currency you'd like to use for payment"];
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
                return back()->with('error', $checkAmountValidate['msg']);
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

            return redirect(route('payment.process', $deposit->trx_id));

        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

}
