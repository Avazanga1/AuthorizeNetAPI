<?php
/**
 * Created by PhpStorm.
 * User: Artur Czopek (artur.czopek@avalton.com)
 * Date: 5/21/14
 * Time: 3:04 PM
 */

namespace Czopson\Authorize;


class AuthorizeNetAPIProductionFactory implements AuthorizeNetAPIFactory {

    public function getAIM($loginID, $transactionKey)
    {
        return new \AuthorizeNetAIM($loginID, $transactionKey);
    }

    public function getCIM($loginID, $transactionKey)
    {
        return new \AuthorizeNetCIM($loginID, $transactionKey);
    }

    public function getTD($loginID, $transactionKey)
    {
        return new \AuthorizeNetTD($loginID, $transactionKey);
    }
}