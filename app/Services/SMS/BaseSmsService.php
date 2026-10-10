<?php

namespace App\Services\SMS;

class BaseSmsService
{
    public function sendSMS($destination, $message)
    {
        $smsObj = 'Facades\\App\\Services\\SMS\\' . ucfirst(config('SMSConfig.default')) . 'Service';
        $data = $smsObj::getConnection()->sendMessage($destination, $message);
        return $data;
    }
}
