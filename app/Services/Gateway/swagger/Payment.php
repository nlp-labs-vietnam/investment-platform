<?php

namespace App\Services\Gateway\swagger;

use App\Models\Deposit;
use Facades\App\Services\BasicCurl;
use Facades\App\Services\BasicService;

class Payment
{
    public static function prepareData($deposit, $gateway)
    {
        $val['account'] = $gateway->parameters->MAGUA_PAY_ACCOUNT;
        $val['order_id'] = $deposit->trx_id;
        $val['amount'] = (int)round($deposit->payable_amount);
        $val['currency'] = $deposit->payment_method_id;
        $val['recurrent'] = false;
        $val['purpose'] = "Online Payment";
        $val['customer_first_name'] = "John";
        $val['customer_last_name'] = optional($deposit->user)->lastname ?? "Doe";
        $val['customer_address'] = optional($deposit->user)->address ?? "10 Downing Street";
        $val['customer_city'] = optional($deposit->user)->city ?? "London";
        $val['customer_zip_code'] = optional($deposit->user)->zip_code ?? "121165";
        $val['customer_country'] = "GB";
        $val['customer_phone'] = optional($deposit->user)->phone ?? "+79000000000";
        $val['customer_email'] = $deposit->email ?? "johndoe@mail.com";

        $val['customer_ip_address'] = request()->ip();
        $val['merchant_site'] = url('/');

        $val['success_url'] = route('success');
        $val['fail_url'] = route('failed');
        $val['callback_url'] = route('ipn', $gateway->code);
        $val['status_url'] = route('ipn', $gateway->code);

        if ($gateway->environment == 'test' || $deposit->mode == 1) {
            $url = "https://merchantapi.magua-pay.com/initPayment";
        } else {
            $url = "https://api-gateway.magua-pay.com/initPayment";
        }
        $header = array();
        $header[] = 'Content-Type: application/json';
        $header[] = 'Authorization: Basic ' . base64_encode($gateway->parameters->MerchantKey . ":" . $gateway->parameters->Secret);

        $response = BasicCurl::curlPostRequestWithHeaders($url, $header, $val);

        $response = json_decode($response);

        if (isset($response->form_url)) {
            $send['redirect'] = true;
            $send['redirect_url'] = $response->form_url;
        } else {
            $send['error'] = true;
            $send['message'] = "Invalid Request";
        }
        return json_encode($send);
    }

    public static function ipn($request, $gateway, $deposit = null, $trx = null, $type = null)
    {
        $order = Deposit::where('trx_id', $request->orderId)->orderBy('id', 'DESC')->first();
        if ($order) {
            if ($request->status == 2 && $request->currency == $order->gateway_currency && ($request->amount == (int)round($order->payable_amount)) && $order->status == 0) {
                BasicService::preparePaymentUpgradation($order);
            }
        }
    }
}
