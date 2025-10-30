<?php
declare(strict_types=1);
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php");
    exit;
}
?>
<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>POST Only — Page 1</title>
  <link rel="stylesheet" href="/common/style.css" />
</head>
<body>
  <div class="card">
    <h1>POST request accepted</h1>
    <p>You reached this page via POST. If you open it via GET, you will be redirected back.</p>
    <a class="btn" href="index.php">Back</a>
  </div>
</body>
</html>
