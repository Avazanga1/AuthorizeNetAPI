<?php
/**
 * Created by PhpStorm.
 * User: Artur Czopek (artur.czopek@avalton.com)
 * Date: 5/21/14
 * Time: 3:01 PM
 */

namespace Czopson\Authorize;


interface AuthorizeNetAPIFactory {
    public function getAIM($loginID, $transactionKey);
    public function getCIM($loginID, $transactionKey);
    public function getTD($loginID, $transactionKey);
} 