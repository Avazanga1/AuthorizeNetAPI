<?php

namespace Czopson\Authorize;


use Czopson\Authorize\Models\CCPaymentDetails;

class CCPayment extends ANetPayment
{
    private $paymentDetails;

    public function __construct($loginID, $transactionKey, CCPaymentDetails $details, $sandbox = false, ANetAPIFactory $apiFactory = null)
    {
        parent::__construct($loginID, $transactionKey, $sandbox, $apiFactory);

        if(null === $details) {
            throw new \Exception('Missing CC details.');
        }

        $this->paymentDetails = $details;
    }

    public function validateCC($amount) {
        if($this->authorizeTransaction($amount)) {
            $res = $this->voidTransaction($this->getLastTransactionResponse()->transaction_id);
        } else {
            $res = false;
        }

        return $this->transactionResult($res);
    }

    public function chargeCC($amount) {
        if($this->authorizeTransaction($amount)) {
            $res = $this->captureTransaction($this->getLastTransactionResponse()->transaction_id);
        } else {
            $res = false;
        }

        return $this->transactionResult($res);
    }


    private function authorizeTransaction($amount) {
        $this->apiAIM->amount = $amount;
        $this->apiAIM->setFields($this->paymentDetails->toArray());

        $response = $this->apiAIM->authorizeOnly();
        $this->setLastTransactionResponse($response);
        if($this->getLastTransactionResponse()->approved) {
            return true;
        }

        return false;
    }

    private function voidTransaction($transactionId) {
        $response = $this->apiAIM->void($transactionId);
        $this->setLastTransactionResponse($response);

        if($this->getLastTransactionResponse()->approved) {
            return true;
        }

        return false;
    }

    private function captureTransaction($transactionId) {
        $response = $this->apiAIM->priorAuthCapture($transactionId);
        $this->setLastTransactionResponse($response);

        if($this->getLastTransactionResponse()->approved) {
            return true;
        }

        return false;
    }
} 