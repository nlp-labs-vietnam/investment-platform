<?php

namespace App\Services\Gateway\cashonexHosted;

use Facades\App\Services\BasicCurl;
use Facades\App\Services\BasicService;
use Illuminate\Support\Facades\Http;



class Payment
{
    public static function prepareData($deposit, $gateway)
    {

        $idempotency_key = $gateway->parameters->idempotency_key??'727649-0h76ac-467573-fxoxli-141433-c5ugg1';
        $salt = $gateway->parameters->salt??'67a8d2c1548c1ddb616bdc27e31fbd5e385f7872204043df7219498f08e4dcda';

        $headers = [
            'Content-Type: application/json',
            "Idempotency-Key: $idempotency_key",
        ];

        $postParam = [
            "salt" => $salt,
            "last_name" => optional($deposit->user)->lastname,
            "first_name" => optional($deposit->user)->firstname,
            "email" => optional($deposit->user)->email??'email@gmail.com',
            "phone" => optional($deposit->user)->phone??'9999999999',
            "address" => optional($deposit->user)->address??'123, address',
            "city" => optional($deposit->user)->city??'City',
            "state" => optional($deposit->user)->city??'State',
            "country" => optional($deposit->user)->country??'GB',
            "zip_code" => optional($deposit->user)->zip_code??'90210',
            "amount" => round($deposit->payable_amount ,2),
            "currency" => $deposit->payment_method_currency,
            "orderid" => $deposit->trx_id,
            "clientip" => request()->ip(),
            "redirect_url" => route('success'),
            "webhook_url" => route('ipn', [$gateway->code, $deposit->trx_id])
        ];

        $url = "https://cashonex.com/api/rest/payment";
        $result = BasicCurl::curlPostRequestWithHeadersJson($url, $headers, $postParam);
        $response = json_decode($result);


        if (isset($response->success) && $response->success == true) {
            $deposit->btc_wallet = @$response->data->paymentId;
            $deposit->update();

            $send['redirect'] = true;
            $send['redirect_url'] = $response->data->redirectUrl;
        } else {
            $send['error'] = true;
            $send['message'] = 'Unexpected Error!';
        }
        return json_encode($send);
    }

    public static function ipn($request, $gateway, $deposit = null, $trx = null, $type = null)
    {
        if ($request['transaction_status'] == 'APPROVED' && $request['amount'] == round($deposit->final_amount ,2)) {
            BasicService::preparePaymentUpgradation($deposit);
        }

    }
}
