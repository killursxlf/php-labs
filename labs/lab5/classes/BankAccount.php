<?php declare(strict_types=1);

require_once __DIR__ . '/AccountInterface.php';

class BankAccount implements AccountInterface
{
    public const MIN_BALANCE = 0.0;

    protected float $balance;
    protected string $currency;

    public function __construct(float $initialBalance = 0.0, string $currency = 'USD')
    {
        if ($initialBalance < self::MIN_BALANCE) {
            throw new Exception('Initial balance cannot be less than '.self::MIN_BALANCE);
        }
        $this->balance = $initialBalance;
        $this->currency = $currency;
    }

    public function deposit(float $amount): void
    {
        if ($amount <= 0) {
            throw new Exception('Deposit amount must be positive.');
        }
        $this->balance += $amount;
    }

    public function withdraw(float $amount): void
    {
        if ($amount <= 0) {
            throw new Exception('Withdrawal amount must be positive.');
        }
        if ($amount > $this->balance - self::MIN_BALANCE) {
            throw new Exception('Insufficient funds.');
        }
        $this->balance -= $amount;
    }

    public function getBalance(): float
    {
        return $this->balance;
    }

    public function getCurrency(): string
    {
        return $this->currency;
    }
}
?>