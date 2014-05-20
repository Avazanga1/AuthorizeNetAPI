<?php
/**
 * Created by PhpStorm.
 * User: Artur Czopek
 * Date: 5/19/14
 * Time: 3:16 PM
 */

namespace Czopson\Authorize;


use PhpSpec\Exception\Exception;

class CCPayment extends AuthorizeNetObject
{
    private $transactionFields;
    private $lastTransactionResponse;


    public function setTransactionDetails($details) {
        $validator = new CCDetailsValidator();
        $validator->verify($details);

        $this->transactionFields = array(
             'card_num'=> $details['cc_number']
            ,'card_code'=>$details['cc_code']
            ,'exp_date'=>$details['cc_exp_month'].'/'.$details['cc_exp_year']
            ,'first_name' => $details['cc_name_first']
            ,'last_name' => $details['cc_name_last']
            ,'city' => $details['cc_city']
            ,'state' => $details['cc_state']
            ,'address' => $details['cc_addr1']
            ,'zip' => $details['cc_zip']
        );
    }


    public function validateCC($amount) {
        if(null === $this->transactionFields) {
            throw new \Exception('Missing CC details.');
        }

        if($this->authorizeTransaction($amount)) {
            $res = $this->voidTransaction($this->lastTransactionResponse->transaction_id);
        } else {
            $res = false;
        }

        return $this->result($res);
    }


    public function chargeCC($amount) {
        if(null === $this->transactionFields) {
            throw new \Exception('Missing CC details.');
        }

        if($this->authorizeTransaction($amount)) {
            $res = $this->captureTransaction($this->lastTransactionResponse->transaction_id);
        } else {
            $res = false;
        }

        return $this->result($res);
    }

    public function getLastTransactionResponse() {
        if(!isset($this->lastTransactionResponse)) {
            throw new Exception('Unable to retrieve last transaction response');
        }

        return $this->lastTransactionResponse;
    }

    protected function getLastTransationId() {
        if(!isset($this->lastTransactionResponse->transaction_id)) {
            throw new Exception('Unable to retrieve ID of last transaction');
        }

        return $this->lastTransactionResponse->transaction_id;
    }

    protected function getLastTransactionError() {
        $requestDetails = $this->apiTD->getTransactionDetails($this->getLastTransationId());

        if(!empty($requestDetails->xml->transaction->responseReasonDescription))
            $reason = $requestDetails->xml->transaction->responseReasonDescription;
        else
            $reason = $this->getLastTransactionResponse()->response_reason_text;

        if ($reason == 'Card declined by issuer - Contact card issuer to determine reason.')
            $reason = 'CC declined by card issuer.';
        if ($reason == 'Card reported lost or stolen - Contact card issuer for resolution.')
            $reason = 'CC reported lost or stolen.';
        if ($reason == 'Authorization with the card issuer was successful but the transaction was declined due to an address or ZIP code mismatch with the address on file with the card issuing bank based on the settings in the Merchant Interface.')
            $reason = 'ZIP code mismatch with CC address.';
        if ($reason == 'Processor error - Invalid Credit Card Number.  Call merchant service provider for resolution.')
            $reason = 'Invalid CC number.';
        if ($reason == 'The credit card number is invalid.')
            $reason = 'Invalid CC number.';
        if ($reason == 'Processor Error - Invalid Credit Card Expiration Date')
            $reason = 'Invalid CC expiration date';
        if ($reason == 'The credit card has expired.')
            $reason = 'CC is expired';

        return $reason;
    }


    private function authorizeTransaction($amount) {
        $this->apiAIM->amount = $amount;
        $this->apiAIM->setFields($this->transactionFields);

        $this->lastTransactionResponse = $this->apiAIM->authorizeOnly();
        if($this->lastTransactionResponse->approved) {
            return true;
        }

        return false;
    }

    private function voidTransaction($transactionId) {
        $this->lastTransactionResponse = $this->apiAIM->void($transactionId);

        if($this->lastTransactionResponse->approved) {
            return true;
        }

        return false;
    }

    private function captureTransaction($transactionId) {
        $this->lastTransactionResponse = $this->apiAIM->priorAuthCapture($transactionId);

        if($this->lastTransactionResponse->approved) {
            return true;
        }

        return false;
    }
} 