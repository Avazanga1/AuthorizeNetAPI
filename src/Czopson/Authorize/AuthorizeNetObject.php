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
    protected $apiAIM;
    protected $apiTD;

    protected function __construct($loginID, $transactionKey, $sandbox = false)
    {
        if($sandbox) {
            define("AUTHORIZENET_SANDBOX", $sandbox);
        }

        $this->apiAIM = new \AuthorizeNetAIM($loginID, $transactionKey);
        $this->apiTD = new \AuthorizeNetTD($loginID, $transactionKey);
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