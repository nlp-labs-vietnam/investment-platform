<?php

namespace App\Services\Gateway\coingate;

use App\Services\BasicService;
use CoinGate\Client;
use Facades\App\Services\BasicCurl;
class Payment
{
    public static function prepareData($deposit, $gateway)
    {
        try {
            $basic = basicControl();
            $env = $gateway->environment == 'live' ? 'live' : 'sandbox';


            $client = new Client();
            $client->setApiKey($gateway->parameters->api_key);
            $client->setEnvironment($env);


            $postParams = array(
                'order_id' => $deposit->trx_id,
                'price_amount' => round($deposit->payable_amount, 2),
                'price_currency' => $deposit->payment_method_currency,
                'receive_currency' => $deposit->payment_method_currency,
                'callback_url' => route('ipn', [$gateway->code, $deposit->trx_id]),
                'cancel_url' => twoStepPrevious($deposit),
                'success_url' => route('success'),
                'title' => "Pay To $basic->site_title",
                'description' => 'Payment to ' . basicControl()->site_title,
            );


            $order = $client->order->create($postParams);

            if ($order) {
                $send['redirect'] = true;
                $send['redirect_url'] = $order->payment_url;
                return json_encode($send);
            }

        } catch (\CoinGate\Exception\ApiErrorException $e) {
            $send['error'] = true;
            $send['message'] = $e->getMessage();
            return json_encode($send);

        } catch (\Exception $e) {
            $send['error'] = true;
            $send['message'] = 'Unexpected Error! Please Try Again';
            return json_encode($send);
        }
    }

    public static function ipn($request, $gateway, $deposit = null, $trx = null, $type = null)
    {
        $ip = $request->ip();
        $url = 'https://api.coingate.com/v2/ips-v4';
        $response = BasicCurl::curlGetRequest($url);
        if (strpos($response, $ip) !== false) {
            if ($request->status == 'paid' && $request->price_amount == round($deposit->payable_amount, 2)) {
                BasicService::preparePaymentUpgradation($deposit);
            }
        }
    }
}
