<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/config/main.php';

header('Content-Type: application/json');

if (!isset($_COOKIE['_ROBLOSECURITY'])) {
    echo json_encode(['success' => false, 'error' => 'Not logged in']);
    exit;
}

$info = getuserinfo($_COOKIE['_ROBLOSECURITY']);
if (!$info || !isset($_POST['stream_id'], $_POST['message'])) {
    echo json_encode(['success' => false, 'error' => 'Invalid request']);
    exit;
}

$stream_id = (int)$_POST['stream_id'];
$user_id = $info['UserId'];
$message = trim($_POST['message']);

if ($stream_id <= 0 || $message === '') {
    echo json_encode(['success' => false, 'error' => 'Missing stream ID or message']);
    exit;
}

$stmt = $pdo->prepare("INSERT INTO stream_chat (stream_id, user_id, message) VALUES (?, ?, ?)");
$stmt->execute([$stream_id, $user_id, $message]);

echo json_encode(['success' => true]);
