<?php

namespace ComBank\Transactions;

/**
 * Created by VS Code.
 * User: JPortugal
 * Date: 7/28/24
 * Time: 1:22 PM
 */

use ComBank\Bank\Contracts\BankAccountInterface;
use ComBank\Exceptions\FailedTransactionException;
use ComBank\Exceptions\InvalidOverdraftFundsException;
use ComBank\Support\Traits\AmountValidationTrait;
use ComBank\Transactions\Contracts\BankTransactionInterface;

class InterestTransaction extends BaseTransaction implements BankTransactionInterface
{
    use AmountValidationTrait;
    public function __construct($amount = 0.0)
    {
        $this->amount = $amount;
    }

    public function applyTransaction(BankAccountInterface $bankAccount): float
    {
        $allow = $bankAccount->getOverdraft()->isGrantOverdraftFunds($bankAccount->getBalance() > 0);
        $interest = $bankAccount->getOverdraft()->getInterestRate();

        if ($allow) {
            return $bankAccount->getBalance() * (($interest / 100) + 1);
        }

        throw new FailedTransactionException();
    }

    public function getTransactionInfo(): string
    {
        return "INTEREST_TRANSACTION";
    }

    public function getAmount(): float
    {
        return $this->amount;
    }
}
