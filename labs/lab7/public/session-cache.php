<?php
session_start();

if (isset($_GET['clear'])) {
  unset($_SESSION['lab7_session_cache']);
}

$start = microtime(true);
$data  = $_SESSION['lab7_session_cache'] ?? null;
$hit   = $data !== null;

if (!$hit) {
  sleep(2);
  $data = [
    'generated_at' => date('Y-m-d H:i:s'),
    'items' => array_map(fn($i)=>[
      'name' => 'Product ' . $i,
      'price' => number_format(mt_rand(100, 10000)/100, 2)
    ], range(1, 10)),
  ];
  $_SESSION['lab7_session_cache'] = $data;
}

$elapsed = round((microtime(true) - $start) * 1000);
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Session Cache</title>
  <link rel="stylesheet" href="styles.php">
</head>
<body>
  <h1>Session Cache</h1>
  <div class="card">
    <p>Status: <strong><?= $hit ? 'HIT' : 'MISS' ?></strong> — elapsed: <strong><?= $elapsed ?> ms</strong></p>
    <p>Generated at: <strong><?= htmlspecialchars($data['generated_at']) ?></strong></p>
  </div>
  <div class="card">
    <table>
      <thead><tr><th>#</th><th>Name</th><th>Price</th></tr></thead>
      <tbody>
      <?php foreach ($data['items'] as $i => $row): ?>
        <tr><td><?= $i+1 ?></td><td><?= htmlspecialchars($row['name']) ?></td><td><?= $row['price'] ?></td></tr>
      <?php endforeach; ?>
      </tbody>
    </table>
  </div>
  <div class="links">
    <a class="btn secondary" href="session-cache.php">Reload</a>
    <a class="btn" href="session-cache.php?clear=1">Clear session cache</a>
    <a class="btn" href="index.php">Home</a>
  </div>
</body>
</html>
