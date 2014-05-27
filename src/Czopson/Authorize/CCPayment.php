<?php

namespace Czopson\Authorize;


use Czopson\Authorize\Models\CCPaymentDetails;

class CCPayment extends ANetPayment
{
    public function __construct($loginID, $transactionKey, CCPaymentDetails $details, $sandbox = false, ANetAPIFactory $apiFactory = null)
    {
        parent::__construct($loginID, $transactionKey, $sandbox, $apiFactory);

        if(null === $details) {
            throw new \Exception('Missing CC details.');
        }

        $this->transactionFields = array(
            'card_num'=> $details->getCcNumber()
            ,'card_code'=>$details->getCcCode()
            ,'exp_date'=>$details->getCcExpirationMonth().'/'.$details->getCcExpirationYear()
            ,'first_name' => $details->getFirstName()
            ,'last_name' => $details->getLastName()
            ,'city' => $details->getCity()
            ,'state' => $details->getState()
            ,'address' => $details->getAddressLine1().' '.$details->getAddressLine2()
            ,'zip' => $details->getZip()
        );
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
        $this->apiAIM->setFields($this->transactionFields);

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