<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/config/main.php';
header('Content-Type: application/json');
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method Not Allowed']);
    exit;
}
$data = json_decode(file_get_contents("php://input"), true);
$recipientId = (int)($data['recipientUserId'] ?? 0);
$session = $_COOKIE['_ROBLOSECURITY'] ?? null;
$user = $session ? getuserinfo($session) : null;
$senderId = $user['UserId'] ?? 0;
if (!$senderId || !$recipientId || $senderId === $recipientId) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid recipientUserId']);
    exit;
}
$stmt = $pdo->prepare("SELECT 1 FROM friends WHERE (from_id = :a AND to_id = :b) OR (from_id = :b AND to_id = :a)");
$stmt->execute(['a' => $senderId, 'b' => $recipientId]);
if (!$stmt->fetch()) {
    $insert = $pdo->prepare("INSERT INTO friends (from_id, to_id, type) VALUES (?, ?, 3)");
    $insert->execute([$senderId, $recipientId]);
}
echo json_encode(['success' => true, 'message' => 'Success']);
