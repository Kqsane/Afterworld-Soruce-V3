<?php
declare(strict_types=1);

use Roblox\Grid\Rcc\RCCServiceSoap;
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
$jobid = $_GET['jobid'] ?? null;
if (!$jobid) {
    http_response_code(400);
    exit('Missing job ID.');
}
$stmt = $pdo->prepare('SELECT pid, players, placeId, soapport FROM jobs WHERE jobid = :jobid');
$stmt->execute([':jobid' => $jobid]);
$job = $stmt->fetch(PDO::FETCH_ASSOC);
if (!$job) {
    http_response_code(404);
    exit('Job not found.');
}
$pid = (int)$job['pid'];
$playerCount = (int)$job['players'];
$placeId = (int)$job['placeId'];
// $cmd = strncasecmp(PHP_OS, 'WIN', 3) === 0
//     ? "taskkill /F /PID {$pid}"
//     : "kill -9 {$pid}";
// shell_exec("{$cmd} 2>&1");
$rccSoap = new RCCServiceSoap("127.0.0.1", $job["soapport"]);
$rccSoap->CloseJob($jobid);
$pdo->prepare('UPDATE assets SET players = GREATEST(players - :cnt, 0) WHERE AssetId = :place')->execute([':cnt' => $playerCount, ':place' => $placeId]);
$pdo->prepare('DELETE FROM jobs WHERE jobid = :jobid')->execute([':jobid' => $jobid]);

echo "Attempted to shutdown {$placeId}.\n<a href='/Admi/GS.aspx'>Go Back?</a>";