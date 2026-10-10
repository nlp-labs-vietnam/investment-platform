<?php

namespace App\Services\Gateway\cashmaal;

use App\Models\Deposit;
use Facades\App\Services\BasicService;

class Payment
{
    public static function prepareData($deposit, $gateway)
    {
        $val['pay_method'] = " ";
        $val['amount'] = round($deposit->payable_amount, 2);
        $val['currency'] = $deposit->payment_method_currency;
        $val['succes_url'] = route('success');
        $val['cancel_url'] = twoStepPrevious($deposit);
        $val['client_email'] = $deposit->email ?? 'example@gmail.com';
        $val['web_id'] = $gateway->parameters->web_id;
        $val['order_id'] = $deposit->trx_id;
        $val['addi_info'] = "Payment";
        $send['url'] = 'https://www.cashmaal.com/Pay/';
        $send['method'] = 'post';
        $send['view'] = 'user.payment.redirect';
        $send['val'] = $val;

        return json_encode($send);
    }

    public static function ipn($request, $gateway, $deposit = null, $trx = null, $type = null)
    {
        $deposit = Deposit::where('trx_id', $request->order_id)->orderBy('id', 'desc')->first();
        if ($deposit) {
            if ($request->currency == $deposit->payment_method_currency && ($request->Amount == round($deposit->payable_amount, 2)) && $deposit->status == 0) {
                BasicService::preparePaymentUpgradation($deposit);
            }
        }
    }
}
