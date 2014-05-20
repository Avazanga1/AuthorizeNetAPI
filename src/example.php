<?php
/**
 * Created by PhpStorm.
 * User: Artur Czopek
 * Date: 5/19/14
 * Time: 1:00 PM
 */

require_once('../vendor/authorizenet/authorizenet/AuthorizeNet.php');
require_once('../vendor/autoload.php');


$payment = new \Czopson\Authorize\CCPayment('6Sq9N3nvR', '42sRN493D4n5nJ5X', true);

$paymentDetails = new \Czopson\Authorize\Models\CCPaymentDetails('Artur', 'Czopek', '5424000000000015');
$paymentDetails->setCcCode('123');
$paymentDetails->setCcType('Master');
$paymentDetails->setCcExpirationMonth('05');
$paymentDetails->setCcExpirationYear('2016');
$paymentDetails->setCity('Krakow');
$paymentDetails->setState('FL');
$paymentDetails->setZip('12345');
$paymentDetails->setAddressLine1('Ul. Krakusa Pierwszego 123');

$payment->setTransactionDetails($paymentDetails);
$charge = $payment->validateCC ('10');
var_dump($charge);