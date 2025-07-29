<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/config/main.php';
if (!isset($_COOKIE['_ROBLOSECURITY'])) {
    header("Location: /newlogin");
    exit();
}

$info = getuserinfo($_COOKIE['_ROBLOSECURITY']);
$userId = $info['UserId'] ?? null;

$stmt = $pdo->prepare("SELECT isAdmin FROM users WHERE UserId = ?");
$stmt->execute([$userId]);
$isAdmin = (int)($stmt->fetchColumn() ?? 0);

if (!in_array($isAdmin, [2, 3, 4, 5])) {
    exit("Unauthorized");
}

$postId = isset($_GET['PostID']) ? (int)$_GET['PostID'] : 0;
$stmt = $pdo->prepare("DELETE FROM forums WHERE id = ?");
$stmt->execute([$postId]);

header("Location: /Forum/Default.aspx");
exit();
?>
