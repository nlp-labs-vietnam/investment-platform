<?php

namespace App\Services\SMS;


use Twilio\Exceptions\TwilioException;
use Twilio\Rest\Client;

class TwilioService
{
    protected $client;

    public function getConnection()
    {
        $this->client = new Client(getenv("TWILIO_ACCOUNT_SID"), getenv("TWILIO_AUTH_TOKEN"));
        return $this;
    }

    public function sendMessage($destination, $message)
    {
        try {
            $sid = env("TWILIO_ACCOUNT_SID");
            $token = env("TWILIO_AUTH_TOKEN");
            $twilio = new Client($sid, $token);

            $message = $twilio->messages
                ->create($destination, [
                    "body" => $message,
                    "from" => env("TWILIO_PHONE_NUMBER"),
                ]);
            return ['sid' => $message->sid];
        } catch (TwilioException $exception) {
            return ['error' => $exception->getMessage(), 'code' => $exception->getCode()];
        }
    }
}
