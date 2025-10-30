<?php
declare(strict_types=1);

function h(string $s): string { return htmlspecialchars($s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }

$logPath = __DIR__ . DIRECTORY_SEPARATOR . 'log.txt';
$now = (new DateTimeImmutable('now'))->format('Y-m-d H:i:s');

$infoMsg = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $text = trim((string)($_POST['text'] ?? ''));
    if ($text !== '') {
        $entry = "[$now] " . str_replace(["\r\n", "\r"], "\n", $text) . "\n\n";
        file_put_contents($logPath, $entry, FILE_APPEND | LOCK_EX);
        $infoMsg = 'Entry saved.';
    } else {
        $infoMsg = 'Empty text was not saved.';
    }
}

$logContent = is_file($logPath) ? file_get_contents($logPath) : '';
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Log (log.txt)</title>
  <link rel="stylesheet" href="/common/style.css" />
</head>
<body>
  <h1>Log (log.txt)</h1>
  <?php if ($infoMsg): ?>
    <p class="card"><?= h($infoMsg) ?></p>
  <?php endif; ?>

  <div class="card">
    <h2>Add a new entry</h2>
    <form action="text.php" method="post">
      <textarea name="text" rows="5" placeholder="Your text..." required></textarea>
      <p>
        <button class="btn" type="submit">Save</button>
        <a class="btn" href="index.html">Back to Home</a>
      </p>
    </form>
  </div>

  <div class="card">
    <h2>File contents</h2>
    <?php if ($logContent === ''): ?>
      <p class="muted">The log file is empty or not created yet.</p>
    <?php else: ?>
      <pre><?= h($logContent) ?></pre>
    <?php endif; ?>
  </div>
</body>
</html>
