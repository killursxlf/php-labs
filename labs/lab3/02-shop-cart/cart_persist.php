<?php
declare(strict_types=1);
session_start();
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: cart.php");
    exit;
}
function load_previous(): array {
    if (!isset($_COOKIE['previous_purchases'])) return [];
    $arr = json_decode($_COOKIE['previous_purchases'], true);
    return is_array($arr) ? $arr : [];
}
function save_previous(array $map): void {
    setcookie('previous_purchases', json_encode($map, JSON_UNESCAPED_UNICODE), time()+30*24*60*60, '/');
}
$prev = load_previous();
foreach (($_SESSION['cart'] ?? []) as $pid => $qty) {
    $prev[$pid] = ($prev[$pid] ?? 0) + (int)$qty;
}
save_previous($prev);
header("Location: cart.php");
exit;
?>