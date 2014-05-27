<?php

namespace Czopson\Authorize;

use Czopson\Authorize\Models\CCPaymentDetails;
use PhpSpec\Exception\Exception;

class CustomerManager extends ANetObject
{
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

        $this->save($customerProfile);
    }

    private function createCustomerProfile(CCPaymentDetails $details)
    {
        $customerProfile = new \AuthorizeNetCustomer;
        $customerProfile->description = $details->getFirstName() .' '. $details->getLastName();
        $customerProfile->merchantCustomerId = time().rand(1,10);
        $customerProfile->email = $details->getEmail();

        return $customerProfile;
    }

    private function createCCPaymentProfile(\AuthorizeNetCustomer $customerProfile, CCPaymentDetails $details)
    {
        $paymentProfile = new \AuthorizeNetPaymentProfile;
        $paymentProfile->customerType = self::INDIVIDUAL_CC_PROFILE;
        $paymentProfile->payment->creditCard->cardNumber = $details->getCcNumber();
        $paymentProfile->payment->creditCard->expirationDate = $details->getCcExpirationYear() . '-' . $details->getCcExpirationMonth();
        $customerProfile->paymentProfiles[] = $paymentProfile;
    }

    private function createAddressProfile(\AuthorizeNetCustomer $customerProfile, CCPaymentDetails $details)
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
        $response = $this->apiCIM->createCustomerProfile($customerProfile);
        if($response->isOk())
        {
            $this->setCustomerProfileID($response->getCustomerProfileId());
            $this->setPaymentProfileID($response->getPaymentProfileId());
            $this->setAddressProfileID($response->getCustomerAddressId());
        } else {
            throw new Exception('Unable to create customer profile in Authorize.NET.');
        }
    }

    public function deleteProfile($customerId)
    {
        $response = $this->apiCIM->deleteCustomerProfile($customerId);

        if($response->isOk()) {
            return true;
        } else {
            throw new Exception('Unable to remove customer profile in Authorize.NET.');
        }
    }

    public function loadById($customerId)
    {
        $customerProfile = $this->apiCIM->getCustomerProfile($customerId);

        $this->setCustomerProfileID($customerProfile->getCustomerProfileId());
        $this->setPaymentProfileID($customerProfile->getPaymentProfileId());
        $this->setAddressProfileID($customerProfile->getCustomerAddressId());
    }


    /**
     * @param mixed $addressProfileID
     */
    private function setAddressProfileID($addressProfileID)
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
    private function setCustomerProfileID($customerProfileID)
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
    private function setPaymentProfileID($paymentProfileID)
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
