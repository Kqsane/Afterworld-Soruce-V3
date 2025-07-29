<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/config/main.php';
header('Content-Type: application/json');
$data = json_decode(file_get_contents("php://input"), true);
$to_id = (int)($data['to_id'] ?? 0);
$from_id = getuserinfo($_COOKIE['_ROBLOSECURITY'])['UserId'] ?? 0;
if (!$from_id || !$to_id || $from_id === $to_id) {
    echo json_encode(['success' => false]);
    exit;
}
$stmt = $pdo->prepare("SELECT * FROM friends WHERE (from_id = ? AND to_id = ?) OR (from_id = ? AND to_id = ?)");
$stmt->execute([$from_id, $to_id, $to_id, $from_id]);
if (!$stmt->fetch()) {
    $pdo->prepare("INSERT INTO friends (from_id, to_id, type) VALUES (?, ?, 3)")
        ->execute([$from_id, $to_id]);
}
echo json_encode(['success' => true]);
