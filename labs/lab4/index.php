<?php
declare(strict_types=1);

require_once __DIR__ . '/classes/product.php';
require_once __DIR__ . '/classes/discountedProduct.php';
require_once __DIR__ . '/classes/category.php';

$items = [
    new Product('Coffee', 4.99, 'Freshly roasted beans'),
    new Product('Tea', 3.49, 'Green tea leaves'),
    new Product('Cookies', 2.99, 'Butter cookies'),
    new DiscountedProduct('Chocolate', 5.49, 'Dark 70%', 15),
    new DiscountedProduct('Granola', 6.99, 'Honey almond', 10),
];

$beverages = new Category('Beverages');
$beverages->addProduct($items[0]);
$beverages->addProduct($items[1]);

$sweets = new Category('Sweets');
$sweets->addProduct($items[2]);
$sweets->addProduct($items[3]);

?><!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Lab 4 — OOP in PHP</title>
  <link rel="stylesheet" href="/common/style.css" />
</head>
<body>
  <div class="wrap">

    <h1>Lab 4 — OOP in PHP</h1>

    <div class="card">
      <h2>Products</h2>
      <div class="row">
        <?php foreach ($items as $p): ?>
          <div class="card">
            <pre><?= htmlspecialchars($p->getInfo()) ?></pre>
          </div>
        <?php endforeach; ?>
      </div>
    </div>

    <div class="card">
      <h2>Categories</h2>

      <div class="card">
        <h3><?= htmlspecialchars($beverages->name) ?></h3>
        <?php foreach ($beverages->getInfoList() as $info): ?>
          <pre><?= htmlspecialchars($info) ?></pre>
        <?php endforeach; ?>
      </div>

      <div class="card" style="margin-top:.75rem;">
        <h3><?= htmlspecialchars($sweets->name) ?></h3>
        <?php foreach ($sweets->getInfoList() as $info): ?>
          <pre><?= htmlspecialchars($info) ?></pre>
        <?php endforeach; ?>
      </div>
    </div>
  </div>
</body>
</html>
