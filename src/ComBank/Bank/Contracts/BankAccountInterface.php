<?php

namespace ComBank\Bank\Contracts;

/**
 * Created by VS Code.
 * User: JPortugal
 * Date: 7/27/24
 * Time: 7:26 PM
 */

use ComBank\Exceptions\BankAccountException;
use ComBank\Exceptions\FailedTransactionException;
use ComBank\OverdraftStrategy\Contracts\OverdraftInterface;
use ComBank\Transactions\Contracts\BankTransactionInterface;

interface BankAccountInterface
{
    const STATUS_OPEN = 'OPEN';
    const STATUS_CLOSED = 'CLOSED';

    public function transaction(BankTransactionInterface $bankTransaction);
    public function reOpenAccount();
    public function closeAccount();
    public function setBalance($balance);
    public function getBalance();
    public function isOpen();
    public function setStatus($status);
    public function getOverdraft();
    public function applyOverdraft(OverdraftInterface $overdraftInterface);
}
