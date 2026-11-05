<?php

namespace ComBank\Transactions;

use ComBank\Support\Traits\AmountValidationTrait;

/**
 * Created by VS Code.
 * User: JPortugal
 * Date: 7/28/24
 * Time: 1:24 PM
 */


abstract class BaseTransaction
{
    use AmountValidationTrait;
    protected $amount;
    public function __construct(float $amount)
    {
        $this->amount = $amount;
    }
}
