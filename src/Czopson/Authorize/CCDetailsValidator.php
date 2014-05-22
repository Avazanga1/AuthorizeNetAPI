<?php
/**
 * Created by PhpStorm.
 * User: Artur Czopek
 * Date: 5/19/14
 * Time: 4:09 PM
 */

namespace Czopson\Authorize;


class CCDetailsValidator {
    public function verifyAll($ccDetails)
    {
        // Verify CC number
        if(!isset($ccDetails['cc_number'])) {
            throw new Exceptions\MissingCCDetailsException('Missing CC number');
        }
        $this->verifyCCNumber($ccDetails['cc_number']);


        // Verify CC security code
        if(!isset($ccDetails['cc_code'])) {
            throw new Exceptions\MissingCCDetailsException('Missing CC code');
        }
        $this->verifyCCCode($ccDetails['cc_code']);


        // Verify CC expiration month
        if(!isset($ccDetails['cc_exp_month'])) {
            throw new Exceptions\MissingCCDetailsException('Missing CC expiration month');
        }
        $this->verifyCCExpMonth($ccDetails['cc_exp_month']);


        // Verify CC expiration year
        if(!isset($ccDetails['cc_exp_year'])) {
            throw new Exceptions\MissingCCDetailsException('Missing CC expiration year');
        }
        $this->verifyCCExpYear($ccDetails['cc_exp_year']);


        // Verify CC type
        if(!isset($ccDetails['cc_type'])) {
            throw new Exceptions\MissingCCDetailsException('Missing CC type');
        }
        $this->verifyCCType($ccDetails['cc_type']);


        // Verify CC fist name
        if(!isset($ccDetails['cc_name_first'])) {
            throw new Exceptions\MissingCCDetailsException('Missing CC first name');
        }
        $this->verifyCCFirstName($ccDetails['cc_name_first']);


        // Verify CC last name
        if(!isset($ccDetails['cc_name_last'])) {
            throw new Exceptions\MissingCCDetailsException('Missing CC last name');
        }
        $this->verifyCCLastName($ccDetails['cc_name_last']);


        // Verify CC city
        if(!isset($ccDetails['cc_city'])) {
            throw new Exceptions\MissingCCDetailsException('Missing CC city');
        }
        $this->verifyCCCity($ccDetails['cc_city']);


        // Verify CC state
        if(!isset($ccDetails['cc_state'])) {
            throw new Exceptions\MissingCCDetailsException('Missing CC state');
        }
        $this->verifyCCState($ccDetails['cc_state']);


        // Verify CC address line 1
        if(!isset($ccDetails['cc_addr1'])) {
            throw new Exceptions\MissingCCDetailsException('Missing CC address line 1');
        }
        $this->verifyCCAddressLine1($ccDetails['cc_addr1']);


        // Verify CC address line 2
        if(!isset($ccDetails['cc_addr2'])) {
            throw new Exceptions\MissingCCDetailsException('Missing CC address line 2');
        }
        $this->verifyCCAddressLine2($ccDetails['cc_addr2']);


        // Verify CC zip
        if(!isset($ccDetails['cc_zip'])) {
            throw new Exceptions\MissingCCDetailsException('Missing CC zip');
        }
        $this->verifyCCZip($ccDetails['cc_zip']);
    }

    public function verifyCCNumber($number)
    {
        $number = str_replace(array('-',' '), "", $number);
        if (!preg_match('/^([0-9]{15,16})|([A-Za-z0-9\+\/\=]{172})$/',$number)) {
            throw new Exceptions\IncorrectCCDetailsException('Wrong CC number');
        }
    }

    public function verifyCCCode($code)
    {
        if (!preg_match('/^([0-9]{3,4})||([A-Za-z0-9\+\/\=]{172})$/', $code)) {
            throw new Exceptions\IncorrectCCDetailsException('Wrong CC code');
        }
    }

    public function verifyCCExpMonth($exp_month)
    {
        if (!preg_match('/^[0-9]{2}$/', $exp_month)) {
            throw new Exceptions\IncorrectCCDetailsException('Wrong CC expiration month');
        }
    }

    public function verifyCCExpYear($exp_year)
    {
        if (!preg_match('/^[0-9]{4}$/', $exp_year)) {
            throw new Exceptions\IncorrectCCDetailsException('Wrong CC expiration year');
        }
    }

    public function verifyCCType($type)
    {
        if (!preg_match('/^(Master|Visa|Amex|Discover)$/', $type)) {
            throw new Exceptions\IncorrectCCDetailsException('Wrong CC type');
        }
    }

    public function verifyCCFirstName($firstName)
    {
        if (!preg_match('/^[a-zA-Záéíóúüñ¿¡0-9\-\'\.\,\ ]+$/', $firstName)) {
            throw new Exceptions\IncorrectCCDetailsException('Wrong first name');
        }
    }

    public function verifyCCLastName($lastName)
    {
        if (!preg_match('/^[a-zA-Záéíóúüñ¿¡0-9\-\'\.\,\ ]+$/', $lastName)) {
            throw new Exceptions\IncorrectCCDetailsException('Wrong last name');
        }
    }

    public function verifyCCCity($city)
    {
        if (!preg_match('/^[a-zA-ZÁÉÎÍÓÚÜÑáéíóúüñ¿¡\-\.\ ]+$/', $city)) {
            throw new Exceptions\IncorrectCCDetailsException('Wrong city');
        }
    }

    public function verifyCCState($state)
    {
        if (!preg_match('/^[A-Z]{2}$/', $state)) {
            throw new Exceptions\IncorrectCCDetailsException('Wrong state');
        }
    }

    public function verifyCCAddressLine1($addressLine1)
    {
        if (!preg_match('/^[a-zA-ZÁÉÎÍÓÚÜÑáéíóúüñ¿¡0-9\-#\ \.\,\-]+$/', $addressLine1)) {
            throw new Exceptions\IncorrectCCDetailsException('Wrong address line 1');
        }
    }

    public function verifyCCAddressLine2($addressLine2)
    {
        if (!preg_match('/^[a-zA-ZÁÉÎÍÓÚÜÑáéíóúüñ¿¡0-9\-#\ \.\,\-]+$/', $addressLine2)) {
            throw new Exceptions\IncorrectCCDetailsException('Wrong address line 2');
        }
    }

    public function verifyCCZip($zip)
    {
        if (!preg_match('/^[0-9]{5}$/', $zip)) {
            throw new Exceptions\IncorrectCCDetailsException('Wrong zip');
        }
    }

    public function verifyCCCompanyName($companyName)
    {
        if (!preg_match('/^[a-zA-Záéíóúüñ¿¡0-9\-\'\.\,\ ]+$/', $companyName)) {
            throw new Exceptions\IncorrectCCDetailsException('Wrong company name');
        }
    }

    public function verifyEmail($email)
    {
        if (!preg_match('/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+(\.[a-z0-9-]+)*(\.[a-z]{2,3})$/', $email)) {
            throw new Exceptions\IncorrectCCDetailsException('Wrong email address');
        }
    }
} 