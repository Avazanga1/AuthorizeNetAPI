<?php

namespace Czopson\Authorize;

use Czopson\Authorize\Models\CCPaymentDetails;

class Customer extends AuthorizeNetObject
{
    private $id;
    private $lastTransactionResponse;

    const INDIVIDUAL_CC_PROFILE = 'individual';

    public function createFullProfile(CCPaymentDetails $details)
    {
        $customerProfile = $this->createCustomerProfile($details);
        $this->createCCPaymentProfile($customerProfile, $details);
        $this->createAddressProfile($customerProfile, $details);

        return $this->save($customerProfile);
    }

    public function createCustomerProfile(CCPaymentDetails $details)
    {
        $customerProfile = new \AuthorizeNetCustomer;
        $customerProfile->description = $details->getFirstName() .' '. $details->getLastName();
        $customerProfile->merchantCustomerId = time().rand(1,10);
        $customerProfile->email = $details->getEmail();

        return $customerProfile;
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
        $address = new \AuthorizeNetAddress;
        $address->firstName = $details->getFirstName();
        $address->lastName = $details->getLastName();
        $address->company = $details->getCompanyName();
        $address->address = $details->getAddressLine1() .' '. $details->getAddressLine2();
        $address->city = $details->getCity();
        $address->state = $details->getState();
        $address->zip = $details->getZip();
        $customerProfile->shipToList[] = $address;
    }

    public function save(\AuthorizeNetCustomer $customerProfile)
    {
        $this->lastTransactionResponse = $this->apiCIM->createCustomerProfile($customerProfile);
        if($this->lastTransactionResponse->isOk()) {
            $this->id = $this->lastTransactionResponse->getCustomerProfileId();
            return $this->result(true);
        }

        return $this->result(false);
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
