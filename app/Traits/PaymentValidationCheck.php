<?php

namespace App\Traits;

use App\Models\Gateway;
use Mockery\Exception;

trait PaymentValidationCheck
{
    public function validationCheck($amount, $gateway, $currency, $cryptoCurrency = null)
    {


        try {
            $gateway = Gateway::where('id', $gateway)->where('status', 1)->first();

            if (!$gateway) {
                return [
                    'status' => 'error',
                    'msg' => 'Payment method not available for this transaction'
                ];
            }


            $requestCurrency = $cryptoCurrency ? $cryptoCurrency : $currency;



            if (999 < $gateway->id) {
                $isCrypto = false;
            } else {

                $isCrypto = (checkTo($gateway->currencies, $requestCurrency) == 1) ? true : false;
            }




            if ($isCrypto == false) {
                $selectedCurrency = array_search($currency, $gateway->supported_currency);

                if ($selectedCurrency !== false) {
                    $selectedPayCurrency = $gateway->supported_currency[$selectedCurrency];
                } else {
                    return [
                        'status' => 'error',
                        'msg' => "Please choose the currency you'd like to use for payment"
                    ];
                }
            }

            if ($isCrypto == true) {
                $selectedCurrency = array_search($cryptoCurrency, $gateway->supported_currency);
                if ($selectedCurrency !== false) {
                    $selectedPayCurrency = $gateway->supported_currency[$selectedCurrency];
                } else {
                    return [
                        'status' => 'error',
                        'msg' => "Please choose the currency you'd like to use for payment"
                    ];
                }
            }

            if ($gateway) {
                $receivableCurrencies = $gateway->receivable_currencies;
                if (is_array($receivableCurrencies)) {
                    if ($gateway->id < 999) {
                        $currencyInfo = collect($receivableCurrencies)->where('name', $selectedPayCurrency)->first();
                    } else {
                        if ($isCrypto == false) {
                            $currencyInfo = collect($receivableCurrencies)->where('currency', $selectedPayCurrency)->first();
                        } else {
                            $currencyInfo = collect($receivableCurrencies)->where('currency', $selectedPayCurrency)->first();
                        }

                    }
                } else {
                    return null;
                }
            }

            if (!$currencyInfo) {
                return [
                    'status' => 'error',
                    'msg' => "Please choose the currency you'd like to use for payment"
                ];
            }

            if ($amount < $currencyInfo->min_limit || $amount > $currencyInfo->max_limit) {
                return [
                    'status' => 'error',
                    'msg' => "minimum payment $currencyInfo->min_limit and maximum payment limit $currencyInfo->max_limit"
                ];
            }

            $currencyType = $gateway->currency_type;
            $limit = $currencyType == 0 ? 8 : 2;

            if ($currencyInfo) {
                $percentage_charge = getAmount(($amount * $currencyInfo->percentage_charge) / 100, $limit);
                $fixed_charge = getAmount($currencyInfo->fixed_charge, $limit);
                $min_limit = getAmount($currencyInfo->min_limit, $limit);
                $max_limit = getAmount($currencyInfo->max_limit, $limit);
                $charge = getAmount($percentage_charge + $fixed_charge, $limit);
            }

            $basicControl = basicControl();
            $payable_amount = getAmount($amount + $charge, $limit);
            $amount_in_base_currency = getAmount($amount / $currencyInfo->conversion_rate, $limit);
            $base_currency_charge = getAmount($charge / $currencyInfo->conversion_rate ?? 1, $limit);

            $data['gateway_id'] = $gateway->id;
            $data['fixed_charge'] = $fixed_charge;
            $data['percentage_charge'] = $percentage_charge;
            $data['min_limit'] = $min_limit;
            $data['max_limit'] = $max_limit;
            $data['payable_amount'] = $payable_amount;
            $data['amount'] = $amount;
            $data['base_currency_charge'] = $base_currency_charge;
            $data['payable_amount_base_in_currency'] = $amount_in_base_currency;
            $data['currency'] = (!$isCrypto) ? ($currencyInfo->name ?? $currencyInfo->currency) : $cryptoCurrency ?? 'USD';
            $data['base_currency'] = $basicControl->base_currency;
            $data['currency_limit'] = $limit;

            return [
                'status' => 'success',
                'data' => $data
            ];
        } catch (Exception $e) {
            return [
                'status' => 'error',
                'msg' => $e->getMessage()
            ];
        }

    }
}
