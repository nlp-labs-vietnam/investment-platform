<?php

namespace App\Services\Gateway\fastpay;

use Facades\App\Services\BasicCurl;
use Facades\App\Services\BasicService;

class Payment
{
	public static function prepareData($deposit, $gateway)
	{
		$basic = (object)config('basic');
		$store_id = $gateway->parameters->store_id ?? '';
		$store_password = $gateway->parameters->store_password ?? '';
		$order_id = $deposit->trx_id;
		$bill_amount = (int)round($deposit->payable_amount, 2);
		$currency = $deposit->payment_method_currency;

		if ($gateway->environment == 'live' && $deposit->mode == 0) {
			$url = "https://apigw-merchant.fast-pay.iq/api/v1/public/pgw/payment/initiation";
		} else {
			$url = "https://staging-apigw-merchant.fast-pay.iq/api/v1/public/pgw/payment/initiation";
		}

		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => $url,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_POSTFIELDS => array('store_id' => $store_id, 'store_password' => $store_password, 'order_id' => $order_id, 'bill_amount' => $bill_amount, 'currency' => $currency, 'cart' => '[{"name":"Scarf","qty":1,"unit_price":1000,"sub_total":1000}]'),
			CURLOPT_HTTPHEADER => array(
				'Cookie: cookiesession1=678B286D4B6C7555E2DFC4B98D7F35FE'
			),
		));

		$response = curl_exec($curl);

		curl_close($curl);
		$res = json_decode($response);

		if ($deposit) {
			if (isset($res->data->redirect_uri)) {
				$send['redirect'] = true;
				$send['redirect_url'] = $res->data->redirect_uri;
			} else {
				$send['error'] = true;
				$send['message'] = 'Unexpected Error! Please Try Again';
			}
		} else {
			$send['error'] = true;
			$send['message'] = 'Unexpected Error! Please Try Again';
		}

		return json_encode($send);
	}

	public static function ipn($request, $gateway, $deposit = null, $trx = null, $type = null)
	{
		$store_id = $gateway->parameters->store_id ?? '';
		$store_password = $gateway->parameters->store_password ?? '';
		$order_id = $deposit->trx_id;

		if ($gateway->environment == 'live' && $deposit->mode == 0) {
			$url = "https://apigw-merchant.fast-pay.iq/api/v1/public/pgw/payment/validate";
		} else {
			$url = "https://staging-apigw-merchant.fast-pay.iq/api/v1/public/pgw/payment/validate";
		}

		$curl = curl_init();

		curl_setopt_array($curl, array(
			CURLOPT_URL => $url,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => '',
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 0,
			CURLOPT_FOLLOWLOCATION => true,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => 'POST',
			CURLOPT_POSTFIELDS => array('store_id' => $store_id, 'store_password' => $store_password, 'order_id' => $order_id),
			CURLOPT_HTTPHEADER => array(
				'Cookie: cookiesession1=678B286D4B6C7555E2DFC4B98D7F35FE'
			),
		));

		$response = curl_exec($curl);
		curl_close($curl);
		$paymentData = json_decode($response, true);

		if (isset($paymentData['status']) && $paymentData['status'] == 'Success') {
			BasicService::preparePaymentUpgradation($deposit);
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
