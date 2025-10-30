<?php
declare(strict_types=1);

$uploadDir = __DIR__ . DIRECTORY_SEPARATOR . 'uploads';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0775, true);
}

function h(string $s): string { return htmlspecialchars($s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo '<p>Method not allowed. Use <strong>POST</strong>.</p>';
    exit;
}

if (!isset($_FILES['thefile'])) {
    http_response_code(400);
    echo '<p>No file received.</p>';
    exit;
}

$f = $_FILES['thefile'];

if (!is_uploaded_file($f['tmp_name'])) {
    http_response_code(400);
    echo '<p>The file was not uploaded via HTTP.</p>';
    exit;
}

$maxBytes = 2 * 1024 * 1024;
if ($f['size'] > $maxBytes) {
    http_response_code(413);
    echo '<p>Maximum size exceeded (2 MB).</p>';
    exit;
}

$allowed = ['png', 'jpg', 'jpeg'];
$pathinfo = pathinfo($f['name']);
$ext = strtolower($pathinfo['extension'] ?? '');

if (!in_array($ext, $allowed, true)) {
    http_response_code(415);
    echo '<p>Only PNG, JPG or JPEG files are allowed.</p>';
    exit;
}

$base = preg_replace('/[^A-Za-z0-9_\\-]+/', '_', $pathinfo['filename'] ?? 'file');

function rand_digits(int $len = 8): string {
    $s = '';
    for ($i = 0; $i < $len; $i++) {
        $s .= (string) random_int(0, 9);
    }
    return $s;
}

$suffix = rand_digits(8);
$newName = sprintf('%s-%s.%s', $base, $suffix, $ext);
$dest = $uploadDir . DIRECTORY_SEPARATOR . $newName;

if (!move_uploaded_file($f['tmp_name'], $dest)) {
    http_response_code(500);
    echo '<p>Failed to save the file.</p>';
    exit;
}

function detect_mime(string $path, string $ext): string {
    if (function_exists('finfo_open')) {
        $fi = @finfo_open(FILEINFO_MIME_TYPE);
        if ($fi) {
            $m = @finfo_file($fi, $path);
            @finfo_close($fi);
            if (is_string($m) && $m !== '') return $m;
        }
    }
    if (function_exists('mime_content_type')) {
        $m = @mime_content_type($path);
        if (is_string($m) && $m !== '') return $m;
    }
    $map = ['png' => 'image/png', 'jpg' => 'image/jpeg', 'jpeg' => 'image/jpeg'];
    return $map[$ext] ?? 'application/octet-stream';
}

$mime = detect_mime($dest, $ext);
$sizeKB = number_format(filesize($dest) / 1024, 2, '.', ',');

$downloadHref = 'uploads/' . rawurlencode($newName);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>Upload Result</title>
  <link rel="stylesheet" href="/common/style.css" />
</head>
<body>
  <h1>File uploaded ✔</h1>

  <div class="card">
    <div class="grid">
      <div>Original name:</div><div><strong><?= h($f['name']) ?></strong></div>
      <div>New name:</div><div><code><?= h($newName) ?></code></div>
      <div>MIME type:</div><div><?= h($mime) ?></div>
      <div>Size:</div><div><?= h($sizeKB) ?> KB</div>
    </div>
  </div>

  <p>
    <a class="btn" href="<?= h($downloadHref) ?>" download>Download file</a>
    <a class="btn" href="list.php">All files</a>
    <a class="btn" href="index.html">Back</a>
  </p>

  <p class="muted">Note: file names always get a <code>-XXXXXXXX</code> suffix (8 digits), regardless of existing files.</p>
</body>
</html>
