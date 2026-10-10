<?php

namespace App\Services\Gateway\midtrans;

use Facades\App\Services\BasicCurl;
use Facades\App\Services\BasicService;
use Midtrans\Config;


class Payment
{
	public static function prepareData($deposit, $gateway)
	{
//		Config::$serverKey = $gateway->parameters->server_key ?? '';
//		Config::$isProduction = $gateway->environment == 'live';
//		Config::$isSanitized = true;
//		Config::$is3ds = true;
//		Config::$overrideNotifUrl = route('ipn', 'midtrans');

        \Midtrans\Config::$serverKey = $gateway->parameters->server_key ?? '';
        \Midtrans\Config::$isProduction = false;
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;
        \Midtrans\Config::$overrideNotifUrl = route('ipn', 'midtrans');

		$params = array(
			'transaction_details' => array(
				'order_id' => $deposit->trx_id,
				'gross_amount' => round($deposit->payable_amount, 2) * 100,
			),
			'customer_details' => array(
				'first_name' => optional($deposit->user)->firstname ?? 'John',
				'last_name' => optional($deposit->user)->lastname ?? 'Doe',
				'email' => optional($deposit->user)->email ?? 'example@gmail.com',
			),
		);

		$send['environment'] = $gateway->environment == 'live' ? 'live' : 'test';
		$send['client_key'] = $gateway->parameters->client_key ?? '';
		$send['token'] = \Midtrans\Snap::getSnapToken($params);

        $send['view'] = 'user.payment.midtrans';

		return json_encode($send);
	}

	/**
	 * @param $request
	 * @param $gateway
	 * @param null $order
	 * @param null $trx
	 * @param null $type
	 * @return mixed
	 */
	public static function ipn($request, $gateway, $deposit = null, $trx = null, $type = null)
	{
		if ($gateway->environment == 'live') {
			$url = "https://api.midtrans.com/v2/{$deposit->trx_id}/status";
		} else {
			$url = "https://api.sandbox.midtrans.com/v2/{$deposit->trx_id}/status";
		}
		$serverKey = $gateway->parameters->server_key ?? '';
		$headers = [
			'Content-Type:application/json',
			'Authorization:Basic ' . base64_encode("{$serverKey}:")
		];
		$response = BasicCurl::curlGetRequestWithHeaders($url, $headers);
		$paymentData = json_decode($response, true);
		if (isset($paymentData['transaction_status']) && ($paymentData['transaction_status'] == 'capture' || $paymentData['transaction_status'] == 'settlement')) {
			if ($paymentData['currency'] == $deposit->payment_method_currency && $paymentData['gross_amount'] == round($deposit->payable_amount, 2) * 100) {
				BasicService::preparePaymentUpgradation($deposit);

				$data['status'] = 'success';
				$data['msg'] = 'Transaction was successful.';
				$data['redirect'] = route('success');
			} else {
				$data['status'] = 'error';
				$data['msg'] = 'invalid amount.';
				$data['redirect'] = route('failed');
			}
		} elseif (isset($paymentData['transaction_status']) && $paymentData['transaction_status'] == 'pending') {
			$data['status'] = 'error';
			$data['msg'] = 'Your payment is on pending.';
			$data['redirect'] = route('user.dashboard');
		} else {
			$data['status'] = 'error';
			$data['msg'] = 'unexpected error!';
			$data['redirect'] = route('failed');
		}
		return $data;
	}
}
