<?php

namespace App\Services\Gateway\instamojo;

use Facades\App\Services\BasicCurl;
use Facades\App\Services\BasicService;

class Payment
{
	public static function prepareData($deposit, $gateway)
	{
		$basic = basicControl();
		$api_key = trim($gateway->parameters->api_key);
		$auth_token = trim($gateway->parameters->auth_token);
		$url = 'https://instamojo.com/api/1.1/payment-requests/';
		$headers = [
			"X-Api-Key:$api_key",
			"X-Auth-Token:$auth_token"
		];


		$postParam = [
			'purpose' => 'Payment to ' . $basic->site_title ?? 'Bug Finder',
			'amount' => round($deposit->payable_amount, 2),
			'buyer_name' => optional($deposit->user)->name ?? $basic->site_title,
			'redirect_url' => route('success'),
			'webhook' => route('ipn', [$gateway->code, $deposit->trx_id]),
			'email' => optional($deposit->user)->email ?? optional($deposit->depositable)->email ?? $basic->sender_email,
			'send_email' => true,
			'allow_repeated_payments' => false
		];

		$response = BasicCurl::curlPostRequestWithHeaders($url, $headers, $postParam);

		$response = json_decode($response);

		if ($response->success) {
			$send['redirect'] = true;
			$send['redirect_url'] = $response->payment_requests[0]->longurl;
		} else {
			$send['error'] = true;
			$send['message'] = "Invalid Request";
		}
		return json_encode($send);
	}

	public static function ipn($request, $gateway, $deposit = null, $trx = null, $type = null)
	{
		$salt = trim($gateway->parameters->salt);
		$imData = $request;
		$macSent = $imData['mac'];
		unset($imData['mac']);
		ksort($imData, SORT_STRING | SORT_FLAG_CASE);
		$mac = hash_hmac("sha1", implode("|", $imData), $salt);

		if ($macSent == $mac && $imData['status'] == "Credit" && $deposit->status == '0') {
			BasicService::preparePaymentUpgradation($deposit);
		}
	}
}
