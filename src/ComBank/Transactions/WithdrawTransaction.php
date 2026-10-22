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

use function PHPUnit\Framework\throwException;

class WithdrawTransaction extends BaseTransaction implements BankTransactionInterface
{
    use AmountValidationTrait;
    public function __construct($amount = 0.0)
    {
        $this->validateAmount($amount);
        $this->amount = $amount;
    }

    public function applyTransaction(BankAccountInterface $bankAccount): float
    {
        $overdrafft = $bankAccount->getOverdraft();
        $allow = $bankAccount->getOverdraft()->isGrantOverdraftFunds($bankAccount->getBalance() - $this->amount);


        if ($allow) {
            return $bankAccount->getBalance() - $this->amount;
        }

        if ($overdrafft->getOverdraftFundsAmount() == 0) {
            throw new InvalidOverdraftFundsException();
        }

        throw new FailedTransactionException();
    }

    public function getTransactionInfo(): string
    {
        return "WITHDRAW_TRANSACTION";
    }
}
