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
    exit("You are not allowed to enter this page lol");
}

$replyId = isset($_GET['ReplyID']) ? (int)$_GET['ReplyID'] : 0;

$stmt = $pdo->prepare("SELECT PostId FROM forum_replies WHERE id = :id");
$stmt->execute([':id' => $replyId]);
$postId = (int)$stmt->fetchColumn();

$stmt = $pdo->prepare("DELETE FROM forum_replies WHERE id = :id");
$stmt->execute([':id' => $replyId]);

header("Location: /Forum/ShowPost.aspx?PostID=$postId");
exit();
?>