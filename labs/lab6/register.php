<?php
session_start();
require __DIR__ . '/db.php';
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = trim($_POST['username'] ?? '');
  $email = trim($_POST['email'] ?? '');
  $password = $_POST['password'] ?? '';
  if ($username === '' || mb_strlen($username) > 50) $errors[] = 'Enter a username (max 50).';
  if (!filter_var($email, FILTER_VALIDATE_EMAIL) || mb_strlen($email) > 100) $errors[] = 'Enter a valid email.';
  if (mb_strlen($password) < 6) $errors[] = 'Password must be at least 6 chars.';
  if (!$errors) {
    $hash = md5($password);
    try {
      $stmt = $pdo->prepare('INSERT INTO users (username, email, password) VALUES (?, ?, ?)');
      $stmt->execute([$username, $email, $hash]);
      header('Location: login.php?registered=1');
      exit;
    } catch (PDOException $e) {
      if ($e->getCode() === '23000') $errors[] = 'Username or email already exists.';
      else $errors[] = 'DB error.';
    }
  }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Register</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <h1>Create account</h1>
  <div class="card">
    <?php if ($errors): ?>
      <ul class="muted"><?php foreach ($errors as $e) echo '<li>'.htmlspecialchars($e).'</li>'; ?></ul>
    <?php endif; ?>
    <form method="post" class="grid" autocomplete="off">
      <label for="username">Username</label>
      <input id="username" name="username" type="text" maxlength="50" required>
      <label for="email">Email</label>
      <input id="email" name="email" type="text" maxlength="100" required>
      <label for="password">Password</label>
      <input id="password" name="password" type="password" maxlength="255" required>
      <div></div>
      <button class="btn" type="submit">Register</button>
    </form>
  </div>
  <div class="links">
    <a href="index.php">Home</a>
    <a href="login.php">Login</a>
  </div>
</body>
</html>
