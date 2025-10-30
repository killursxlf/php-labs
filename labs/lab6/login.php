<?php
session_start();
require __DIR__ . '/db.php';
$error = null;
$registered = isset($_GET['registered']);
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
  $username = trim($_POST['username'] ?? '');
  $password = $_POST['password'] ?? '';
  $stmt = $pdo->prepare('SELECT id, username, email, password FROM users WHERE username = ? LIMIT 1');
  $stmt->execute([$username]);
  $user = $stmt->fetch();
  if ($user && md5($password) === $user['password']) {
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];
    $_SESSION['email'] = $user['email'];
    header('Location: welcome.php');
    exit;
  } else {
    $error = 'Invalid username or password.';
  }
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Login</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
  <h1>Login</h1>
  <div class="card">
    <?php if ($registered): ?><p>Registration successful. Please log in.</p><?php endif; ?>
    <?php if ($error): ?><p class="muted"><?= htmlspecialchars($error) ?></p><?php endif; ?>
    <form method="post" class="grid" autocomplete="off">
      <label for="username">Username</label>
      <input id="username" name="username" type="text" required>
      <label for="password">Password</label>
      <input id="password" name="password" type="password" required>
      <div></div>
      <button class="btn" type="submit">Login</button>
    </form>
  </div>
  <div class="links">
    <a href="index.php">Home</a>
    <a href="register.php">Register</a>
  </div>
</body>
</html>
