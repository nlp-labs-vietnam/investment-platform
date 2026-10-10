<?php

namespace App\Services\Gateway\paypal;


use Facades\App\Services\BasicCurl;
use Facades\App\Services\BasicService;

class Payment
{
	public static function prepareData($deposit, $gateway)
	{
		$basic = basicControl();
		$send['cleint_id'] = $gateway->parameters->cleint_id ?? '';
		$send['description'] = "Payment To {$basic->site_title} Account";
		$send['custom_id'] = $deposit->trx_id;
		$send['amount'] = round($deposit->payable_amount, 2);
		$send['currency'] = $deposit->payment_method_currency;
		$send['view'] = 'user.payment.paypal';
		return json_encode($send);
	}

	public static function ipn($request, $gateway, $deposit = null, $trx = null, $type = null)
	{
		if ($gateway->environment == 'live') {
			$url = "https://api-m.paypal.com/v2/checkout/orders/{$type}";
		} else {
			$url = "https://api-m.sandbox.paypal.com/v2/checkout/orders/{$type}";
		}

		$client_id = $gateway->parameters->cleint_id ?? '';
		$secret = $gateway->parameters->secret ?? '';
		$headers = [
			'Content-Type:application/json',
			'Authorization:Basic ' . base64_encode("{$client_id}:{$secret}")
		];
		$response = BasicCurl::curlGetRequestWithHeaders($url, $headers);
		$paymentData = json_decode($response, true);
		if (isset($paymentData['status']) && $paymentData['status'] == 'COMPLETED') {
			if ($paymentData['purchase_units'][0]['amount']['currency_code'] == $deposit->payment_method_currency && $paymentData['purchase_units'][0]['amount']['value'] == round($deposit->payable_amount, 2)) {
				BasicService::preparePaymentUpgradation($deposit);
				$data['status'] = 'success';
				$data['msg'] = 'Transaction was successful.';
				$data['redirect'] = route('success');
			} else {
				$data['status'] = 'error';
				$data['msg'] = 'invalid amount.';
				$data['redirect'] = route('failed');
			}
		} else {
			$data['status'] = 'error';
			$data['msg'] = 'unexpected error!';
			$data['redirect'] = route('failed');
		}
		return $data;
	}
}
