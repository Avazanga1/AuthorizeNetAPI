<?php
/**
 * Created by PhpStorm.
 * User: Artur Czopek (artur.czopek@avalton.com)
 * Date: 5/23/14
 * Time: 12:15 PM
 */

namespace Czopson\Authorize;


class CustomerPayment extends AuthorizeNetObject
{
    private $customer;

    public function __construct($loginID, $transactionKey, $customerId, $sandbox = false)
    {
        parent::__construct($loginID, $transactionKey, $sandbox);

        $this->customer = new Customer($loginID, $transactionKey, $sandbox);
        $this->customer->loadById($customerId);
    }

    public function validateCC($amount)
    {
        $this->chargeCC($amount);
        return $this->result($this->void($amount));
    }

    public function chargeCC($amount)
    {
        $transaction = $this->prepareTransaction($amount);
        $response = $this->apiCIM->createCustomerProfileTransaction("AuthCapture", $transaction);
        $transactionResponse = $response->getTransactionResponse();

        return $this->result($transactionResponse->transaction_id);
    }

    public function void($transactionId)
    {
        $transaction = new \AuthorizeNetTransaction;
        $transaction->transId = $transactionId;
        $response = $this->apiCIM->createCustomerProfileTransaction("Void", $transaction);

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

    protected function getLastTransationId()
    {
        // TODO: Implement getLastTransationId() method.
    }

    protected function getLastTransactionError()
    {
        // TODO: Implement getLastTransactionError() method.
    }
}