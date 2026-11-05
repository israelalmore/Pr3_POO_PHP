<?php

/**
 * Created by VS Code.
 * User: JPortugal
 * Date: 7/27/24
 * Time: 7:24 PM
 */

use ComBank\Bank\BankAccount;
use ComBank\OverdraftStrategy\GoldOverdraft;
use ComBank\OverdraftStrategy\SilverOverdraft;
use ComBank\Transactions\DepositTransaction;
use ComBank\Transactions\InterestTransaction;
use ComBank\Transactions\TransferTransaction;
use ComBank\Transactions\WithdrawTransaction;
use ComBank\Exceptions\BankAccountException;
use ComBank\Exceptions\FailedTransactionException;
use ComBank\Exceptions\InvalidArgsException;
use ComBank\Exceptions\InvalidOverdraftFundsException;
use ComBank\Exceptions\ZeroAmountException;

require_once 'bootstrap.php';


//---[Bank account 1]---/
// create a new account1 with balance 400
$bankAccount1 = new BankAccount(400);

pl('--------- [Start testing bank account #1, No overdraft] --------');
try {
    // show balance account
    $bankAccount1->getBalance();

    // close account
    $bankAccount1->closeAccount();
    pl('Closing account' . $bankAccount1->isOpen());

    // reopen account
    $bankAccount1->reOpenAccount();
    pl('Opening account' . $bankAccount1->isOpen());

    // deposit +150 
    pl('Doing transaction deposit (+150) with current balance ' . $bankAccount1->getBalance());
    $bankAccount1->transaction(new DepositTransaction(150.0));

    pl('My new balance after deposit (+150) : ' . $bankAccount1->getBalance());

    // withdrawal -25
    pl('Doing transaction withdrawal (-25) with current balance ' . $bankAccount1->getBalance());
    $bankAccount1->transaction(new WithdrawTransaction(25.0));
    pl('My new balance after withdrawal (-25) : ' . $bankAccount1->getBalance());

    // withdrawal -600
    pl('Doing transaction withdrawal (-600) with current balance ' . $bankAccount1->getBalance());
    $bankAccount1->transaction(new WithdrawTransaction(600.0));
} catch (ZeroAmountException $e) {
    pl($e->getMessage());
} catch (BankAccountException $e) {
    pl($e->getMessage());
} catch (FailedTransactionException $e) {
    pl('Error transaction: ' . $e->getMessage());
} catch (InvalidOverdraftFundsException $e) {
    pl('Error transaction: ' . $e->getMessage());
}
pl('My balance after failed last transaction : ' . $bankAccount1->getBalance());



$bankAccount2 = new BankAccount(100.0);
$bankAccount2->applyOverdraft(new SilverOverdraft());
//---[Bank account 2]---/
pl('--------- [Start testing bank account #2, Silver overdraft (100.0 funds)] --------');
try {

    // show balance account
    pl($bankAccount2->getBalance());
    // deposit +100
    pl('Doing transaction deposit (+100) with current balance ' . $bankAccount2->getBalance());

    pl('My new balance after deposit (+100) : ' . $bankAccount2->getBalance());
    $bankAccount2->transaction(new DepositTransaction(100.0));

    // withdrawal -300
    pl('Doing transaction deposit (-300) with current balance ' . $bankAccount2->getBalance());
    $bankAccount2->transaction(new WithdrawTransaction(300.0));
    pl('My new balance after withdrawal (-300) : ' . $bankAccount2->getBalance());

    // withdrawal -50
    pl('Doing transaction deposit (-50) with current balance ' . $bankAccount2->getBalance());
    $bankAccount2->transaction(new WithdrawTransaction(50.0));
    pl('My new balance after withdrawal (-50) with funds : ' . $bankAccount2->getBalance());

    // withdrawal -120
    pl('Doing transaction withdrawal (-120) with current balance ' . $bankAccount2->getBalance());
    $bankAccount2->transaction(new WithdrawTransaction(120.0));
} catch (FailedTransactionException $e) {
    pl('Error transaction: ' . $e->getMessage());
} catch (InvalidOverdraftFundsException $e) {
    pl('Error transaction: ' . $e->getMessage());
}
pl('My balance after failed last transaction : ' . $bankAccount2->getBalance());

try {
    pl('Doing transaction withdrawal (-20) with current balance : ' . $bankAccount2->getBalance());
    $bankAccount2->transaction(new WithdrawTransaction((20.0)));
} catch (InvalidOverdraftFundsException $e) {
    pl('Error transaction: ' . $e->getMessage());
} catch (FailedTransactionException $e) {
    pl('Error amount: ' . $e->getMessage());
}
pl('My new balance after withdrawal (-20) with funds : ' . $bankAccount2->getBalance());

try {
} catch (BankAccountException $e) {
    pl($e->getMessage());
}

pl('--------- [Start testing bank account #3, No Overdraft] --------');

try {
    $bankAccount3 = new BankAccount(100.0, "israelalmore2gmail.com");
} catch (InvalidArgsException $e) {
    pl('Account creation rejected due to an invalid email format. ' .  $e->getMessage());
}

pl('--------- [Start testing bank account #4, ] --------');

try {
    $bankAccount4 = new BankAccount(500.0, "goldoverdraft@gmail.com");

    $bankAccount4->applyOverdraft(new GoldOverdraft());

    pl('Interest of the account: ' . $bankAccount4->getOverdraft()->getInterestRate());

    $bankAccount4->transaction(new InterestTransaction());

    pl('New balance after interest transaction: ' . $bankAccount4->getBalance());
} catch (InvalidArgsException $e) {
    pl('Account creation rejected due to an invalid email format. ' .  $e->getMessage());
}

pl('--------- [Start testing bank account #5, ] --------');

try {
    $bankAccount5 = new BankAccount(500.0, "transactionaccount@gmail.com");
    pl('Account balance 1: ' . $bankAccount5->getBalance());
    $bankAccount6 = new BankAccount(200.0, "transactionaccount2@gmail.com");
    pl('Account balance 2: ' . $bankAccount6->getBalance());

    $bankAccount5->transaction(new TransferTransaction($bankAccount6, 100));

    pl('Transaction of 100 from Account 1 to Account 2:');

    pl('New account balance 1: ' . $bankAccount5->getBalance());
    pl('New account balance 2: ' . $bankAccount6->getBalance());
} catch (InvalidArgsException $e) {
    pl('Account creation rejected due to an invalid email format. ' .  $e->getMessage());
}

pl('--------- [Start testing bank account #6, ] --------');

$bankAccount7 = new BankAccount(50.0);
pl('Account balance 1: ' . $bankAccount7->getBalance());
$bankAccount8 = new BankAccount(200.0);
pl('Account balance 2: ' . $bankAccount8->getBalance());

try {
    pl('Transaction of 100 from Account 1 to Account 2');

    $bankAccount7->transaction(new TransferTransaction($bankAccount8, 100));

    pl('New account balance 1: ' . $bankAccount7->getBalance());
    pl('New account balance 2: ' . $bankAccount8->getBalance());
} catch (FailedTransactionException $e) {
    pl('New account balance 1: ' . $bankAccount7->getBalance());
    pl('New account balance 2: ' . $bankAccount8->getBalance());
    pl('Transfer rejected when the source account has insufficient available funds. ' . $e->getMessage());
} catch (ZeroAmountException $e) {
    pl($e->getMessage());
}
