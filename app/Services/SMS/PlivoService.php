<?php

namespace App\Services\SMS;


use Plivo\Resources\PHLO\PhloRestClient;
use Plivo\Exceptions\PlivoRestException;

class PlivoService
{
    protected $client;
    protected $phlo;

    public function getConnection()
    {
        $this->client = new PhloRestClient(getenv('PLIVO_AUTH_ID'), getenv('PLIVO_AUTH_TOKEN'));
        $this->phlo = $this->client->phlo->get(getenv('PLIVO_ID'));
        return $this;
    }

    public function sendMessage($destination, $message)
    {
        try {
            $response = $this->phlo->run(["From" => "<sender_id>", "To" => "<destination_number>"]);
            dd($response);
        } catch (PlivoRestException $exception) {
            return ['error' => $exception->getMessage(), 'code' => $exception->getCode()];
        }

    }
}

