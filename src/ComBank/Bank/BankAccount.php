<?php

namespace ComBank\Bank;

/**
 * Created by VS Code.
 * User: JPortugal
 * Date: 7/27/24
 * Time: 7:25 PM
 */

use ComBank\Exceptions\BankAccountException;
use ComBank\OverdraftStrategy\NoOverdraft;
use ComBank\Bank\Contracts\BankAccountInterface;
use ComBank\OverdraftStrategy\Contracts\OverdraftInterface;
use ComBank\Transactions\Contracts\BankTransactionInterface;

class BankAccount implements BankAccountInterface
{
    private float $balance;
    private bool $status;
    private $overdraft;

    public function __construct(float $balance = 0.0, string $email = "test@gmail.com")
    {
        $this->balance = $balance;
        $this->status = true;
        $this->overdraft = new NoOverdraft();
    }

    public function transaction(BankTransactionInterface $bankTransaction)
    {
        if (!$this->isOpen()) {
            throw new BankAccountException();
        }
        $newBalance = $bankTransaction->applyTransaction($this);

        $this->setBalance($newBalance);
    }

    public function reOpenAccount()
    {
        if ($this->isOpen()) {
            throw new BankAccountException();
        }
        $this->status = true;
    }

    public function closeAccount()
    {
        $this->status = false;
    }

    /**
     * Set the value of balance
     *
     * @return  self
     */
    public function setBalance($balance)
    {
        $this->balance = $balance;

        return $this;
    }

    /**
     * Get the value of balance
     */
    public function getBalance()
    {
        return $this->balance;
    }

    /**
     * Get the value of status
     */
    public function isOpen()
    {
        return $this->status;
    }

    /**
     * Set the value of status
     *
     * @return  self
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get the value of overdraft
     */
    public function getOverdraft()
    {
        return $this->overdraft;
    }

    /**
     * Set the value of overdraft
     *
     * @return  self
     */
    public function applyOverdraft(OverdraftInterface $overdraftInterface)
    {
        $this->overdraft = $overdraftInterface;

        return $this;
    }
}
