<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/config/main.php';

function currentUserId(): int
{
    global $pdo;
    if (empty($_COOKIE['_ROBLOSECURITY'])) {
        return 0;
    }
    $stmt = $pdo->prepare("SELECT UserId FROM users WHERE ROBLOSECURITY = ? LIMIT 1");
    $stmt->execute([$_COOKIE['_ROBLOSECURITY']]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    return isset($row['UserId']) ? (int)$row['UserId'] : 0;
}

global $pdo;
$userId = currentUserId();
if ($userId > 0) {
    $stmt = $pdo->prepare("SELECT lastSeen FROM users WHERE UserId = ?");
    $stmt->execute([$userId]);
    $lastSeen = $stmt->fetchColumn();
    if ($lastSeen) {
        $lastTimestamp = strtotime($lastSeen);
        if ($lastTimestamp < time() - 15) {
            $stmt = $pdo->prepare("UPDATE users SET status = 0 WHERE UserId = ?");
            $stmt->execute([$userId]);
        }
    }
    $stmt = $pdo->prepare("UPDATE users SET status = 1, lastSeen = NOW() WHERE UserId = ?");
    $stmt->execute([$userId]);
}
header('Content-Type: image/png');
header('Cache-Control: no-cache, no-store, must-revalidate');
header('Expires: 0');
echo base64_decode('iVBORw0KGgoAAAANSUhEUgAAAAEAAAABCAQAAAC1HAwCAAAAC0lEQVR4nGNgYAAAAAMAAWgmWQ0AAAAASUVORK5CYII=');
exit;
