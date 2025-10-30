<?php declare(strict_types=1);

require_once __DIR__ . '/BankAccount.php';

class SavingsAccount extends BankAccount
{
    private float $interestRate = 0.05;

    public function __construct(float $initialBalance = 0.0, string $currency = 'USD', float $interestRate = 0.05)
    {
        parent::__construct($initialBalance, $currency);
        $this->setInterestRate($interestRate);
    }

    public function applyInterest(): void
    {
        $this->balance += $this->balance * $this->interestRate;
    }

    public function getInterestRate(): float
    {
        return $this->interestRate;
    }

    public function setInterestRate(float $rate): void
    {
        if ($rate < 0) {
            throw new Exception('Interest rate cannot be negative.');
        }
        $this->interestRate = $rate;
    }

    public function __wakeup(): void
    {
        if (!isset($this->interestRate)) {
            $this->interestRate = 0.05;
        }
    }
}
?>