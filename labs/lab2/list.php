<?php
declare(strict_types=1);

function h(string $s): string { return htmlspecialchars($s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8'); }

$dir = __DIR__ . DIRECTORY_SEPARATOR . 'uploads';
if (!is_dir($dir)) {
    mkdir($dir, 0775, true);
}

$files = [];
$dh = opendir($dir);
if ($dh !== false) {
    while (($file = readdir($dh)) !== false) {
        if ($file === '.' || $file === '..') continue;
        $full = $dir . DIRECTORY_SEPARATOR . $file;
        if (is_file($full)) {
            $files[] = $file;
        }
    }
    closedir($dh);
}
sort($files, SORT_NATURAL | SORT_FLAG_CASE);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <title>File List</title>
  <link rel="stylesheet" href="/common/style.css" />
</head>
<body>
  <h1>Files in the <code>uploads</code> folder</h1>

  <div class="card">
    <?php if (!$files): ?>
      <p class="muted">No files yet. Try uploading something on the <a href="index.html">home page</a>.</p>
    <?php else: ?>
      <table>
        <thead><tr><th>File</th><th>Size (KB)</th><th>Action</th></tr></thead>
        <tbody>
        <?php foreach ($files as $f): 
              $full = $dir . DIRECTORY_SEPARATOR . $f;
              $sizeKB = number_format(filesize($full) / 1024, 2, '.', ',');
              $href = 'uploads/' . rawurlencode($f);
        ?>
          <tr>
            <td><?= h($f) ?></td>
            <td><?= h($sizeKB) ?></td>
            <td><a class="btn" href="<?= h($href) ?>" download>Download</a></td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    <?php endif; ?>
  </div>

  <p><a class="btn" href="index.html">Back to Home</a></p>
</body>
</html>