<?php

namespace App\Services\Gateway\authorizenet;

use Facades\App\Services\BasicService;
use net\authorize\api\constants\ANetEnvironment;
use net\authorize\api\contract\v1\CreateTransactionRequest;
use net\authorize\api\contract\v1\CreditCardType;
use net\authorize\api\contract\v1\MerchantAuthenticationType;
use net\authorize\api\contract\v1\PaymentType;
use net\authorize\api\contract\v1\TransactionRequestType;
use net\authorize\api\controller\CreateTransactionController;

class Payment
{
	public static function prepareData($deposit, $gateway)
	{
		$send['view'] =  'user.payment.card';
		return json_encode($send);
	}

	public static function ipn($request, $gateway, $deposit = null, $trx = null, $type = null)
	{
		// Common setup for API credentials
		$merchantAuthentication = new MerchantAuthenticationType();
		$merchantAuthentication->setName($gateway->parameters->login_id);
		$merchantAuthentication->setTransactionKey($gateway->parameters->current_transaction_key);
		$refId = $deposit->trx_id;

		// Create the payment data for a credit card
		$creditCard = new CreditCardType();
		$creditCard->setCardNumber($request->card_number);
		$expiry = $request->expiry_year . '-' . $request->expiry_month;
		$creditCard->setExpirationDate($expiry);

		$paymentOne = new PaymentType();
		$paymentOne->setCreditCard($creditCard);

		// Create a transaction
		$transactionRequestType = new TransactionRequestType();
		$transactionRequestType->setTransactionType("authCaptureTransaction");
		$transactionRequestType->setAmount($deposit->payable_amount);
		$transactionRequestType->setPayment($paymentOne);

		$transactionRequest = new CreateTransactionRequest();
		$transactionRequest->setMerchantAuthentication($merchantAuthentication);
		$transactionRequest->setRefId($refId);
		$transactionRequest->setTransactionRequest($transactionRequestType);

		$controller = new CreateTransactionController($transactionRequest);
		$env = $gateway->environment == 'live' && $deposit->mode == 0 ? ANetEnvironment::PRODUCTION : ANetEnvironment::SANDBOX;
		$response = $controller->executeWithApiResponse($env);
		if ($response != null) {
			$tresponse = $response->getTransactionResponse();
			if (($tresponse != null) && ($tresponse->getResponseCode() == "1")) {
				BasicService::preparePaymentUpgradation($deposit);

				$data['status'] = 'success';
				$data['msg'] = 'Transaction was successful.';
				$data['redirect'] = route('success');
			} else {
				$data['status'] = 'error';
				$data['msg'] = 'Invalid response.';
				$data['redirect'] = route('failed');
			}
		} else {
			$data['status'] = 'error';
			$data['msg'] = 'Charge Credit Card Null response returned';
			$data['redirect'] = route('failed');
		}
		return $data;
	}
}
