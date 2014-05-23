<?php

namespace Czopson\Authorize;

use Czopson\Authorize\Models\CCPaymentDetails;

class Customer extends AuthorizeNetObject
{
    private $lastTransactionResponse;

    private $customerProfileID;
    private $paymentProfileID;
    private $addressProfileID;

    const INDIVIDUAL_CC_PROFILE = 'individual';
    const BUSINESS_CC_PROFILE = 'business';

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
        if($this->lastTransactionResponse->isOk())
        {
            $this->setCustomerProfileID($this->lastTransactionResponse->getCustomerProfileId());
            $this->setPaymentProfileID($this->lastTransactionResponse->getPaymentProfileId());
            $this->setAddressProfileID($this->lastTransactionResponse->getCustomerAddressId());

            return $this->result(true);
        }

        return $this->result(false);
    }

    public function deleteProfile($customerId)
    {
        return $this->apiCIM->deleteCustomerProfile($customerId);
    }

    public function loadById($customerId)
    {
        $customerProfile = $this->apiCIM->getCustomerProfile($customerId);

        $this->setCustomerProfileID($customerProfile->getCustomerProfileId());
        $this->setPaymentProfileID($customerProfile->getPaymentProfileId());
        $this->setAddressProfileID($customerProfile->getCustomerAddressId());
    }

    protected function getLastTransationId()
    {
        return $this->lastTransactionResponse->getCustomerProfileId();
    }

    protected function getLastTransactionError()
    {
        return '';
    }

    /**
     * @param mixed $addressProfileID
     */
    public function setAddressProfileID($addressProfileID)
    {
        $this->addressProfileID = $addressProfileID;
    }

    /**
     * @return mixed
     */
    public function getAddressProfileID()
    {
        return $this->addressProfileID;
    }

    /**
     * @param mixed $customerProfileID
     */
    public function setCustomerProfileID($customerProfileID)
    {
        $this->customerProfileID = $customerProfileID;
    }

    /**
     * @return mixed
     */
    public function getCustomerProfileID()
    {
        return $this->customerProfileID;
    }

    /**
     * @param mixed $paymentProfileID
     */
    public function setPaymentProfileID($paymentProfileID)
    {
        $this->paymentProfileID = $paymentProfileID;
    }

    /**
     * @return mixed
     */
    public function getPaymentProfileID()
    {
        return $this->paymentProfileID;
    }


}
