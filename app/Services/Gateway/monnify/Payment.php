<?php

namespace App\Services\Gateway\monnify;

use App\Models\ApiOrder;
use App\Models\ApiOrderTest;
use App\Models\Invoice;
use App\Models\ProductOrder;
use App\Models\QRCode;
use App\Models\Voucher;
use Facades\App\Services\BasicCurl;
use Facades\App\Services\BasicService;

class Payment
{
	public static function prepareData($deposit, $gateway)
	{
		$basic = basicControl();
		$send['api_key'] = $gateway->parameters->api_key ?? '';
		$send['contract_code'] = $gateway->parameters->contract_code ?? '';
		$send['amount'] = round($deposit->payable_amount, 2);
		$send['currency'] = $deposit->payment_method_currency;
		$send['customer_name'] = optional($deposit->user)->name ?? $basic->site_title;
		$send['customer_email'] = optional($deposit->user)->email ?? optional($deposit->depositable)->email ?? $basic->sender_email;
		$send['customer_phone'] = optional($deposit->user)->phone ?? '';
		$send['ref'] = $deposit->trx_id;
		$send['description'] = "Pay to {$basic->site_title}";
		$send['view'] = 'user.payment.monnify';
		return json_encode($send);
	}

	public static function ipn($request, $gateway, $deposit = null, $trx = null, $type = null)
	{
		$apiKey = $gateway->parameters->api_key ?? '';
		$secretKey = $gateway->parameters->secret_key ?? '';
		if ($gateway->environment == 'test') {
			$url = "https://sandbox.monnify.com/api/v1/merchant/transactions/query?paymentReference={$trx}";
		} else {
			$url = "https://app.monnify.com/api/v1/merchant/transactions/query?paymentReference={$trx}";
		}
		$headers = [
			"Authorization: Basic " . base64_encode($apiKey . ':' . $secretKey)
		];
		$response = BasicCurl::curlGetRequestWithHeaders($url, $headers);
		$response = json_decode($response);
		if ($response->requestSuccessful && $response->responseMessage == "success") {
			if ($response->responseBody->amount == round($deposit->payable_amount, 2) && $response->responseBody->currencyCode == $deposit->payment_method_currency && $deposit->status == 0) {
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
			$data['msg'] = 'unable to Process.';
			$data['redirect'] = route('failed');
		}
		return $data;
	}
}
