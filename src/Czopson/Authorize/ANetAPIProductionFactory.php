<?php

namespace Czopson\Authorize;


class ANetAPIProductionFactory implements ANetAPIFactory {

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