<?php

namespace App\Services;

use App\Models\Property;
use App\Traits\Notify;
use App\Traits\Upload;
use Carbon\Carbon;


class NotifyMailService
{
    use Upload, Notify;

    public function investmentProfit($user, $investment, $basic, $transaction, $amount)
    {
        $property = Property::find($investment->property_id);
        $msg = [
            'username' => $user->username,
            'amount' => $amount == null ? 0 : getAmount($amount),
            'currency' => basicControl()->currency_symbol,
            'property_name' => $property->title
        ];
        $action = [
            "link" => route('admin.transaction'),
            "icon" => "fa fa-money-bill-alt"
        ];

        $userAction = [
            "link" => route('user.transaction'),
            "icon" => "fa fa-money-bill-alt"
        ];

        $this->adminPushNotification('INVEST_PROFIT_NOTIFY_TO_ADMIN', $msg, $action);
        $this->userPushNotification($user, 'INVEST_PROFIT_NOTIFY_TO_USER', $msg, $userAction);

    }

    public function investmentCapitalBack($user, $investment, $basic, $transaction, $capital)
    {
        $currentDate = Carbon::now();
        $property = Property::find($investment->property_id);
        $msg = [
            'username' => $user->username,
            'amount' => getAmount($capital),
            'currency' => basicControl()->currency_symbol,
            'property_name' => $property->title
        ];
        $action = [
            "link" => route('admin.transaction'),
            "icon" => "fa fa-money-bill-alt"
        ];

        $userAction = [
            "link" => route('user.transaction'),
            "icon" => "fa fa-money-bill-alt"
        ];


        $this->adminPushNotification('INVESTMENT_CAPITAL_BACK_NOTIFY_TO_ADMIN', $msg, $action);
        $this->userPushNotification($user, 'INVESTMENT_CAPITAL_BACK_NOTIFY_TO_USER', $msg, $userAction);

        $this->sendMailSms($user, $type = 'INVESTMENT_CAPITAL_BACK_NOTIFY_TO_USER', [
            'username' => $user->username,
            'amount' => getAmount($investment->net_profit),
            'currency' => basicControl()->currency_symbol,
            'property_name' => $property->title,
            'transaction_id' => $transaction->trx_id,
            'date' => $currentDate,
        ]);

        $this->adminMail('INVESTMENT_CAPITAL_BACK_NOTIFY_TO_ADMIN', [
            'username' => $user->username,
            'amount' => getAmount($investment->net_profit),
            'currency' => basicControl()->currency_symbol,
            'property_name' => $property->title,
            'transaction_id' => $transaction->trx_id,
            'date' => $currentDate,
        ]);
    }
}
