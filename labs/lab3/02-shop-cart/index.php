<?php
declare(strict_types=1);
session_start();

$products = [
    1 => ['id' => 1, 'name' => 'Coffee',  'price' => 4.99],
    2 => ['id' => 2, 'name' => 'Tea',     'price' => 3.49],
    3 => ['id' => 3, 'name' => 'Cookies', 'price' => 2.99],
    4 => ['id' => 4, 'name' => 'Chocolate','price' => 5.49],
];

function redirect(string $to): never { header("Location: ".$to); exit; }
function ensure_post_or_redirect(string $to='index.php'): void { if ($_SERVER['REQUEST_METHOD'] !== 'POST') redirect($to); }

function cart_add(int $id, int $qty = 1): void {
    if (!isset($_SESSION['cart'])) $_SESSION['cart'] = [];
    $_SESSION['cart'][$id] = ($_SESSION['cart'][$id] ?? 0) + max(1, $qty);
}
function cart_remove(int $id): void { if (isset($_SESSION['cart'][$id])) unset($_SESSION['cart'][$id]); }
function cart_clear(): void { $_SESSION['cart'] = []; }

function load_previous(): array {
    if (!isset($_COOKIE['previous_purchases'])) return [];
    $arr = json_decode($_COOKIE['previous_purchases'], true);
    return is_array($arr) ? $arr : [];
}
function save_previous(array $map): void {
    setcookie('previous_purchases', json_encode($map, JSON_UNESCAPED_UNICODE), time()+30*24*60*60, '/');
}

$action = $_GET['action'] ?? $_POST['action'] ?? null;
if ($action === 'add')   { ensure_post_or_redirect('index.php'); cart_add((int)$_POST['id'], (int)($_POST['qty'] ?? 1)); redirect('index.php'); }
if ($action === 'remove'){ ensure_post_or_redirect('cart.php');  cart_remove((int)$_POST['id']); redirect('cart.php'); }
if ($action === 'clear') { ensure_post_or_redirect('cart.php');  cart_clear(); redirect('cart.php'); }
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Page 2 — Using $_COOKIE and $_SESSION together</title>
  <link rel="stylesheet" href="/common/style.css" />
</head>
<body>
  <div class="wrap">
    <div class="links">
      <a class="btn" href="index.php">Catalog</a>
      <a class="btn secondary" href="cart.php">Cart</a>
      <a class="btn secondary" href="../01-login-cookies/index.php">Page 1</a>
    </div>

    <h1>Page 2 — Using <code>$_COOKIE</code> and <code>$_SESSION</code> together</h1>
    <p class="muted">The session stores the current cart. The cookie stores previous purchases.</p>

    <div class="row">
      <?php foreach ($products as $p): ?>
        <div class="card">
          <h3><?= htmlspecialchars($p['name']) ?></h3>
          <p>Price: €<?= number_format($p['price'], 2) ?></p>
          <form method="post" action="index.php?action=add" class="row">
            <input type="hidden" name="action" value="add" />
            <input type="hidden" name="id" value="<?= (int)$p['id'] ?>" />
            <label for="qty<?= (int)$p['id'] ?>">Qty</label>
            <input id="qty<?= (int)$p['id'] ?>" name="qty" type="number" min="1" value="1" />
            <button class="btn" type="submit">Add</button>
          </form>
        </div>
      <?php endforeach; ?>
    </div>

    <div class="card" style="margin-top:1rem;">
      <h2>Previous purchases (cookie)</h2>
      <?php $prev = load_previous(); if (!$prev): ?>
        <p class="muted">Empty for now. Add items and save the current cart on the “Cart” page.</p>
      <?php else: ?>
        <table>
          <thead><tr><th>Item</th><th>Qty</th></tr></thead>
          <tbody>
            <?php foreach ($prev as $pid => $qty): ?>
              <tr>
                <td><?= htmlspecialchars($products[$pid]['name'] ?? ('#'.$pid)) ?></td>
                <td><?= (int)$qty ?></td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      <?php endif; ?>
    </div>
  </div>
</body>
</html>
