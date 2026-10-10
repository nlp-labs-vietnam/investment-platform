<?php

namespace App\Services\Gateway\cinetpay;

use Facades\App\Services\BasicCurl;
use Facades\App\Services\BasicService;
use CinetPay\CinetPay;

class Payment
{
    public static function prepareData($order, $gateway)
    {
        $send['view'] = 'user.payment.cinetpay';
        return json_encode($send);
    }

    public static function ipn($request, $gateway, $order = null, $trx = null, $type = null)
    {


         $id_transaction = $order->transaction;

        if (!empty($id_transaction)) {
//            try {
                $apiKey = $gateway->parameters->apiKey; //Veuillez entrer votre apiKey
                $site_id = $gateway->parameters->site_id; //Veuillez entrer votre siteId

                $cp = new CinetPay($site_id, $apiKey);

                dd($cp);
                // Reprise exacte des bonnes données chez CinetPay
                $cp->setTransId($id_transaction)->getPayStatus();


                $paymentData = [
                    "cpm_site_id" => $cp->_cpm_site_id,
                    "signature" => $cp->_signature,
                    "cpm_amount" => $cp->_cpm_amount,
                    "cpm_trans_id" => $cp->_cpm_trans_id,
                    "cpm_custom" => $cp->_cpm_custom,
                    "cpm_currency" => $cp->_cpm_currency,
                    "cpm_payid" => $cp->_cpm_payid,
                    "cpm_payment_date" => $cp->_cpm_payment_date,
                    "cpm_payment_time" => $cp->_cpm_payment_time,
                    "cpm_error_message" => $cp->_cpm_error_message,
                    "payment_method" => $cp->_payment_method,
                    "cpm_phone_prefixe" => $cp->_cpm_phone_prefixe,
                    "cel_phone_num" => $cp->_cel_phone_num,
                    "cpm_ipn_ack" => $cp->_cpm_ipn_ack,
                    "created_at" => $cp->_created_at,
                    "updated_at" => $cp->_updated_at,
                    "cpm_result" => $cp->_cpm_result,
                    "cpm_trans_status" => $cp->_cpm_trans_status,
                    "cpm_designation" => $cp->_cpm_designation,
                    "buyer_name" => $cp->_buyer_name,
                ];
                // Recuperation de la ligne de la transaction dans votre base de données

                // Verification de l'etat du traitement de la commande

                // Si le paiement est bon alors ne traitez plus cette transaction : die();

                // On verifie que le montant payé chez CinetPay correspond à notre montant en base de données pour cette transaction

                // On verifie que le paiement est valide

                if ($cp->isValidPayment()) {
                    BasicService::preparePaymentUpgradation($order);

                    $data['status'] = 'success';
                    $data['msg'] = 'Transaction was successful.';
                    $data['redirect'] = route('success');
                } else {
                    $data['status'] = 'error';
                    $data['msg'] = 'it seems some issue in server to server communication. Kindly connect with administrator';
                    $data['redirect'] = route('failed');
                }
//            } catch ( \Exception $e) {
//
//                $data['status'] = 'error';
//                $data['msg'] = 'it seems some issue in server to server communication. Kindly connect with administrator';
//                $data['redirect'] = route('failed');
//            }
        } else {
            // redirection vers la page d'accueil
            die();
        }
        return $data;
    }
}
