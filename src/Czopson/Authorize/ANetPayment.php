<?php

namespace Czopson\Authorize;


class ANetPayment extends ANetObject {
    private $lastTransactionResponse;

    protected function transactionResult($result)
    {
        if(true === $result) {
            return (object) [
                'success' => true,
                'result' => 'Transaction successful',
                'id' => $this->getLastTransactionId(),
            ];
        } else {
            return (object) [
                'success' => false,
                'result' => $this->getLastTransactionError(),
                'id' => $this->getLastTransactionId(),
            ];
        }
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
} 