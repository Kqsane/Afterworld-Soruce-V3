<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include_once $_SERVER['DOCUMENT_ROOT'] . '/config/main.php';
$port = $_POST['sport'] ?? null;
$ip = $_POST['sip'] ?? null;

// Check for missing required fields
if (empty($ip) || empty($port)) {
    exit("Please fill in all required fields.");
}
$checkQuery = $pdo->prepare("SELECT * FROM game_session WHERE LCASE(serverip) = :serverip");
$checkQuery->execute(['serverip' => strtolower($ip)]);
if ($checkQuery->rowCount() > 0) {
    $existingRow = $checkQuery->fetch(PDO::FETCH_ASSOC);
    $existingId = $existingRow['serverid'];
    exit("Server already uploaded to the database. <a href='/games/start?placeid=" . $existingId . "'>Join Game</a> <div> <b> Details: </b> <p>Server Id: ".$existingId."</p> </div>");
}
$insertQuery = $pdo->prepare(
    "INSERT INTO game_session (serverip, port) 
    VALUES (:serverip, :port)"
);

$insertSuccess = $insertQuery->execute([
    'serverip'   => htmlspecialchars($ip),
    'port' => (int)$port,
]);
if ($insertSuccess) {
    $insertedId = $pdo->lastInsertId();
    exit("Server uploaded successfully. <a href='/games/start?placeid=" . $insertedId . "'>Join Game</a> <div> <b> Details: </b> <p>Server Id: ".$insertedId."</p> </div>");
} else {
    exit("An error occurred while uploading the server information.");
}
