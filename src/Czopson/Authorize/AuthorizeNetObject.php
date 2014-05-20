<?php
/**
 * Created by PhpStorm.
 * User: Artur Czopek
 * Date: 5/19/14
 * Time: 11:10 AM
 */

namespace Czopson\Authorize;

/**
 * Class AuthorizeNetObject is used as API connection details configurator
 * @package Czopson\Authorize
 */
abstract class AuthorizeNetObject
{
    protected $api;

    protected function __construct($loginID, $transactionKey, $sandbox = false)
    {
        require_once('\Czopson\Authorize\anet_php_sdk\AuthorizeNet.php');

        define("AUTHORIZENET_API_LOGIN_ID", $loginID);
        define("AUTHORIZENET_TRANSACTION_KEY", $transactionKey);
        define("AUTHORIZENET_SANDBOX", $sandbox);
    }

    protected function useApi($apiName)
    {
        $this->api = new $apiName();
    }

    protected function result($result)
    {
        if(true === $result) {
            return (object) [
                'success' => true,
                'comment' => 'Transaction successful',
                'id' => $this->getLastTransationId(),
            ];
        } else {
            return (object) [
                'success' => false,
                'comment' => $this->getLastTransactionError(),
                'id' => $this->getLastTransationId(),
            ];
        }
    }

    abstract protected function getLastTransationId();

    abstract protected function getLastTransactionError();
} 