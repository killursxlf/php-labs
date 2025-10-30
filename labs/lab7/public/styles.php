<?php
$source = realpath(__DIR__ . '/../../../common/style.css');
if ($source === false || !is_file($source)) {
  http_response_code(404);
  header('Content-Type: text/plain; charset=utf-8');
  echo 'style.css not found';
  exit;
}
$mtime = filemtime($source);
$size  = filesize($source);
$etag  = '"' . md5($mtime . '|' . $size) . '"';
$lastModified = gmdate('D, d M Y H:i:s', $mtime) . ' GMT';
$maxAge = 86400;
$expires = gmdate('D, d M Y H:i:s', time() + $maxAge) . ' GMT';

$ifNoneMatch     = $_SERVER['HTTP_IF_NONE_MATCH']     ?? '';
$ifModifiedSince = $_SERVER['HTTP_IF_MODIFIED_SINCE'] ?? '';

if ($ifNoneMatch === $etag || $ifModifiedSince === $lastModified) {
  header('HTTP/1.1 304 Not Modified');
  header('Cache-Control: public, max-age=' . $maxAge);
  header('Expires: ' . $expires);
  header('ETag: ' . $etag);
  header('Last-Modified: ' . $lastModified);
  exit;
}

header('Content-Type: text/css; charset=utf-8');
header('Cache-Control: public, max-age=' . $maxAge);
header('Expires: ' . $expires);
header('ETag: ' . $etag);
header('Last-Modified: ' . $lastModified);
readfile($source);
