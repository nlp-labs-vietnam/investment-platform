<?php

namespace App\Services\SMS;


use Exception;

class VonageService
{
    protected $client;
    protected $clientVerify;

    protected $requestID;

    public function getConnection()
    {
        $basic = new \Vonage\Client\Credentials\Basic(getenv('VONAGE_API_KEY'), getenv('VONAGE_API_SECRET'));
        $this->clientVerify = new \Vonage\Client(new \Vonage\Client\Credentials\Container($basic));
        $this->client = new \Vonage\Client($basic);
        return $this;

    }

    public function sendMessage($destination, $message)
    {
        try {
            $response = $this->client->sms()->send(
                new \Vonage\SMS\Message\SMS($destination, "Vonage APIs", $message)
            );

            $message = $response->current();

            if ($message->getStatus() == 0) {
                return "The message was sent successfully";
            } else {
                return "The message failed with status: " . $message->getStatus();
            }

        } catch (Exception $exception) {
            return ['error' => $exception->getMessage(), 'code' => $exception->getCode()];
        }

    }
}

