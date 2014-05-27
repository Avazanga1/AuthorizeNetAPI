<?php

namespace Czopson\Authorize;


interface ANetAPIFactory {
    public function getAIM($loginID, $transactionKey);
    public function getCIM($loginID, $transactionKey);
    public function getTD($loginID, $transactionKey);
} 