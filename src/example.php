<?php
/**
 * Created by PhpStorm.
 * User: Artur Czopek
 * Date: 5/19/14
 * Time: 1:00 PM
 */

require_once('../vendor/autoload.php');

////function __autoload($class) {
//    require_once($class . '.php');
//}

$payment = new \Czopson\Authorize\CCPayment('6Sq9N3nvR', '42sRN493D4n5nJ5X', true);
$ccDetails = [
    'cc_number' => '5424000000000015',
    'cc_code' => '123',
    'cc_exp_month' => '10',
    'cc_exp_year' => '2018',
    'cc_type' => 'Master',
    'cc_name_first' => 'Artur',
    'cc_name_last' => 'Czopek',
    'cc_city' => 'Krakow',
    'cc_state' => 'FL',
    'cc_addr1' => 'Ul. Krakusa 13',
    'cc_addr2' => 'Apt 666',
    'cc_zip' => '12345'
];
$payment->setTransactionDetails($ccDetails);
$charge = $payment->validateCC ('10');
var_dump($charge);