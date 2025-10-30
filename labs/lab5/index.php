<?php declare(strict_types=1);

require_once __DIR__ . '/classes/AccountInterface.php';
require_once __DIR__ . '/classes/BankAccount.php';
require_once __DIR__ . '/classes/SavingsAccount.php';
require_once __DIR__ . '/classes/Accounts.php';

session_start();

$messages = [];
$errors = [];

function h(string $s): string { return htmlspecialchars($s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }

$action = $_POST['action'] ?? '';

try {
    switch ($action) {
        case 'create':
            $type = $_POST['type'] ?? 'bank';
            $currency = trim((string)($_POST['currency'] ?? 'USD'));
            $initial = (float)($_POST['initial'] ?? 0);
            if ($type === 'savings') {
                $rate = isset($_POST['rate']) ? (float)$_POST['rate'] : 0.05;
                $acc = new SavingsAccount($initial, $currency, $rate);
                Accounts::add($acc);
                $messages[] = "Created savings account with balance {$acc->getBalance()} {$acc->getCurrency()} (rate ".($acc->getInterestRate()*100)."%).";
            } else {
                $acc = new BankAccount($initial, $currency);
                Accounts::add($acc);
                $messages[] = "Created bank account with balance {$acc->getBalance()} {$acc->getCurrency()}.";
            }
            break;

        case 'deposit':
            $idx = (int)($_POST['account'] ?? -1);
            $amount = (float)($_POST['amount'] ?? 0);
            $acc = Accounts::get($idx);
            if (!$acc) { throw new Exception('Selected account not found.'); }
            $acc->deposit($amount);
            $messages[] = "Deposited {$amount} to account #$idx. New balance: {$acc->getBalance()} {$acc->getCurrency()}.";
            break;

        case 'withdraw':
            $idx = (int)($_POST['account'] ?? -1);
            $amount = (float)($_POST['amount'] ?? 0);
            $acc = Accounts::get($idx);
            if (!$acc) { throw new Exception('Selected account not found.'); }
            $acc->withdraw($amount);
            $messages[] = "Withdrew {$amount} from account #$idx. New balance: {$acc->getBalance()} {$acc->getCurrency()}.";
            break;

        case 'apply_interest':
            $applied = 0;
            foreach (Accounts::all() as $acc) {
                if ($acc instanceof SavingsAccount) {
                    $acc->applyInterest();
                    $applied++;
                }
            }
            $messages[] = "Applied interest to {$applied} savings account(s) using their per-account rates.";
            break;

        case 'reset':
            Accounts::reset();
            $messages[] = 'Accounts list cleared.';
            break;
    }
} catch (Exception $e) {
    $errors[] = $e->getMessage();
}

$accounts = Accounts::all();
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Lab 5 — OOP in PHP: Bank Accounts</title>
  <link rel="stylesheet" href="/common/style.css" />
  <link rel="icon" href="data:,">
</head>
<body>
  <h1>Lab 5 — OOP in PHP</h1>
  <p class="muted">Exceptions, interfaces, constants, static properties, inheritance, and polymorphism on bank accounts.</p>

  <?php if ($messages): ?>
    <div class="card">
      <strong>Success:</strong>
      <ul>
        <?php foreach ($messages as $m): ?><li><?= h($m) ?></li><?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>

  <?php if ($errors): ?>
    <div class="card">
      <strong>Error:</strong>
      <ul>
        <?php foreach ($errors as $m): ?><li><?= h($m) ?></li><?php endforeach; ?>
      </ul>
    </div>
  <?php endif; ?>

  <div class="row">
    <div class="card">
      <h2>1) Create account</h2>
      <form method="post">
        <input type="hidden" name="action" value="create"/>
        <label>Type</label>
        <select name="type">
          <option value="bank">BankAccount</option>
          <option value="savings">SavingsAccount</option>
        </select>

        <label>Currency</label>
        <input type="text" name="currency" value="USD" />

        <label>Initial balance</label>
        <input type="text" name="initial" value="0" />

        <div class="grid">
          <div><label>Savings rate (decimal)</label></div>
          <div><input type="text" name="rate" value="0.05" /></div>
        </div>

        <p><button class="btn">Create</button></p>
      </form>
    </div>

    <div class="card">
      <h2>2) Deposit / Withdraw</h2>
      <form method="post" class="row" style="align-items:flex-end">
        <div>
          <label>Account</label>
          <select name="account">
            <?php foreach ($accounts as $i => $acc): ?>
              <option value="<?= $i ?>">
                #<?= $i ?> — <?= h((new \ReflectionClass($acc))->getShortName()) ?> — Balance: <?= $acc->getBalance() ?> <?= h($acc->getCurrency()) ?>
              </option>
            <?php endforeach; ?>
          </select>
        </div>
        <div>
          <label>Amount</label>
          <input type="text" name="amount" value="10" />
        </div>
        <div>
          <label>&nbsp;</label>
          <button class="btn" name="action" value="deposit">Deposit</button>
          <button class="btn secondary" name="action" value="withdraw">Withdraw</button>
        </div>
      </form>
    </div>
  </div>

  <div class="row">
    <div class="card">
      <h2>3) Apply interest</h2>
      <form method="post">
        <input type="hidden" name="action" value="apply_interest"/>
        <p><button class="btn">Apply using per-account rates</button></p>
      </form>
    </div>

    <div class="card">
      <h2>4) Reset accounts</h2>
      <form method="post" onsubmit="return confirm('Clear accounts list?')">
        <input type="hidden" name="action" value="reset"/>
        <button class="btn secondary">Reset</button>
      </form>
    </div>
  </div>

  <div class="card">
    <h2>Accounts</h2>
    <table>
      <thead>
        <tr>
          <th>#</th><th>Type</th><th>Balance</th><th>Currency</th><th>Rate</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($accounts as $i => $acc): ?>
          <tr>
            <td><?= $i ?></td>
            <td><?= h((new \ReflectionClass($acc))->getShortName()) ?></td>
            <td><?= $acc->getBalance() ?></td>
            <td><?= h($acc->getCurrency()) ?></td>
            <td><?= $acc instanceof SavingsAccount ? ($acc->getInterestRate()*100).'%' : '—' ?></td>
          </tr>
        <?php endforeach; ?>
        <?php if (empty($accounts)): ?>
          <tr><td colspan="5" class="muted">No accounts yet.</td></tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</body>
</html>
