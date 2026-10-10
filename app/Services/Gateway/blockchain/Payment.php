<?php

namespace App\Services\Gateway\blockchain;

use Facades\App\Services\BasicCurl;
use Facades\App\Services\BasicService;

class Payment
{
    public static function prepareData($deposit, $gateway)
    {

        $apiKey = $gateway->parameters->api_key ?? '';
        $xpubCode = $gateway->parameters->xpub_code ?? '';

        $btcPriceUrl = "https://blockchain.info/ticker";
        $btcPriceResponse = BasicCurl::curlGetRequest($btcPriceUrl);
        $btcPriceResponse = json_decode($btcPriceResponse);
        $btcRate = $btcPriceResponse->USD->last;

        $usd = round($deposit->payable_amount, 2);
        $btcamount = $usd / $btcRate;
        $btc = round($btcamount, 8);
        if ($deposit->btc_amount == 0 || $deposit->btc_wallet == "") {
            $secret = $deposit->trx_id;
            $callback_url = route('ipn', [$gateway->code, $deposit->trx_id]) . "?invoice_id=" . $deposit->trx_id . "&secret=" . $secret;
            $url = "https://api.blockchain.info/v2/receive?key={$apiKey}&callback=" . urlencode($callback_url) . "&xpub={$xpubCode}";
            $response = BasicCurl::curlGetRequest($url);
            $response = json_decode($response);
            if (@$response->address == '') {
                $send['error'] = true;
                $send['message'] = 'BLOCKCHAIN API HAVING ISSUE. PLEASE TRY LATER. ' . $response->message ?? null;
            } else {
                $deposit['btc_wallet'] = $response->address;
                $deposit['btc_amount'] = $btc;
                $deposit->update();
            }
        }

        $send['amount'] = $deposit->btc_amount;
        $send['sendto'] = $deposit->btc_wallet;
        $send['img'] = BasicService::cryptoQR($deposit->btc_wallet, $deposit->btc_amount);
        $send['currency'] = $deposit->payment_method_currency ?? 'BTC';
        $send['view'] = 'user.payment.crypto';
        return json_encode($send);
    }

    public static function ipn($request, $gateway, $deposit = null, $trx = null, $type = null)
    {
        $btc = $request->value / 100000000;
        if ($deposit->btc_amount == $btc && $request->address == $deposit->btc_wallet && $request->secret == $deposit->trx_id && $request->confirmations > 2 && $deposit->status == 0) {
            BasicService::preparePaymentUpgradation($deposit);

            $data['status'] = 'success';
            $data['msg'] = 'Transaction was successful.';
            $data['redirect'] = route('success');
        } else {
            $data['status'] = 'error';
            $data['msg'] = 'Invalid response.';
            $data['redirect'] = route('failed');
        }
        return $data;
    }
}
