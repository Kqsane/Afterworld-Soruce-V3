<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/config/main.php';

$streamId = intval($_GET['stream_id'] ?? 0);
if (!$streamId) {
    http_response_code(400);
    echo json_encode(["error" => "Missing stream_id"]);
    exit;
}

$stmt = $pdo->prepare("
    SELECT sc.message, sc.sent_at, u.username, u.UserId AS user_id, u.IsAdmin, s.streamer_id
    FROM stream_chat sc
    JOIN users u ON sc.user_id = u.UserId
    JOIN stream s ON s.id = sc.stream_id
    WHERE sc.stream_id = :stream_id
    ORDER BY sc.sent_at DESC
    LIMIT 50
");
$stmt->execute(['stream_id' => $streamId]);
$messages = array_reverse($stmt->fetchAll(PDO::FETCH_ASSOC));

echo json_encode($messages);
?>