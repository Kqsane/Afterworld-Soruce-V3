<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/config/main.php';
header(HEADER_APPLICATION_JSON);

use Roblox\Grid\Rcc\RCCServiceSoap;
use Roblox\Grid\Rcc\ScriptExecution;

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
    exit(json_encode([
        "success" => false,
        "message" => "insufficient privileges"
    ]));
}

class ScriptRequest
{
    public string $script;
    public string $jobId;

    public static function fromArray(array $data): ?self
    {
        if (!isset($data["script"], $data["jobId"])) {
            return null;
        }

        $instance = new self();
        $instance->script = $data["script"];
        $instance->jobId = $data["jobId"];

        return $instance;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST")
{
    $post = file_get_contents("php://input");
    if (!$post || empty($post))
    {
        http_response_code(400);
        exit(json_encode([
            "success" => false,
            "message" => "invalid post request"
        ]));
    }

    if (!json_validate($post))
    {
        http_response_code(400);
        exit(json_encode([
            "success" => false,
            "message" => "invalid post request"
        ]));
    }

    $post_data = json_decode($post, true);
    $script_req = ScriptRequest::fromArray($post_data);

    if (!$script_req)
    {
        http_response_code(400);
        exit(json_encode([
            "success" => false,
            "message" => "invalid json format"
        ]));
    }

    try {
        $stmt = $pdo->prepare("SELECT * FROM `jobs` WHERE `jobid` = :jobId");
        $stmt->bindParam(":jobId", $script_req->jobId, PDO::PARAM_STR);
        $stmt->execute();
        $rowCount = $stmt->rowCount();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e)
    {
        http_response_code(500);
        exit(json_encode([
            "success" => false,
            "message" => "A database error occured. Please report this to the developers."
        ]));
    }

    if ($rowCount < 1)
    {
        http_response_code(400);
        exit(json_encode([
            "success" => false,
            "message" => "Invalid job"
        ]));
    }

    if ($row["isRenderer"] == 1)
    {
        http_response_code(400);
        exit(json_encode([
            "success" => false,
            "message" => "Invalid job"
        ]));
    }

    try {
        $rccSoap = new RCCServiceSoap("127.0.0.1", $row["soapport"]);
        $script = new ScriptExecution("ExecuteScript", $script_req->script);
        $result = $rccSoap->ExecuteEx($row["jobid"], $script);
    } catch (Exception $e)
    {
        http_response_code(500);
        exit(json_encode([
            "success" => false,
            "message" => "An error occured. Please report this to the developers."
        ]));
    }

    if ($result instanceof SoapFault)
    {
        exit(json_encode([
            "success" => true,
            "result" => $result->faultstring
        ]));
    }

    exit(json_encode([
        "success" => true,
        "result" => ""
    ]));
} else {
    http_response_code(405);
    exit(json_encode([
        "success" => false,
        "message" => "method not allowed"
    ]));
}