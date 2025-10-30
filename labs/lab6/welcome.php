<?php
session_start();
if (empty($_SESSION['user_id'])) {
  header('Location: login.php');
  exit;
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Protected</title>
  <link rel="stylesheet" href="/common/style.css">
</head>
<body>
  <h1>Welcome, <?= htmlspecialchars($_SESSION['username']) ?>!</h1>
  <div class="card">
    <p>You are logged in as <strong><?= htmlspecialchars($_SESSION['email']) ?></strong>.</p>
    <div class="links">
      <a class="btn" href="logout.php">Logout</a>
      <a class="btn secondary" href="index.php">Home</a>
    </div>
  </div>
</body>
</html>
