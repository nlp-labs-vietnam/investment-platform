<?php

namespace App\Services\Gateway\perfectmoney;

use App\Models\ApiOrder;
use App\Models\ApiOrderTest;
use App\Models\Invoice;
use App\Models\ProductOrder;
use App\Models\QRCode;
use App\Models\Voucher;
use Facades\App\Services\BasicService;
use Illuminate\Support\Facades\Auth;

class Payment
{
	public static function prepareData($deposit, $gateway)
	{
		$basic = basicControl();
		$val['PAYEE_ACCOUNT'] = trim($gateway->parameters->payee_account);
		$val['PAYEE_NAME'] = optional($deposit->user)->name ?? $basic->site_title;
		$val['PAYMENT_ID'] = $deposit->trx_id;
		$val['PAYMENT_AMOUNT'] = round($deposit->payable_amount, 2);
		$val['PAYMENT_UNITS'] = $deposit->payment_method_currency;
		$val['STATUS_URL'] = route('ipn', [$gateway->code, $deposit->trx_id]);
		$val['PAYMENT_URL'] = route('success');
		$val['PAYMENT_URL_METHOD'] = 'POST';
		$val['NOPAYMENT_URL'] = route('failed');
		$val['NOPAYMENT_URL_METHOD'] = 'POST';
		$val['SUGGESTED_MEMO'] = optional($deposit->user)->name ?? $basic->site_title;
		$val['BAGGAGE_FIELDS'] = 'IDENT';
		$send['val'] = $val;
		$send['view'] = 'user.payment.redirect';
		$send['method'] = 'post';
		$send['url'] = 'https://perfectmoney.is/api/step1.asp';
		return json_encode($send);
	}

	public static function ipn($request, $gateway, $deposit = null, $trx = null, $type = null)
	{
		$passphrase = strtoupper(md5(trim($gateway->parameters->passphrase)));
		define('ALTERNATE_PHRASE_HASH', $passphrase);
		define('PATH_TO_LOG', '/assets/upload/');
		$string =
			$request->PAYMENT_ID . ':' . $request->PAYEE_ACCOUNT . ':' .
			$request->PAYMENT_AMOUNT . ':' . $request->PAYMENT_UNITS . ':' .
			$request->PAYMENT_BATCH_NUM . ':' .
			$request->PAYER_ACCOUNT . ':' . ALTERNATE_PHRASE_HASH . ':' .
			$request->TIMESTAMPGMT;

		$hash = strtoupper(md5($string));
		$hash2 = $request->V2_HASH;

		if ($hash == $hash2) {
			$amount = $request->PAYMENT_AMOUNT;
			$unit = $request->PAYMENT_UNITS;
			if ($request->PAYEE_ACCOUNT == trim($gateway->parameters->payee_account) && $unit == $deposit->payment_method_currency && $amount == $deposit->payable_amount && $deposit->status == 0) {
				BasicService::preparePaymentUpgradation($deposit);
			}
		}
	}
}
