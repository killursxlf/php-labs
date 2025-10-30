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
function load_previous(): array {
    if (!isset($_COOKIE['previous_purchases'])) return [];
    $arr = json_decode($_COOKIE['previous_purchases'], true);
    return is_array($arr) ? $arr : [];
}
function save_previous(array $map): void {
    setcookie('previous_purchases', json_encode($map, JSON_UNESCAPED_UNICODE), time()+30*24*60*60, '/');
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Cart (session) + previous purchases (cookie)</title>
  <link rel="stylesheet" href="/common/style.css" />
</head>
<body>
  <div class="wrap">
    <div class="links">
      <a class="btn secondary" href="index.php">Catalog</a>
      <a class="btn" href="cart.php">Cart</a>
      <a class="btn secondary" href="../01-login-cookies/index.php">Page 1</a>
    </div>

    <h1>Cart (session) + previous purchases (cookie)</h1>

    <?php if (empty($_SESSION['cart'])): ?>
      <p>Cart is empty.</p>
    <?php else: ?>
      <table>
        <thead><tr><th>Item</th><th>Price</th><th>Qty</th><th>Sum</th><th></th></tr></thead>
        <tbody>
        <?php $total = 0.0; foreach ($_SESSION['cart'] as $pid => $qty): $p = $products[$pid] ?? null; if (!$p) continue; $sum = $p['price']*$qty; $total += $sum; ?>
          <tr>
            <td><?= htmlspecialchars($p['name']) ?></td>
            <td>€<?= number_format($p['price'], 2) ?></td>
            <td><?= (int)$qty ?></td>
            <td>€<?= number_format($sum, 2) ?></td>
            <td>
              <form method="post" action="index.php?action=remove" class="row">
                <input type="hidden" name="action" value="remove" />
                <input type="hidden" name="id" value="<?= (int)$pid ?>" />
                <button class="btn secondary" type="submit">Remove</button>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
        </tbody>
        <tfoot>
          <tr>
            <th colspan="3" style="text-align:right;">Total:</th>
            <th>€<?= number_format($total, 2) ?></th>
            <th></th>
          </tr>
        </tfoot>
      </table>

      <div class="row" style="margin-top:1rem;">
        <form method="post" action="index.php?action=clear">
          <input type="hidden" name="action" value="clear" />
          <button class="btn secondary" type="submit">Clear cart</button>
        </form>
        <form method="post" action="cart_persist.php">
          <button class="btn" type="submit">Save to “previous purchases” (cookie)</button>
        </form>
      </div>
    <?php endif; ?>
  </div>
</body>
</html>
