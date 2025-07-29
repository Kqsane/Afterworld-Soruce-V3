<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/config/main.php';
header('Content-Type: application/json');
$data = json_decode(file_get_contents("php://input"), true);
$to_id = (int)($data['to_id'] ?? 0);
$from_id = getuserinfo($_COOKIE['_ROBLOSECURITY'])['UserId'] ?? 0;
if (!$from_id || !$to_id) {
    echo json_encode(['success' => false]);
    exit;
}
$pdo->prepare("DELETE FROM friends WHERE (from_id = ? AND to_id = ?) OR (from_id = ? AND to_id = ?)")
    ->execute([$from_id, $to_id, $to_id, $from_id]);
echo json_encode(['success' => true]);
