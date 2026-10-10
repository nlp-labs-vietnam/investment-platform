<?php

namespace App\Services\SMS;

use Infobip\Api\TfaApi;
use Infobip\Api\SmsApi;
use Infobip\Configuration;
use Infobip\ApiException;
use Infobip\Model\SmsAdvancedTextualRequest;
use Infobip\Model\SmsDestination;
use Infobip\Model\SmsTextualMessage;

class InfobipService
{
    protected $sendSmsApi;
    protected $tfaApi;
    protected $pinId;

    public function getConnection()
    {
        $configuration = new Configuration(
            host: getenv('INFOBIP_URL_BASE_PATH'),
            apiKey: getenv('INFOBIP_API_KEY')
        );
        $this->sendSmsApi = new SmsApi(config: $configuration);
        $this->tfaApi = new TfaApi(config: $configuration);
        return $this;
    }

    public function sendMessage($destination, $message)
    {
        $message = new SmsTextualMessage(
            destinations: [
                new SmsDestination(to: $destination)
            ],
            from: 'InfoSMS',
            text: $message
        );

        $request = new SmsAdvancedTextualRequest(messages: [$message]);

        try {
            $smsResponse = $this->sendSmsApi->sendSmsMessage($request);
            return $smsResponse;
        } catch (ApiException $apiException) {
            return ['error' => $apiException->getMessage(), 'code' => $apiException->getCode()];
        }
    }

}

