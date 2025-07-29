<?php
include($_SERVER['DOCUMENT_ROOT'] . '/config/main.php');
header('Content-Type: application/json');
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}
$input = json_decode(file_get_contents("php://input"), true);
$content = trim($input['content'] ?? '');
$pid = (int)($input['pid'] ?? 0);
if (!$pid) {
    echo json_encode(['success' => false, 'message' => 'User ID missing']);
    exit;
}
if (empty($content)) {
    echo json_encode(['success' => false, 'message' => 'Content is empty']);
    exit;
}
try {
    $stmt = $pdo->prepare("INSERT INTO feeds (pid, content, postedAt, isGroup) VALUES (?, ?, ?, 0)");
    $stmt->execute([$pid, $content, time()]);
    echo json_encode(['success' => true]);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Database error']);
}