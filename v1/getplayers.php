<?php
include_once $_SERVER["DOCUMENT_ROOT"] . '/config/main.php';
header(HEADER_APPLICATION_JSON);

rate_limiter($user_ip, $limit = 10, $period = 60);

try {
    $stmt = $pdo->prepare("SELECT * FROM `jobs` WHERE `players` > 0");
    $stmt->execute();
} catch (PDOException $e) {
    http_response_code(500);
    exit(json_encode([
        "success" => false,
        "error" => "An internal database error occured"
    ]));
}

$players = 0;
if ($stmt->rowCount() > 0) {
    foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $job) {
        if ($job["players"] > 0) {
            $players += $job["players"];
        }
    }
}

exit(json_encode([
    "success" => true,
    "players" => $players
]));