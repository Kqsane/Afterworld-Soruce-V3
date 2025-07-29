<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/config/main.php';
header('Content-Type: application/json');
$from_id = getuserinfo($_COOKIE['_ROBLOSECURITY'])['UserId'] ?? 0;
$to_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if (!$from_id || !$to_id || $from_id === $to_id) {
    echo json_encode(['status' => 'none']);
    exit;
}
$stmt = $pdo->prepare("SELECT * FROM friends WHERE (from_id = ? AND to_id = ?) OR (from_id = ? AND to_id = ?)");
$stmt->execute([$from_id, $to_id, $to_id, $from_id]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ($row) {
    $type = (int)$row['type'];
    if ($type === 3) {
        if ((int)$row['to_id'] === $from_id) {
            echo json_encode(['status' => 'accept']);
        } else {
            echo json_encode(['status' => 'sent']);
        }
    } elseif (in_array($type, [1, 2])) {
        echo json_encode(['status' => 'friends']);
    } else {
        echo json_encode(['status' => 'none']);
    }
} else {
    echo json_encode(['status' => 'add']);
}
