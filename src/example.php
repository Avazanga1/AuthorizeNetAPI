<?php
/**
 * Created by PhpStorm.
 * User: Artur Czopek
 * Date: 5/19/14
 * Time: 1:00 PM
 */

require_once('../vendor/authorizenet/authorizenet/AuthorizeNet.php');
require_once('../vendor/autoload.php');


$paymentDetails = new \Czopson\Authorize\Models\CCPaymentDetails('Artur', 'Czopek', '5424000000000015');
$paymentDetails->setCcCode('123');
$paymentDetails->setCcType('Master');
$paymentDetails->setCcExpirationMonth('05');
$paymentDetails->setCcExpirationYear('2016');
$paymentDetails->setCity('Krakow');
$paymentDetails->setState('FL');
$paymentDetails->setZip('12345');
$paymentDetails->setAddressLine1('Ul. Krakusa Pierwszego 123');
$paymentDetails->setEmail('artur.czopek@avalton.com');

// Standard CC payment
//$payment = new \Czopson\Authorize\CCPayment('6Sq9N3nvR', '42sRN493D4n5nJ5X', true);
//$payment->setTransactionDetails($paymentDetails);
//$charge = $payment->validateCC ('10');
//var_dump($charge);

// Customer creation in Authorize.NET
$customer = new \Czopson\Authorize\Customer('6Sq9N3nvR', '42sRN493D4n5nJ5X', true);
$res = $customer->createFullProfile($paymentDetails);

$customerPayment = new \Czopson\Authorize\CustomerPayment('6Sq9N3nvR', '42sRN493D4n5nJ5X', $res->id, true);
var_dump($customerPayment->chargeCC(5));
var_dump($customerPayment->validateCC(1));

// Customer removal
//var_dump($customer->deleteProfile(26895328));