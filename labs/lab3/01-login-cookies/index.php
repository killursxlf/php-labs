<?php
declare(strict_types=1);
session_start();

function redirect(string $to): never {
    header("Location: " . $to);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cookie_action'])) {
    if ($_POST['cookie_action'] === 'save_cookie_name') {
        $name = trim($_POST['name'] ?? '');
        if ($name !== '') {
            setcookie('username', $name, time() + 7 * 24 * 60 * 60, '/');
        }
        redirect('index.php');
    }
    if ($_POST['cookie_action'] === 'delete_cookie_name') {
        if (isset($_COOKIE['username'])) {
            setcookie('username', '', time() - 3600, '/');
        }
        redirect('index.php');
    }
}

$loginError = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['session_action'] ?? null) === 'login') {
    $login = trim($_POST['login'] ?? '');
    $password = trim($_POST['password'] ?? '');
    $validLogin = 'admin';
    $validPass  = '1234';
    if ($login === $validLogin && $password === $validPass) {
        $_SESSION['user'] = ['login' => $login];
        redirect('index.php');
    } else {
        $loginError = 'Invalid username or password (demo: admin / 1234)';
    }
}

if (isset($_GET['logout'])) {
    session_unset();
    session_destroy();
    redirect('index.php');
}

$cookieUsername = $_COOKIE['username'] ?? null;
$serverInfoVisible = ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['server_info']));
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Page 1 — Cookies, Session, $_SERVER</title>
  <link rel="stylesheet" href="/common/style.css" />
</head>
<body>
  <div class="wrap">
    <h1>Page 1 — Working with <code>$_COOKIE</code>, <code>$_SESSION</code> and <code>$_SERVER</code></h1>

    <div class="links">
      <a class="btn" href="../02-shop-cart/index.php">Page 2 — Using $_COOKIE and $_SESSION together</a>
    </div>

    <div class="card">
      <h2>1. Working with <code>$_COOKIE</code></h2>
      <p>The form asks for a username and stores it in a cookie for 7 days. A button deletes the cookie.</p>

      <?php if ($cookieUsername): ?>
        <p class="ok">Welcome, <strong><?= htmlspecialchars($cookieUsername) ?></strong>! (cookie exists)</p>
      <?php else: ?>
        <p class="muted">No username cookie.</p>
      <?php endif; ?>

      <form method="post" class="row">
        <input type="hidden" name="cookie_action" value="save_cookie_name" />
        <label for="name">Username</label>
        <input id="name" name="name" type="text" value="<?= htmlspecialchars($cookieUsername ?? '') ?>" required />
        <button class="btn" type="submit">Save to cookie (7 days)</button>
      </form>

      <form method="post" style="margin-top:.75rem;">
        <input type="hidden" name="cookie_action" value="delete_cookie_name" />
        <button class="btn secondary" type="submit">Delete cookie</button>
      </form>
    </div>

    <div class="card">
      <h2>2. Working with <code>$_SESSION</code></h2>
      <p>Login form. After success, data is stored in the session. If a session is active, a greeting and a “Logout” button are shown.</p>

      <?php if (isset($_SESSION['user'])): ?>
        <p class="ok">You are logged in as <strong><?= htmlspecialchars($_SESSION['user']['login']) ?></strong>.</p>
        <a class="btn secondary" href="?logout=1">Logout</a>
      <?php else: ?>
        <?php if ($loginError): ?>
          <p class="err"><?= htmlspecialchars($loginError) ?></p>
        <?php endif; ?>
        <form method="post" class="form">
          <input type="hidden" name="session_action" value="login" />
          <div>
            <label for="login">Username</label>
            <input id="login" name="login" type="text" required />
          </div>
          <div>
            <label for="password">Password</label>
            <input id="password" name="password" type="password" required />
          </div>
          <button class="btn" type="submit">Log in</button>
          <p class="muted" style="margin-top:.5rem;">exmpl: <code>admin / 1234</code></p>
        </form>
      <?php endif; ?>
    </div>

    <div class="card">
      <h2>3. Working with <code>$_SERVER</code></h2>

      <?php if ($serverInfoVisible): ?>
        <div class="grid">
          <div>Client IP address</div><div><code><?= htmlspecialchars($_SERVER['REMOTE_ADDR'] ?? 'unknown') ?></code></div>
          <div>Browser (HTTP_USER_AGENT)</div><div><code><?= htmlspecialchars($_SERVER['HTTP_USER_AGENT'] ?? 'unknown') ?></code></div>
          <div>Script name (PHP_SELF)</div><div><code><?= htmlspecialchars($_SERVER['PHP_SELF'] ?? 'unknown') ?></code></div>
          <div>Request method</div><div><code><?= htmlspecialchars($_SERVER['REQUEST_METHOD'] ?? 'unknown') ?></code></div>
          <div>File path</div><div><code><?= htmlspecialchars(__FILE__) ?></code></div>
        </div>
      <?php else: ?>
        <p class="muted">Server information is shown only after a <strong>POST</strong> request.</p>
      <?php endif; ?>

      <h3 style="margin-top:1rem;">Get server information (POST)</h3>
      <form method="post" action="index.php" class="row">
        <input type="hidden" name="server_info" value="1" />
        <button class="btn" type="submit">Show $_SERVER (POST)</button>
      </form>

      <h3 style="margin-top:1rem;">POST-only example (redirects on GET)</h3>
      <form method="post" action="post-only.php">
        <input type="hidden" name="demo" value="1" />
        <button class="btn secondary" type="submit">Go to post-only.php (POST)</button>
      </form>
    </div>
  </div>
</body>
</html>
