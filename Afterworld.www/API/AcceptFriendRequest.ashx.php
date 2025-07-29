<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/config/main.php';
header('Content-Type: application/json');
$data = json_decode(file_get_contents("php://input"), true);
$from_id = (int)($data['from_id'] ?? 0);
$to_id = getuserinfo($_COOKIE['_ROBLOSECURITY'])['UserId'] ?? 0;
if (!$from_id || !$to_id) {
    echo json_encode(['success' => false]);
    exit;
}
$stmt = $pdo->prepare("UPDATE friends SET type = 1 WHERE from_id = ? AND to_id = ? AND type = 3");
$success = $stmt->execute([$from_id, $to_id]);
echo json_encode(['success' => $success]);
