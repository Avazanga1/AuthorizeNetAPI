<?php

namespace Czopson\Authorize;

use Czopson\Authorize\Models\CCPaymentDetails;

class Customer extends AuthorizeNetObject
{
    private $id;
    private $lastTransactionResponse;

    const INDIVIDUAL_CC_PROFILE = 'individual';

    public function createCustomer($name, $email)
    {
        $customerProfile = new \AuthorizeNetCustomer;
        $customerProfile->description = $name;
        $customerProfile->merchantCustomerId = time().rand(1,10);
        $customerProfile->email = $email;

        $this->lastTransactionResponse = $this->apiCIM->createCustomerProfile($customerProfile);
        if($this->lastTransactionResponse->isOk()) {
            $this->id = $this->lastTransactionResponse->getCustomerProfileId();
            return $this->result(true);
        }

        return $this->result(false);
    }

    public function createCCPaymentProfile(\AuthorizeNetCustomer $customerProfile, CCPaymentDetails $details)
    {
        $paymentProfile = new \AuthorizeNetPaymentProfile;
        $paymentProfile->customerType = self::INDIVIDUAL_CC_PROFILE;
        $paymentProfile->payment->creditCard->cardNumber = $details->getCcNumber();
        $paymentProfile->payment->creditCard->expirationDate = $details->getCcExpirationYear() . '-' . $details->getCcExpirationMonth();
        $customerProfile->paymentProfiles[] = $paymentProfile;
    }

    public function createAddressProfile(\AuthorizeNetCustomer $customerProfile, CCPaymentDetails $details)
    {
        $address = new AuthorizeNetAddress;
        $address->firstName = $details->getFirstName();
        $address->lastName = $details->getLastName();
        $address->company = $details->getCompanyName();
        $address->address = $details->getAddressLine1() .' '. $details->getAddressLine2();
        $address->city = "Boston";
        $address->state = "MA";
        $address->zip = "02412";
        $address->country = "USA";
        $address->phoneNumber = "555-555-5555";
        $address->faxNumber = "555-555-5556";
        $customerProfile->shipToList[] = $address;
    }

    public function getId()
    {
        return $this->id;
    }


    protected function getLastTransationId()
    {
        return $this->getId();
    }

    protected function getLastTransactionError()
    {
        return '';
    }

    public function createPaymentProfile($type, $cardNumber, $expirationDate)
    {

    }
}
