<?php
ob_start();
include_once $_SERVER['DOCUMENT_ROOT'] . '/config/main.php';
$method   = $_GET['method'] ?? null;
$playerid = isset($_GET['playerid']) ? (int)$_GET['playerid'] : 0;
$userid   = isset($_GET['userid']) ? (int)$_GET['userid'] : 0;
$groupid  = $_GET['groupid'] ?? null;
if (!$playerid) {
    echo '<Error>Missing playerid</Error>';
    exit;
}
$stmt = $pdo->prepare('SELECT isAdmin FROM users WHERE UserId = ?');
$stmt->execute([$playerid]);
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$admin = $row['isAdmin'] ?? 0;
$value = 'false';
if ($method === "IsFriendsWith") {
    if ($userid) {
        $check = $pdo->prepare("SELECT 1 FROM friends WHERE type = 1 AND ((from_id = :a AND to_id = :b) OR (from_id = :b AND to_id = :a))");
        $check->execute(['a' => $playerid, 'b' => $userid]);
        if ($check->fetch()) {
            $value = 'true';
        }
    }
    echo '<Value Type="boolean">' . $value . '</Value>';
} elseif ($method === "IsBestFriendsWith") {
    if ($userid) { 
        $check = $pdo->prepare("SELECT 1 FROM friends WHERE type = 1 AND ((from_id = :a AND to_id = :b) OR (from_id = :b AND to_id = :a))"); // CHANGED
        $check->execute(['a' => $playerid, 'b' => $userid]);
        if ($check->fetch()) {
            $value = 'true';
        }
    }
    echo '<Value Type="boolean">' . $value . '</Value>';
} elseif ($method === "IsInGroup") {
    if ($groupid === "1200769" && in_array($admin, [2, 3, 4, 5])) {
        $value = 'true';
    }
    echo '<Value Type="boolean">' . $value . '</Value>';
} else {
    echo '<Error>Unknown method</Error>';
}
