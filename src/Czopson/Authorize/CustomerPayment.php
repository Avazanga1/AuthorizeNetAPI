<?php

namespace Czopson\Authorize;


class CustomerPayment extends ANetPayment
{
    private $customer;

    public function __construct($loginID, $transactionKey, $customerId, $sandbox = false)
    {
        parent::__construct($loginID, $transactionKey, $sandbox);

        $this->customer = new CustomerManager($loginID, $transactionKey, $sandbox);
        $this->customer->loadById($customerId);
    }

    public function validateCC($amount)
    {
        $transactionId = $this->authorize($amount);
        return $this->transactionResult($this->void($transactionId));
    }

    public function chargeCC($amount)
    {
        $transaction = $this->prepareTransaction($amount);
        $response = $this->apiCIM->createCustomerProfileTransaction("AuthCapture", $transaction);
        $this->setLastTransactionResponse($response->getTransactionResponse());

        return $this->transactionResult($response->isOk());
    }

    private function authorize($amount)
    {
        $transaction = $this->prepareTransaction($amount);
        $response = $this->apiCIM->createCustomerProfileTransaction("AuthOnly", $transaction);
        $this->setLastTransactionResponse($response->getTransactionResponse());

        return $this->getLastTransactionResponse()->transaction_id;
    }

    private function void($transactionId)
    {
        $transaction = new \AuthorizeNetTransaction;
        $transaction->transId = $transactionId;
        $response = $this->apiCIM->createCustomerProfileTransaction("Void", $transaction);
        $this->setLastTransactionResponse($response->getTransactionResponse());

        return $response->isOk();
    }

    private function prepareTransaction($amount)
    {
        $transaction = new \AuthorizeNetTransaction;
        $transaction->amount = $amount;
        $transaction->customerProfileId = $this->customer->getCustomerProfileID();
        $transaction->customerPaymentProfileId = $this->customer->getPaymentProfileID();
        $transaction->customerShippingAddressId = $this->customer->getAddressProfileID();

        return $transaction;
    }

    public function get($customerId)
    {
        return $this->apiCIM->getCustomerProfile($customerId);

    }
}