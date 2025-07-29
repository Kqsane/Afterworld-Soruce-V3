<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/config/main.php';
header('Content-Type: application/json');
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method Not Allowed']);
    exit;
}
$data = json_decode(file_get_contents("php://input"), true);
$requesterId = (int)($data['requesterUserId'] ?? 0);
$session = $_COOKIE['_ROBLOSECURITY'] ?? null;
$user = $session ? getuserinfo($session) : null;
$receiverId = $user['UserId'] ?? 0;
if (!$requesterId || !$receiverId) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid requesterUserId']);
    exit;
}
$stmt = $pdo->prepare("UPDATE friends SET type = 1 WHERE from_id = ? AND to_id = ? AND type = 3");
$success = $stmt->execute([$requesterId, $receiverId]);
if ($success && $stmt->rowCount() > 0) {
    echo json_encode(['success' => true, 'message' => 'Success']);
} else {
    http_response_code(404);
    echo json_encode(['success' => false, 'message' => 'Resource Not found: Not enabled']);
}