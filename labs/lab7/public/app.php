<?php
$maxAge  = 86400;
$expires = gmdate('D, d M Y H:i:s', time() + $maxAge) . ' GMT';
$etag    = '"' . md5(filemtime(__FILE__)) . '"';

if (($_SERVER['HTTP_IF_NONE_MATCH'] ?? '') === $etag) {
  header('HTTP/1.1 304 Not Modified');
  header('Cache-Control: public, max-age=' . $maxAge);
  header('Expires: ' . $expires);
  header('ETag: ' . $etag);
  exit;
}

header('Content-Type: application/javascript; charset=utf-8');
header('Cache-Control: public, max-age=' . $maxAge);
header('Expires: ' . $expires);
header('ETag: ' . $etag);

echo "console.log('app.js loaded; caching active.');";
