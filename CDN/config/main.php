<?php
foreach (glob(__DIR__ . "/classes/Rcc/*.php") as $filename)
{
    require_once $filename;
}
require_once __DIR__ . "/classes/SoapUtils.php";
include_once __DIR__ . "/miscfunctions.php";
try {
    $pdo = new PDO(
        "mysql:host=194.62.248.75;dbname=aftwld",
        "root",
        "AFTWLD$92EUKSQB39321KS",
        [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
    );
} catch (PDOException $e) {
    http_response_code(500);
    header("Content-Type: application/json");
    exit(json_encode([
        "success" => false,
        "error" => "Database Error",
        "debug" => $e->getMessage()
    ]));
}
define("AFTERWORLD_API_KEY", "a4b9d3e6-38f2-4731-bdb9-92f2cb8607fe");
function verifyKey(): void {
    if (!isset($_GET['apiKey']) || $_GET['apiKey'] !== AFTERWORLD_API_KEY) {
        http_response_code(403);
        echo json_encode(['success' => false, 'message' => 'Invalid API key']);
        exit;
    }
}
