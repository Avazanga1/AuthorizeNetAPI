<?php
/**
 * Created by PhpStorm.
 * User: Artur Czopek
 * Date: 5/20/14
 * Time: 1:46 PM
 */

namespace Czopson\Authorize\Models;


use Czopson\Authorize\CCDetailsValidator;
use Czopson\Authorize\Exceptions\MissingCCDetailsException;

class CCPaymentDetails {
    private $validator;

    private $ccNumber = '';
    private $ccCode = '';
    private $ccType = '';
    private $ccExpirationMonth = '';
    private $ccExpirationYear = '';
    private $firstName = '';
    private $lastName = '';
    private $city = '';
    private $state = '';
    private $zip = '';
    private $addressLine1 = '';
    private $addressLine2 = '';

    public function __construct($firsName, $lastName, $ccNumber) {
        $this->validator = new CCDetailsValidator();

        if(empty($firsName)) {
            throw new MissingCCDetailsException('Missing CC first name');
        }
        if(empty($lastName)) {
            throw new MissingCCDetailsException('Missing CC last name');
        }
        if(empty($ccNumber)) {
            throw new MissingCCDetailsException('Missing CC number');
        }

        $this->validator->verifyCCFirstName($firsName);
        $this->firsName = $firsName;
        $this->validator->verifyCCLastName($lastName);
        $this->lastName = $lastName;
        $this->validator->verifyCCNumber($ccNumber);
        $this->ccNumber = $ccNumber;
    }

    /**
     * @param mixed $addressLine1
     */
    public function setAddressLine1($addressLine1)
    {
        $this->validator->verifyCCAddressLine1($addressLine1);
        $this->addressLine1 = $addressLine1;
    }

    /**
     * @return mixed
     */
    public function getAddressLine1()
    {
        return $this->addressLine1;
    }

    /**
     * @param mixed $addressLine2
     */
    public function setAddressLine2($addressLine2)
    {
        $this->validator->verifyCCAddressLine2($addressLine2);
        $this->addressLine2 = $addressLine2;
    }

    /**
     * @return mixed
     */
    public function getAddressLine2()
    {
        return $this->addressLine2;
    }

    /**
     * @param mixed $ccCode
     */
    public function setCcCode($ccCode)
    {
        $this->validator->verifyCCCode($ccCode);
        $this->ccCode = $ccCode;
    }

    /**
     * @return mixed
     */
    public function getCcCode()
    {
        return $this->ccCode;
    }

    /**
     * @param mixed $ccExpirationMonth
     */
    public function setCcExpirationMonth($ccExpirationMonth)
    {
        $this->validator->verifyCCExpMonth($ccExpirationMonth);
        $this->ccExpirationMonth = $ccExpirationMonth;
    }

    /**
     * @return mixed
     */
    public function getCcExpirationMonth()
    {
        return $this->ccExpirationMonth;
    }

    /**
     * @param mixed $ccExpirationYear
     */
    public function setCcExpirationYear($ccExpirationYear)
    {
        $this->validator->verifyCCExpYear($ccExpirationYear);
        $this->ccExpirationYear = $ccExpirationYear;
    }

    /**
     * @return mixed
     */
    public function getCcExpirationYear()
    {
        return $this->ccExpirationYear;
    }

    /**
     * @param mixed $ccNumber
     */
    public function setCcNumber($ccNumber)
    {
        $this->validator->verifyCCNumber($ccNumber);
        $this->ccNumber = str_replace(array('-',' '), "", $ccNumber);;
    }

    /**
     * @return mixed
     */
    public function getCcNumber()
    {
        return $this->ccNumber;
    }

    /**
     * @param mixed $ccType
     */
    public function setCcType($ccType)
    {
        $this->validator->verifyCCType($ccType);
        $this->ccType = $ccType;
    }

    /**
     * @return mixed
     */
    public function getCcType()
    {
        return $this->ccType;
    }

    /**
     * @param mixed $city
     */
    public function setCity($city)
    {
        $this->validator->verifyCCCity($city);
        $this->city = $city;
    }

    /**
     * @return mixed
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * @param mixed $firstName
     */
    public function setFirstName($firstName)
    {
        $this->validator->verifyCCFirstName($firstName);
        $this->firstName = $firstName;
    }

    /**
     * @return mixed
     */
    public function getFirstName()
    {
        return $this->firstName;
    }

    /**
     * @param mixed $lastName
     */
    public function setLastName($lastName)
    {
        $this->validator->verifyCCLastName($lastName);
        $this->lastName = $lastName;
    }

    /**
     * @return mixed
     */
    public function getLastName()
    {
        return $this->lastName;
    }

    /**
     * @param mixed $state
     */
    public function setState($state)
    {
        $this->validator->verifyCCState($state);
        $this->state = $state;
    }

    /**
     * @return mixed
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * @param mixed $zip
     */
    public function setZip($zip)
    {
        $this->validator->verifyCCZip($zip);
        $this->zip = $zip;
    }

    /**
     * @return mixed
     */
    public function getZip()
    {
        return $this->zip;
    }

} 