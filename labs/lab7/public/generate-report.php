<?php
$cacheDir = realpath(__DIR__ . '/../cache') ?: (__DIR__ . '/../cache');
if (!is_dir($cacheDir)) @mkdir($cacheDir, 0777, true);
$cacheFile = $cacheDir . '/report.html';
$ttl = 600;
$now = time();

if (is_file($cacheFile) && ($now - filemtime($cacheFile) < $ttl)) {
  header('Content-Type: text/html; charset=utf-8');
  header('X-Cache: HIT');
  readfile($cacheFile);
  exit;
}

$rows = 1000;
sleep(3);
ob_start();
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Report (File Cache)</title>
  <link rel="stylesheet" href="styles.php">
</head>
<body>
  <h1>Report (File Cache)</h1>
  <div class="card"><p>Generated at: <strong><?= date('Y-m-d H:i:s') ?></strong></p></div>
  <div class="card" style="overflow:auto; max-height:60vh">
    <table>
      <thead><tr><th>#</th><th>Name</th><th>Amount</th><th>Date</th></tr></thead>
      <tbody>
      <?php for ($i=1; $i<=$rows; $i++): ?>
        <tr>
          <td><?= $i ?></td>
          <td><?= 'Item ' . $i ?></td>
          <td><?= number_format(mt_rand(100, 100000) / 100, 2) ?></td>
          <td><?= date('Y-m-d', strtotime('-' . mt_rand(0, 365) . ' days')) ?></td>
        </tr>
      <?php endfor; ?>
      </tbody>
    </table>
  </div>
  <div class="links">
    <a class="btn secondary" href="generate-report.php">Reload</a>
    <a class="btn" href="clear-cache.php">Clear caches</a>
    <a class="btn" href="index.php">Home</a>
  </div>
</body>
</html>
<?php
$html = ob_get_clean();
file_put_contents($cacheFile, $html);
header('Content-Type: text/html; charset=utf-8');
header('X-Cache: MISS');
echo $html;
