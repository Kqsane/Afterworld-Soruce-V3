<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/config/main.php';
header('Content-Type: application/json');
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method Not Allowed']);
    exit;
}
$session = $_COOKIE['_ROBLOSECURITY'] ?? null;
$authUser = $session ? getuserinfo($session) : null;
$userId = isset($_GET['userId']) ? (int)$_GET['userId'] : ($authUser['UserId'] ?? 0);
if (!$userId) {
    http_response_code(404);
    echo json_encode(['success' => false, 'message' => 'Resource Not Found: Not enabled']);
    exit;
}
$stmt = $pdo->prepare("SELECT COUNT(*) FROM friends WHERE type IN (1, 2) AND (from_id = :id OR to_id = :id)");
$stmt->execute(['id' => $userId]);
$count = (int)$stmt->fetchColumn();
echo json_encode([
    'success' => true,
    'message' => 'Success',
    'count' => $count
]);
