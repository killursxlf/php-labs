<?php session_start(); ?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Auth Demo</title>
  <link rel="stylesheet" href="/common/style.css">
</head>
<body>
  <h1>Auth Demo</h1>
  <div class="card">
    <div class="links">
      <?php if (empty($_SESSION['user_id'])): ?>
        <a class="btn" href="register.php">Register</a>
        <a class="btn secondary" href="login.php">Login</a>
      <?php else: ?>
        <a class="btn" href="welcome.php">Protected page</a>
        <a class="btn secondary" href="logout.php">Logout</a>
      <?php endif; ?>
    </div>
  </div>
</body>
</html>
