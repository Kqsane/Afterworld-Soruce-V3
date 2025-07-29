<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/config/main.php';
header('Content-Type: application/json');
$requestUri = $_SERVER['REQUEST_URI'];
preg_match('#/users/(\d+)/friends#', $requestUri, $matches);
$userId = isset($matches[1]) ? (int)$matches[1] : 0;
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$limit = 20;
$offset = ($page - 1) * $limit;
if (!$userId) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid userId']);
    exit;
}
$stmt = $pdo->prepare("SELECT CASE WHEN from_id = :uid THEN to_id ELSE from_id END AS friend_id FROM friends WHERE type IN (1, 2) AND (from_id = :uid OR to_id = :uid) LIMIT :limit OFFSET :offset");
$stmt->bindValue('uid', $userId, PDO::PARAM_INT);
$stmt->bindValue('limit', $limit, PDO::PARAM_INT);
$stmt->bindValue('offset', $offset, PDO::PARAM_INT);
$stmt->execute();
$friendIds = $stmt->fetchAll(PDO::FETCH_COLUMN);
if (!$friendIds) {
    echo json_encode([]);
    exit;
}
$inQuery = implode(',', array_fill(0, count($friendIds), '?'));
$usersStmt = $pdo->prepare("SELECT UserId, Username, status FROM users WHERE UserId IN ($inQuery)");
$usersStmt->execute($friendIds);
$friends = [];
while ($row = $usersStmt->fetch(PDO::FETCH_ASSOC)) {
    $friends[] = [
        'Id' => (int)$row['UserId'],
        'Username' => $row['Username'],
        'AvatarUri' => '/Thumbs/Avatar.ashx?userId=' . $row['UserId'],
        'AvatarFinal' => true,
        'IsOnline' => isset($row['status']) && (int)$row['status'] === 1
    ];
}
echo json_encode($friends);
