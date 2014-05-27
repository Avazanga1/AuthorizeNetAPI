<?php

namespace Czopson\Authorize;

/**
 * Class AuthorizeNetObject is used as API connection details configurator
 * @package Czopson\Authorize
 */
class ANetObject
{
    protected $apiAIM;
    protected $apiTD;
    protected $apiCIM;

    public function __construct($loginID, $transactionKey, $sandbox = false, ANetAPIFactory $apiFactory = null)
    {
        if($sandbox && !defined('AUTHORIZENET_SANDBOX')) {
            define("AUTHORIZENET_SANDBOX", $sandbox);
        }

        if (!$apiFactory) {
            $apiFactory = new ANetAPIProductionFactory();
        }

        $this->apiAIM = $apiFactory->getAIM($loginID, $transactionKey);
        $this->apiTD = $apiFactory->getTD($loginID, $transactionKey);
        $this->apiCIM = $apiFactory->getCIM($loginID, $transactionKey);
    }
} 