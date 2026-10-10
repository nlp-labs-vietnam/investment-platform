<?php

namespace App\Services\Gateway\razorpay;

use Facades\App\Services\BasicService;
use Razorpay\Api\Api;


class Payment
{
	public static function prepareData($deposit, $gateway)
	{
		$basic = basicControl();
		$api_key = $gateway->parameters->key_id ?? '';
		$api_secret = $gateway->parameters->key_secret ?? '';
		$razorPayApi = new Api($api_key, $api_secret);
		$finalAmount = round($deposit->payable_amount, 2) * 100;
		$gatewayCurrency = $deposit->payment_method_currency;
		$trx = $deposit->trx_id;

		$razorOrder = $razorPayApi->order->create(
			array(
				'receipt' => $trx,
				'amount' => $finalAmount,
				'currency' => $gatewayCurrency,
				'payment_capture' => '0'
			)
		);

		$val['key'] = $api_key;
		$val['amount'] = $finalAmount;
		$val['currency'] = $gatewayCurrency;
		$val['order_id'] = $razorOrder['id'];
		$val['buttontext'] = "Pay Now";
		$val['name'] = optional($deposit->user)->name ?? $basic->site_title;
		$val['description'] = "Payment By Razorpay";
		$val['image'] = getFile(config('location.logo.path') . 'logo.png');
		$val['prefill.name'] = optional($deposit->user)->name ?? $basic->site_title;
		$val['prefill.email'] = optional($deposit->user)->email ?? optional($deposit->depositable)->email ?? $basic->sender_email;
		$val['prefill.contact'] = optional($deposit->user)->phone ?? '';
		$val['theme.color'] = "#2ecc71";
		$send['val'] = $val;

		$send['method'] = 'POST';
		$send['url'] = route('ipn', [$gateway->code, $deposit->trx_id]);
		$send['custom'] = $trx;
		$send['checkout_js'] = "https://checkout.razorpay.com/v1/checkout.js";
		$send['view'] = 'user.payment.razorpay';
		return json_encode($send);
	}

	public static function ipn($request, $gateway, $order = null, $trx = null, $type = null)
	{
		$api_secret = $gateway->parameters->key_secret ?? '';
		$signature = hash_hmac('sha256', $request->razorpay_order_id . "|" . $request->razorpay_payment_id, $api_secret);

		if ($signature == $request->razorpay_signature) {
			BasicService::preparePaymentUpgradation($order);

			$data['status'] = 'success';
			$data['msg'] = 'Transaction was successful.';
			$data['redirect'] = route('success');
		} else {
			$data['status'] = 'error';
			$data['msg'] = 'unexpected error!';
			$data['redirect'] = route('failed');
		}

		return $data;
	}
}
