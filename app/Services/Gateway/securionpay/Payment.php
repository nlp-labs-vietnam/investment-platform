<?php

namespace App\Services\Gateway\securionpay;

use Facades\App\Services\BasicService;
use SecurionPay\Exception\SecurionPayException;
use SecurionPay\SecurionPayGateway;

class Payment
{
	public static function prepareData($deposit, $gateway)
	{
		$send['view'] = 'user.payment.card';
		return json_encode($send);
	}

	public static function ipn($request, $gateway, $deposit = null, $trx = null, $type = null)
	{
		$prepareGateway = new SecurionPayGateway($gateway->parameters->secret_key);
		$finalAmount = ceil($deposit->payable_amount);
		$request = array(
			'amount' => $finalAmount,
			'currency' => $deposit->payment_method_currency,
			'card' => array(
				'number' => $request->card_number,
				'expMonth' => $request->expiry_month,
				'expYear' => $request->expiry_year
			)
		);

		try {
			$charge = $prepareGateway->createCharge($request);

			if ($charge->getAmount() == $finalAmount && $charge->getCurrency() == $deposit->payment_method_currency) {
				BasicService::preparePaymentUpgradation($deposit);

				$data['status'] = 'success';
				$data['msg'] = 'Transaction was successful.';
				$data['redirect'] = route('success');
			} else {
				$data['status'] = 'error';
				$data['msg'] = 'unsuccessful transaction.';
				$data['redirect'] = route('failed');
			}

		} catch (SecurionPayException $e) {
			$data['status'] = 'error';
			$data['msg'] = $e->getMessage();
			$data['redirect'] = route('failed');
		}
		return $data;
	}
}
