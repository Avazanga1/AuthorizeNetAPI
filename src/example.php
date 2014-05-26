<?php
/**
 * Created by PhpStorm.
 * User: Artur Czopek
 * Date: 5/19/14
 * Time: 1:00 PM
 */

ini_set('display_errors', true);
error_reporting(E_ALL);

require_once('../vendor/authorizenet/authorizenet/AuthorizeNet.php');
require_once('../vendor/autoload.php');

// Prepare payment details object
$paymentDetails = new \Czopson\Authorize\Models\CCPaymentDetails('Artur', 'Czopek', '5424000000000015');
$paymentDetails->setCcCode('123');
$paymentDetails->setCcType('Master');
$paymentDetails->setCcExpirationMonth('05');
$paymentDetails->setCcExpirationYear('2016');
$paymentDetails->setCity('Krakow');
$paymentDetails->setState('FL');
$paymentDetails->setZip('12345');
$paymentDetails->setAddressLine1('Ul. Czopsa Pierwszego Krakusa 123');
$paymentDetails->setEmail('artur.czopek@avalton.com');




//##### Standard CC payment
$payment = new \Czopson\Authorize\CCPayment('6Sq9N3nvR', '42sRN493D4n5nJ5X', true);
$payment->setTransactionDetails($paymentDetails);

// charge CC for 10 dollars
$transaction = $payment->chargeCC('10');
if(true === $transaction->success) {
    echo "\nCharge success:" . $transaction->result;
} else {
    echo "\nCharge failed:" . $transaction->result;
}

// validate CC with 1 dollar
$transaction = $payment->validateCC('2');
if(true === $transaction->success) {
    echo "\nValidation success:" . $transaction->result;
} else {
    echo "\nValidation fail:" . $transaction->result;
}




//##### Customer creation and CC payment
// initialize customer creation
$customer = new \Czopson\Authorize\Customer('6Sq9N3nvR', '42sRN493D4n5nJ5X', true);

// create customer in Authorize.NET
if(true != $customer->createFullProfile($paymentDetails)) {
    echo 'Unable to create customer in Authorize.NET';
    exit;
}

$customerProfileId = $customer->getCustomerProfileID();
$customerPayment = new \Czopson\Authorize\CustomerPayment(
    '6Sq9N3nvR',
    '42sRN493D4n5nJ5X',
    $customerProfileId,
    true
);

// charge customer CC for 5 dollars
$transaction = $customerPayment->chargeCC(5);
if(true === $transaction->success) {
    echo "\nCharge success:" . $transaction->result;
} else {
    echo "\nCharge failed:" . $transaction->result;
}

// validate customer CC with 1 dollar
$transaction = $customerPayment->validateCC(1);
if(true === $transaction->success) {
    echo "\nValidation success:" . $transaction->result;
} else {
    echo "\nValidation fail:" . $transaction->result;
}

// remove customer
if(true === $customer->deleteProfile($customerProfileId)) {
    echo "\nCustomer removed successfully.";
} else {
    echo "\nUnable to remove customer in Authorize.NET";
}