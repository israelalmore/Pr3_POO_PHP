<?php

namespace ComBank\Transactions;

/**
 * Created by VS Code.
 * User: JPortugal
 * Date: 7/28/24
 * Time: 11:30 AM
 */

use ComBank\Bank\Contracts\BankAccountInterface;
use ComBank\Support\Traits\AmountValidationTrait;
use ComBank\Exceptions\FailedTransactionException;
use ComBank\Transactions\Contracts\BankTransactionInterface;

class TransferTransaction extends BaseTransaction implements BankTransactionInterface
{
    use AmountValidationTrait;

    private BankAccountInterface $destinationAccount;

    public function __construct(BankAccountInterface $destinationAccount, float $amount)
    {
        parent::__construct($amount);
        $this->destinationAccount = $destinationAccount;
    }


    public function applyTransaction(BankAccountInterface $bankAccount): float
    {
        if ($bankAccount->getBalance() < $this->amount) {
            throw new FailedTransactionException();
        }

        $account2Balance = $this->getDestinationAccount()->getBalance();
        $account2 = $this->getDestinationAccount()->setBalance($account2Balance + $this->amount);

        return $bankAccount->getBalance() - $this->amount;
    }

    public function getTransactionInfo(): string
    {
        return "TRANSFER_TRANSACTION";
    }

    public function getAmount(): float
    {
        return $this->amount;
    }

    public function setDestinationAccount(BankAccountInterface $account)
    {
        $this->destinationAccount = $account;

        return $this;
    }

    public function getDestinationAccount(): BankAccountInterface
    {
        return $this->destinationAccount;
    }
}
