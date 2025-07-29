<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/config/main.php';
header('Content-Type: application/json');
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method Not Allowed']);
    exit;
}
$data = json_decode(file_get_contents("php://input"), true);
$friendId = (int)($data['friendUserId'] ?? 0);
$session = $_COOKIE['_ROBLOSECURITY'] ?? null;
$user = $session ? getuserinfo($session) : null;
$authId = $user['UserId'] ?? 0;
if (!$authId || !$friendId || $authId === $friendId) {
    http_response_code(400);
    echo json_encode(['success' => false, 'message' => 'Invalid friendUserId']);
    exit;
}
$stmt = $pdo->prepare("DELETE FROM friends WHERE (from_id = :a AND to_id = :b) OR (from_id = :b AND to_id = :a)");
$stmt->execute(['a' => $authId, 'b' => $friendId]);
if ($stmt->rowCount() > 0) {
    echo json_encode(['success' => true, 'message' => 'Success']);
} else {
    http_response_code(404);
    echo json_encode(['success' => false, 'message' => 'Not enabled']);
}
