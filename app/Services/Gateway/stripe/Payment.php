<?php

namespace App\Services\Gateway\stripe;

use Facades\App\Services\BasicService;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Stripe\Checkout\Session as CheckoutSession;


class Payment
{
    public static function prepareData($deposit, $gateway)
    {
        $basic = basicControl();
        $amount = (int)(round($deposit->payable_amount) * 100);
        Stripe::setApiKey($gateway->parameters->secret_key);

        $checkoutSession = CheckoutSession::create([
            'line_items' => [[
                'price_data' => [
                    'currency' => $deposit->payment_method_currency,
                    'product_data' => [
                        'name' => optional($deposit->receiver)->name ?? $basic->site_title,
                    ],
                    'unit_amount' => $amount,
                ],
                'quantity' => 1,
            ]],
            'mode' => 'payment',
            'success_url' => route('ipn', [$gateway->code, $deposit->trx_id]). '?payment_intent={CHECKOUT_SESSION_ID}',
            'cancel_url' => route('failed'),
        ]);
        if (isset($checkoutSession->url)) {
            $send['redirect'] = true;
            $send['redirect_url'] = $checkoutSession->url;
        } else {
            $send['error'] = true;
            $send['message'] = 'Unexpected Error! Please Try Again';
        }
        return json_encode($send);
    }

    public static function ipn($request, $gateway, $deposit = null, $trx = null, $type = null)
    {

        Stripe::setApiKey($gateway->parameters->secret_key);


        $checkoutSession = CheckoutSession::retrieve($request->payment_intent);
        $paymentIntentId = $checkoutSession->payment_intent;

        $paymentIntent = PaymentIntent::retrieve($paymentIntentId);

        if ($paymentIntent->status == 'succeeded') {
            BasicService::preparePaymentUpgradation($deposit);

            $data['status'] = 'success';
            $data['msg'] = 'Transaction was successful.';
            $data['redirect'] = route('success');
        } else {
            $data['status'] = 'error';
            $data['msg'] = 'Unsuccessful transaction.';
            $data['redirect'] = route('failed');
        }

        return $data;
    }
}
