<?php

namespace Czopson\Authorize;


use Czopson\Authorize\Responses\TransactionInvalidResponse;
use Czopson\Authorize\Responses\TransactionResponse;

class ANetPayment extends ANetObject {
    private $lastTransactionResponse;

    private $reasonMap = [
        'Card declined by issuer - Contact card issuer to determine reason.'    =>  'CC declined by card issuer.',
        'Card reported lost or stolen - Contact card issuer for resolution.'    =>  'CC reported lost or stolen.',
        'Authorization with the card issuer was successful but the transaction was declined due to an address or ZIP code mismatch with the address on file with the card issuing bank based on the settings in the Merchant Interface.'    => 'ZIP code mismatch with CC address.',
        'Processor error - Invalid Credit Card Number.  Call merchant service provider for resolution.' =>  'Invalid CC number.',
        'The credit card number is invalid.'    =>  'Invalid CC number.',
        'Processor Error - Invalid Credit Card Expiration Date' =>  'Invalid CC expiration date',
        'The credit card has expired.'  =>  'CC is expired'
    ];

    protected function transactionResult($result)
    {
        $isValidResult = (bool) $result;
        if ($isValidResult) {
            return new TransactionResponse('Transaction successful', $this->getLastTransactionId());
        }

        return new TransactionInvalidResponse($this->getLastTransactionError(), $this->getLastTransactionId());
    }

    protected function setLastTransactionResponse(\AuthorizeNetResponse $response) {
        $this->lastTransactionResponse = $response;
    }

    protected function getLastTransactionResponse() {
        if(!isset($this->lastTransactionResponse)) {
            throw new \Exception('Unable to retrieve last transaction response');
        }

        return $this->lastTransactionResponse;
    }

    protected function getLastTransactionId() {
        if(!isset($this->getLastTransactionResponse()->transaction_id)) {
            throw new \Exception('Unable to retrieve ID of last transaction');
        }

        return $this->getLastTransactionResponse()->transaction_id;
    }

    protected function getLastTransactionError() {
        $requestDetails = $this->apiTD->getTransactionDetails($this->getLastTransactionId());

        if(!empty($requestDetails->xml->transaction->responseReasonDescription))
            $reason = $requestDetails->xml->transaction->responseReasonDescription;
        else
            $reason = $this->getLastTransactionResponse()->response_reason_text;

        if(isset($this->reasonMap[$reason])) {
            return $this->reasonMap[$reason];
        }

        return $reason;
    }
} 