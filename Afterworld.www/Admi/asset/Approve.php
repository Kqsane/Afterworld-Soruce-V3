<?php
// random 2025
// approve mii
declare(strict_types=1);
header('Content-Type: text/plain; charset=utf-8');
include_once $_SERVER['DOCUMENT_ROOT'] . '/config/main.php';
final class AdminLevel {
    public const NONE = 0;
    public const ASSET_MOD = 1;
    public const MODERATOR = 2;
    public const ADMIN = 3;
    public const HEAD_ADMIN = 4;
    public const DEV = 5;
}
const SHUTDOWN_MIN_LEVEL = AdminLevel::DEV;
function requireAuthCookie(string $name = '_ROBLOSECURITY'): string
{
    if (empty($_COOKIE[$name])) {
        logout();
        header('Location: /newlogin');
        exit;
    }
    return $_COOKIE[$name];
}
function fetchAdminLevel(PDO $pdo, int $userId): int
{
    $stmt = $pdo->prepare('SELECT isAdmin FROM users WHERE UserId = :uid LIMIT 1');
    $stmt->execute([':uid' => $userId]);
    return (int)($stmt->fetchColumn() ?: 0);
}
$cookie = requireAuthCookie();
$user   = getuserinfo($cookie);
if (!$user || !isset($user['UserId'])) {
    logout();
    header('Location: /');
    exit;
}
$adminLevel = fetchAdminLevel($pdo, (int)$user['UserId']);
if ($adminLevel < SHUTDOWN_MIN_LEVEL) {
    http_response_code(403);
    exit('insufficient privileges.');
}
$assetid = $_GET['assetId'] ?? null;
if (!$assetid) {
    http_response_code(400);
    exit('Missing asset ID.');
}
$stmt = $pdo->prepare('SELECT AssetID, isApproved FROM assets WHERE AssetID = :assetid');
$stmt->execute([':assetid' => $assetid]);
$asset = $stmt->fetch(PDO::FETCH_ASSOC);

if ((int)$asset['isApproved'] === 0) {
    http_response_code(404);
    exit('[ERROR] Asset was not approved earlier.');
}


$stmt = $pdo->prepare("UPDATE assets SET isApproved = 1 WHERE AssetID = :assetid");
$success = $stmt->execute([':assetid' => $assetid]);

if ($success) {
    echo '[OK] Update ran.';
    echo '[INFO] Rows affected: ' . $stmt->rowCount();
} else {
    echo '[ERROR] Update failed.';
}


