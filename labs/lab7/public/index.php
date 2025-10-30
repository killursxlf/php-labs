<?php $base = ''; ?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Lab 07 — Caching</title>
  <link rel="stylesheet" href="styles.php">
  <script defer src="app.php"></script>
</head>
<body>
  <h1>Lab 07 — Caching (PHP)</h1>

  <div class="card">
    <p>This page loads <code>styles.php</code> (proxied CSS) and <code>app.php</code> with cache headers. Reload and check DevTools → Network to see cache hits.</p>
  </div>

  <div class="grid card">
    <a class="btn" href="generate-report.php">File cache report (10 min TTL)</a>
    <a class="btn" href="session-cache.php">Session cache demo</a>
    <a class="btn" href="static-cache.php">Static property cache</a>
    <a class="btn secondary" href="clear-cache.php">Clear caches</a>
  </div>
</body>
</html>
