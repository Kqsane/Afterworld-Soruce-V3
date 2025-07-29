<?php
require_once $_SERVER['DOCUMENT_ROOT'] . '/config/main.php';
include_once $_SERVER['DOCUMENT_ROOT'] . '/config/miscfunctions.php';
 
function timeAgo($timestamp) {
    $time = time() - $timestamp;
    $units = [
        31536000 => 'year',
        2592000  => 'month',
        604800   => 'week',
        86400    => 'day',
        3600     => 'hour',
        60       => 'minute',
        1        => 'second'
    ];
    foreach ($units as $unitSeconds => $unitName) {
        if ($time >= $unitSeconds) {
            $numUnits = floor($time / $unitSeconds);
            return $numUnits . ' ' . $unitName . ($numUnits > 1 ? 's' : '');
        }
    }
    return 'Just now';
}
 
$assetId = $_GET['assetID'] ?? null;
$commentText = file_get_contents("php://input");
$userId = isset($_COOKIE['_ROBLOSECURITY']) ? getuserinfo($_COOKIE['_ROBLOSECURITY'])["UserId"] : false;
$rqtype = isset($_GET['rqtype']) ? $_GET['rqtype'] : null;
 
function ownasset($asset) {
    global $userId;
    global $pdo;
    $stmt = $pdo->prepare("SELECT * FROM assets WHERE AssetID = :assetId");
    $stmt->execute(["assetId" => $asset]);
    $info = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!$info || $info["CreatorID"] !== $userId) {
        return false;
    }
    return true;
}

 
function getRealIP() {
    if (!empty($_SERVER['HTTP_CF_CONNECTING_IP'])) {
        return $_SERVER['HTTP_CF_CONNECTING_IP'];
    } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        return trim(explode(',', $_SERVER['HTTP_X_FORWARDED_FOR'])[0]);
    } else {
        return $_SERVER['REMOTE_ADDR'];
    }
}
 
if ($rqtype == "makeComment") {
    rate_limiter($user_ip, 5, 60);
    if (!$userId || !$assetId || trim($commentText) === '') {
        echo "Invalid comment data.";
        exit;
    }
    $commentText = htmlspecialchars($commentText);
    if (containsSwearWords($commentText, loadSwearWords($_SERVER['DOCUMENT_ROOT'] . '/config/includes/swearwords.txt'))) {
        exit("Content Moderated");
    }
    $time = time();
    $userInfo = getuserinfo($userId);
    if ($userinfo['IsBanned'] == 1 || $userinfo['activated'] == 0) {
        exit("You have no permission to comment this item.");
    }
    $stmt = $pdo->prepare("INSERT INTO comments (Posted_At, Text, Author_Id, AssetID) VALUES (?, ?, ?, ?)");
    $stmt->execute([$time, $commentText, $userId, $assetId]);
    $commentId = $pdo->lastInsertId();
    header('Content-Type: application/json');
    echo json_encode([
        "ID" => (int)$commentId,
        "Date" => timeAgo($time),
        "Content" => $commentText,
        "Author" => $userInfo['Username'],
        "AuthorID" => (int)$userId
    ]);
    exit;
} elseif ($rqtype == "deleteComment") {
    rate_limiter($user_ip, 25, 60);
    $stmt = $pdo->prepare("SELECT * FROM comments WHERE id = :comment");
    $stmt->execute(["comment" => $_GET['commentID']]);
    $info = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!ownasset($info["AssetID"])) {
        exit("You dont own this asset to delete this comment");
    }
    $stmt = $pdo->prepare("DELETE FROM comments WHERE id = :comment");
    $stmt->execute(["comment" => $_GET['commentID']]);
    exit;
} elseif ($rqtype == "getComments") {
    $startIndex = isset($_GET['startIndex']) ? intval($_GET['startIndex']) : 0;
    $maxRows = 10;
    $stmt = $pdo->prepare("SELECT id, Posted_At, Author_Id, Text FROM comments WHERE AssetID = :assetId ORDER BY Posted_At DESC LIMIT :startIndex, :maxRows");
    $stmt->bindValue(':assetId', $assetId, PDO::PARAM_INT);
    $stmt->bindValue(':startIndex', $startIndex, PDO::PARAM_INT);
    $stmt->bindValue(':maxRows', $maxRows, PDO::PARAM_INT);
    $stmt->execute();
    $comments = [];
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $authorId = (int)$row['Author_Id'];
        $user = getuserinfo($authorId);
        $comments[] = [
            "Date" => timeAgo($row['Posted_At']),
            "ID" => (int)$row['id'],
            "Author" => $user['Username'] ?? 'Unknown',
            "AuthorID" => $authorId,
            "Content" => ($row['Text']),
            "AuthorOwnsAsset" => ownasset($assetId) ?? false,
        ];
    }
    $response = [
        "isMod" => ownasset($assetId) ?? false,
        "data" => $comments,
        "totalCount" => $maxRows
    ];
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}
echo "invalid request";
?>