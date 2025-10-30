<?php
session_start();
$cacheFile = __DIR__ . '/../cache/report.html';
if (is_file($cacheFile)) @unlink($cacheFile);
unset($_SESSION['lab7_session_cache']);
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Caches cleared</title>
  <link rel="stylesheet" href="styles.php">
</head>
<body>
  <h1>Caches cleared</h1>
  <div class="links">
    <a class="btn" href="index.php">Home</a>
  </div>
</body>
</html>
