<?php

namespace App\Services\Gateway\coinpayments;

use Facades\App\Services\BasicService;

class Payment
{
	public static function prepareData($deposit, $gateway)
	{
		$basic = basicControl();

		$isCrypto = (checkTo($gateway->currencies, $deposit->payment_method_currency) == 1) ? true : false;

		if ($isCrypto == false) {
			$val['merchant'] = $gateway->parameters->merchant_id ?? '';
			$val['item_name'] = $basic->site_title;
			$val['currency'] = $deposit->payment_method_currency;
			$val['currency_code'] = $deposit->payment_method_currency;
			$val['amountf'] = round($deposit->payable_amount, 2);
			$val['ipn_url'] = route('ipn', [$gateway->code, $deposit->trx_id]);
			$val['custom'] = $deposit->trx_id;
			$val['amount'] = round($deposit->payable_amount, 2);
			$val['return'] = route('ipn', [$gateway->code, $deposit->trx_id]);
			$val['cancel_return'] = twoStepPrevious($deposit);
			$val['notify_url'] = route('ipn', [$gateway->code, $deposit->trx_id]);
			$val['success_url'] = route('success');
			$val['cancel_url'] = twoStepPrevious($deposit);
			$val['cmd'] = '_pay_simple';
			$val['want_shipping'] = 0;
			$send['val'] = $val;
			$send['view'] = 'user.payment.redirect';
			$send['method'] = 'post';
			$send['url'] = 'https://www.coinpayments.net/index.php';

		} else {

			if ($deposit->btc_wallet == 0 || $deposit->btc_wallet == "") {
				$cps = new CoinPaymentHosted();
				$cps->Setup($gateway->parameters->private_key, $gateway->parameters->public_key);
				$callbackUrl = route($gateway->extra_parameters->callback, $gateway->code);


				$req = array(
					'amount' => round($deposit->payable_amount, 2),
					'currency1' => 'USD',
					'currency2' => $deposit->payment_method_currency,
					'custom' => $deposit->trx_id,
					'ipn_url' => $callbackUrl,
					'buyer_email' => $deposit->email ?? 'hello@example.com'
				);
				$result = $cps->CreateTransaction($req);

				if ($result['error'] == 'ok') {
					$btc = sprintf('%.08f', $result['result']['amount']);
					$wallet = $result['result']['address'];
					$deposit['btc_wallet'] = $wallet;
					$deposit['btc_amount'] = $btc;
					$deposit->update();


					$send['amount'] = $deposit->btc_amount;
					$send['sendto'] = $deposit->btc_wallet;

					$send['img'] = BasicService::cryptoQR($deposit->btc_wallet, $deposit->btc_amount);
					$send['currency'] = $deposit->payment_method_currency ?? 'BTC';
					$send['view'] = 'user.payment.crypto';

				} else {
					$send['error'] = true;
					$send['message'] = $result['error'];
				}
			}
		}
		return json_encode($send);
	}

	public static function ipn($request, $gateway, $deposit = null, $trx = null, $type = null)
	{

		$isCrypto = (checkTo($gateway->currencies, $deposit->payment_method_currency) == 1) ? true : false;

		$amount1 = floatval($request->amount1) ?? 0;
		$amount2 = floatval($request->amount2) ?? 0;
		$status = $request->status;
		if ($status >= 100 || $status == 2) {

			if ($deposit->payment_method_currency == $request->currency1 && round($deposit->payable_amount, 2) <= $amount1 && $gateway->parameters->merchant_id == $request->merchant && $deposit->status == '0') {
				BasicService::preparePaymentUpgradation($deposit);
			} elseif ($deposit->payment_method_currency == $request->currency2 && round($deposit->payable_amount, 2) <= $amount2 && $gateway->parameters->merchant_id == $request->merchant && $deposit->status == '0') {
				BasicService::preparePaymentUpgradation($deposit);
			} else {
				$data['status'] = 'error';
				$data['msg'] = 'Invalid amount.';
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
