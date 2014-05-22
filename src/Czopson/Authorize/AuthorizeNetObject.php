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
    protected $apiCIM;

    public function __construct($loginID, $transactionKey, $sandbox = false, AuthorizeNetAPIFactory $apiFactory = null)
    {
        if($sandbox && !defined('AUTHORIZENET_SANDBOX')) {
            define("AUTHORIZENET_SANDBOX", $sandbox);
        }

        if (!$apiFactory) {
            $apiFactory = new AuthorizeNetAPIProductionFactory();
        }

        $this->apiAIM = $apiFactory->getAIM($loginID, $transactionKey);
        $this->apiTD = $apiFactory->getTD($loginID, $transactionKey);
        $this->apiCIM = $apiFactory->getCIM($loginID, $transactionKey);
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