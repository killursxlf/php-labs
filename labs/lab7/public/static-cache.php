<?php
class StaticCache {
  private static $data = null;
  public static function get() {
    if (self::$data !== null) return self::$data;
    sleep(2);
    self::$data = [
      'generated_at' => date('Y-m-d H:i:s'),
      'value' => bin2hex(random_bytes(8)),
    ];
    return self::$data;
  }
}
$start1 = microtime(true);
$d1 = StaticCache::get();
$t1 = round((microtime(true) - $start1) * 1000);
$start2 = microtime(true);
$d2 = StaticCache::get();
$t2 = round((microtime(true) - $start2) * 1000);
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Static Property Cache</title>
  <link rel="stylesheet" href="styles.php">
</head>
<body>
  <h1>Static Property Cache</h1>
  <div class="card">
    <p>First call: <strong><?= $t1 ?> ms</strong> — second call: <strong><?= $t2 ?> ms</strong></p>
    <p>Generated at: <strong><?= htmlspecialchars($d1['generated_at']) ?></strong>, value: <code><?= htmlspecialchars($d1['value']) ?></code></p>
  </div>
  <div class="links">
    <a class="btn secondary" href="static-cache.php">Reload</a>
    <a class="btn" href="index.php">Home</a>
  </div>
</body>
</html>
