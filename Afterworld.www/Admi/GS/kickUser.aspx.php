<?php

use Roblox\Grid\Rcc\RCCServiceSoap;
use Roblox\Grid\Rcc\ScriptExecution;

include_once $_SERVER['DOCUMENT_ROOT'] . '/config/main.php';

final class AdminLevel {
    public const NONE = 0;
    public const ASSET_MOD = 1;
    public const MODERATOR = 2;
    public const ADMIN = 3;
    public const HEAD_ADMIN = 4;
    public const DEV = 5;
}

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
const SHUTDOWN_MIN_LEVEL = AdminLevel::DEV;
if ($adminLevel < SHUTDOWN_MIN_LEVEL) {
    http_response_code(403);
    exit('insufficient privileges.');
}

if (!isset($_GET["jobid"]) || !isset($_GET["id"]))
{
    header(HEADER_APPLICATION_JSON);
    http_response_code(401);
    exit(json_encode([
        "success" => false,
        "error" => "Invalid GET params"
    ]));
}

(string) $jobid = $_GET["jobid"];
(int) $userId = $_GET["id"];

if (empty($jobid))
{
    header(HEADER_APPLICATION_JSON);
    http_response_code(401);
    exit(json_encode([
        "success" => false,
        "error" => "JobId cannot be empty"
    ]));
}

if ($userId < 1)
{
    header(HEADER_APPLICATION_JSON);
    http_response_code(401);
    exit(json_encode([
        "success" => false,
        "error" => "Invalid userId"
    ]));
}

try {
    $stmt = $pdo->prepare("SELECT * FROM `jobs` WHERE `jobid` = :jobId");
    $stmt->bindParam(":jobId", $jobid, PDO::PARAM_STR);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $rowCount = $stmt->rowCount();
} catch (PDOException $e)
{
    header(HEADER_APPLICATION_JSON);
    http_response_code(500);
    exit(json_encode([
        "success" => false,
        "error" => "A database error occured. Please report this to the developers."
    ]));
}

if ($rowCount < 1)
{
    header(HEADER_APPLICATION_JSON);
    http_response_code(401);
    exit(json_encode([
        "success" => false,
        "error" => "Invalid Job"
    ]));
}

if ($row["isRenderer"] == 1)
{
    header(HEADER_APPLICATION_JSON);
    http_response_code(401);
    exit(json_encode([
        "success" => false,
        "error" => "Only gameserver jobs are allowed"
    ]));
}

if ($row["soapport"] < 1)
{
    header(HEADER_APPLICATION_JSON);
    http_response_code(401);
    exit(json_encode([
        "success" => false,
        "error" => "Invalid job"
    ]));
}

try {
    $stmt = $pdo->prepare("SELECT * FROM `users` WHERE UserId = :userId");
    $stmt->bindParam(":userId", $userId, PDO::PARAM_INT);
    $stmt->execute();
    $rowCount = $stmt->rowCount();
    $userRow = $stmt->fetch(PDO::FETCH_ASSOC);
} catch (PDOException $e)
{
    header(HEADER_APPLICATION_JSON);
    http_response_code(500);
    exit(json_encode([
        "success" => false,
        "error" => "A database error occured. Please report this to the developers."
    ]));
}

if ($rowCount < 1)
{
    header(HEADER_APPLICATION_JSON);
    http_response_code(401);
    exit(json_encode([
        "success" => false,
        "error" => "Invalid user"
    ]));
}

$username = $userRow["Username"];
try {
    $rccSoap = new RCCServiceSoap("127.0.0.1", $row["soapport"]);
    $script = <<<lua
    game:GetService("Players"):FindFirstChild("{$username}"):Kick()
    lua;
    $script = new ScriptExecution("KickScript", $script);
    $rccSoap->ExecuteEx($jobid, $script);
} catch (Exception $e)
{
    header(HEADER_APPLICATION_JSON);
    http_response_code(500);
    exit(json_encode([
        "success" => false,
        "error" => "An error occured while processing the request"
    ]));
}

echo "Attempted to kick {$username}.\n";
echo "<a href='/Admi/GS.aspx'>Go Back?</a>";