<?php declare(strict_types=1);

class Accounts
{
    public static function &all(): array
    {
        if (!isset($_SESSION['accounts']) || !is_array($_SESSION['accounts'])) {
            $_SESSION['accounts'] = [];
        }
        return $_SESSION['accounts'];
    }

    public static function add(object $account): void
    {
        $accounts = &self::all();
        $accounts[] = $account;
    }

    public static function get(int $index): ?object
    {
        $accounts = &self::all();
        return $accounts[$index] ?? null;
    }

    public static function reset(): void
    {
        $_SESSION['accounts'] = [];
    }
}
?>