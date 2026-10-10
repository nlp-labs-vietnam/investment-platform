<?php

namespace App\Services\Gateway\payeer;

use App\Models\ApiOrder;
use App\Models\ApiOrderTest;
use App\Models\Deposit;
use App\Models\Invoice;
use App\Models\ProductOrder;
use App\Models\QRCode;
use App\Models\Voucher;
use Facades\App\Services\BasicService;

class Payment
{
	public static function prepareData($deposit, $gateway)
	{
		$basic = basicControl();
		$m_amount = number_format($deposit->payable_amount, 2, '.', "");

		$arHash = [
			trim($gateway->parameters->merchant_id),
			$deposit->trx_id,
			$m_amount,
			$deposit->payment_method_currency,
			base64_encode("Pay To $basic->site_title"),
			trim($gateway->parameters->secret_key)
		];

		$val['m_shop'] = trim($gateway->parameters->merchant_id);
		$val['m_orderid'] = $deposit->trx_id;
		$val['m_amount'] = round($deposit->payable_amount, 2);
		$val['m_curr'] = $deposit->payment_method_currency;
		$val['m_desc'] = base64_encode("Pay To $basic->site_title");
		$val['m_sign'] = strtoupper(hash('sha256', implode(":", $arHash)));
		$send['val'] = $val;
		$send['view'] = 'user.payment.redirect';
		$send['method'] = 'get';
		$send['url'] = 'https://payeer.com/merchant';
		return json_encode($send);
	}

	public static function ipn($request, $gateway, $deposit = null, $trx = null, $type = null)
	{
		if (isset($request->m_operation_id) && isset($request->m_sign)) {
			$sign_hash = strtoupper(hash('sha256', implode(":", array(
				$request->m_operation_id,
				$request->m_operation_ps,
				$request->m_operation_date,
				$request->m_operation_pay_date,
				$request->m_shop,
				$request->m_orderid,
				$request->m_amount,
				$request->m_curr,
				$request->m_desc,
				$request->m_status,
				$gateway->parameters->secret_key
			))));

			if ($request->m_sign != $sign_hash) {
				$data['status'] = 'error';
				$data['msg'] = 'digital signature not matched';
				$data['redirect'] = route('failed');
			} else {
				$deposit = Deposit::with('user')->where('trx_id', $request->m_orderid)->latest()->first();
				if ($request->m_amount == $deposit->payable_amount && $request->m_curr == $deposit->payment_method_currency && $request->m_status == 'success' && $deposit->status == 0) {
					BasicService::preparePaymentUpgradation($deposit);
					$data['status'] = 'success';
					$data['msg'] = 'Transaction was successful.';
					$data['redirect'] = route('success');
					return $data;
				} else {
					$data['status'] = 'error';
					$data['msg'] = 'transaction was unsuccessful';
					$data['redirect'] = route('failed');
					return $data;
				}
			}
		} else {
			$data['status'] = 'error';
			$data['msg'] = 'transaction was unsuccessful';
			$data['redirect'] = route('failed');
		}
		return $data;
	}
}
