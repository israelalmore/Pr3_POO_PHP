<?php

namespace ComBank\Transactions;

/**
 * Created by VS Code.
 * User: JPortugal
 * Date: 7/28/24
 * Time: 1:24 PM
 */


abstract class BaseTransaction
{
    protected $amount;

    public function getAmount(): float
    {
        return $this->amount;
    }
}
