<?php

namespace App\Services\Gateway\flutterwave;

use Facades\App\Services\BasicCurl;
use Facades\App\Services\BasicService;


class Payment
{
    public static function prepareData($deposit, $gateway)
    {
        $send['API_publicKey'] = $gateway->parameters->public_key ?? '';
        $send['customer_email'] = optional($deposit->user)->email ?? 'example@gmail.com';
        $send['customer_phone'] = optional($deposit->receiver)->phone ?? '';
        $send['amount'] = round($deposit->payable_amount, 2);
        $send['currency'] = $deposit->payment_method_currency;
        $send['txref'] = $deposit->trx_id;
        $send['view'] = 'user.payment.flutterwave';
        return json_encode($send);
    }

    public static function ipn($request, $gateway, $deposit = null, $trx = null, $type = null)
    {
        if ($type == 'error') {
            $data['status'] = 'error';
            $data['msg'] = 'transaction Failed.';
            $data['redirect'] = route('failed');
        } else {

            $url = 'https://api.ravepay.co/flwv3-pug/getpaidx/api/v2/verify';
            $headers = ['Content-Type:application/json'];
            $postParam = array(
                "SECKEY" => $gateway->parameters->secret_key ?? '',
                "txref" => $deposit->trx_id
            );

            $response = BasicCurl::curlPostRequestWithHeaders($url, $headers, $postParam);
            $response = json_decode($response);
            if ($response->data->status == "successful" && $response->data->chargecode == "00" && round($deposit->payable_amount, 2) == $response->data->amount && $deposit->payment_method_currency == $response->data->currency && $deposit->status == 0) {
                BasicService::preparePaymentUpgradation($deposit);

                $data['status'] = 'success';
                $data['msg'] = 'Transaction was successful.';
                $data['redirect'] = route('success');
            } else {
                $data['status'] = 'error';
                $data['msg'] = 'unable to Process.';
                $data['redirect'] = route('failed');
            }
        }
        return $data;
    }
}
