<?php
/**
 * Created by PhpStorm.
 * User: Artur Czopek (artur.czopek@avalton.com)
 * Date: 5/27/14
 * Time: 3:25 PM
 */

namespace Czopson\Authorize\Responses;


class TransactionResponse {
    public $success;
    public $result;
    public $id;

    public function __construct($result, $id)
    {
        $this->success = true;
        $this->result = $result;
        $this->id = $id;
    }
} 