<?php

namespace App\Services\Gateway\coinbasecommerce;

use Facades\App\Services\BasicCurl;
use Facades\App\Services\BasicService;


class Payment
{
	public static function prepareData($deposit, $gateway)
	{
		$basic = basicControl();
		$apiKey = $gateway->parameters->api_key ?? '';

		$postParam = [
			'name' => optional($deposit->user)->name ?? $basic->site_title,
			'description' => "Pay to $basic->site_title",
			'local_price' => [
				'amount' => round($deposit->payable_amount, 2),
				'currency' => $deposit->payment_method_currency
			],
			'metadata' => [
				'trx' => $deposit->trx_id
			],
			'pricing_type' => "fixed_price",
			'redirect_url' => route('ipn', [$gateway->code, $deposit->trx_id]),
			'cancel_url' => twoStepPrevious($deposit)
		];

		$url = 'https://api.commerce.coinbase.com/charges';
		$headers = [
			'Content-Type:application/json',
			'X-CC-Api-Key: ' . "$apiKey",
			'X-CC-Version: 2018-03-22'];
		$response = BasicCurl::curlPostRequestWithHeaders($url, $headers, $postParam);
		$response = json_decode($response);

		if (@$response->error == '') {
			$send['redirect'] = true;
			$send['redirect_url'] = $response->data->hosted_url;
		} else {
			$send['error'] = true;
			$send['message'] = 'Some Problem Occurred. Try Again';
		}
		return json_encode($send);
	}

	public static function ipn($request, $gateway, $deposit = null, $trx = null, $type = null)
	{
		$sentSign = $request->header('X-Cc-Webhook-Signature');
		$sig = hash_hmac('sha256', $request, $gateway->parameters->secret);
		if ($sentSign == $sig) {
			if ($request->event->type == 'charge:confirmed' && $deposit->status == 0) {
				BasicService::preparePaymentUpgradation($deposit);

				$data['status'] = 'success';
				$data['msg'] = 'Transaction was successful.';
				$data['redirect'] = route('success');
			} else {
				$data['status'] = 'error';
				$data['msg'] = 'Invalid response.';
				$data['redirect'] = route('failed');
			}
		} else {
			$data['status'] = 'error';
			$data['msg'] = 'Invalid response.';
			$data['redirect'] = route('failed');
		}
		return $data;
	}
}
