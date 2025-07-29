<?php
include_once $_SERVER['DOCUMENT_ROOT'].'/config/main.php';
header("content-type: application/json");

$placeId = $_GET["placeId"];

$FindGames = $pdo->prepare('SELECT * FROM assets WHERE AssetID = :gid AND AssetType = 9');
$FindGames->execute(['gid' => (int)$placeId]);
$row = $FindGames->fetch(PDO::FETCH_ASSOC);

if (!$row) {
    exit("place doesn't exist");
}

$placeId = $row['AssetID'];
$startRow = $_GET["startindex"];

$hasPermission = false;

if (isset($_COOKIE["_ROBLOSECURITY"]) && getuserinfo($_COOKIE["_ROBLOSECURITY"])) {
    if ($row['CreatorID'] == getuserinfo($_COOKIE["_ROBLOSECURITY"])['UserId']) {
        $hasPermission = true;
    }
}

$result = [
    "PlaceId" => $placeId,
    "ShowShutdownAllButton" => $hasPermission,
    "Collection" => [],
    "TotalCollectionSize" => 0
];

// Get total count of active jobs for pagination
$stmt = $pdo->prepare("SELECT COUNT(*) FROM jobs WHERE placeId = :placeId AND isRunning = 1 AND heartbeat > :recentTime");
$stmt->execute([
    ":placeId" => $placeId,
    ":recentTime" => time() - 60 // 60s heartbeat freshness window
]);
$result["TotalCollectionSize"] = $stmt->fetchColumn();

// Get paginated jobs
$stmt = $pdo->prepare("
    SELECT * FROM jobs 
    WHERE placeId = :placeId AND isRunning = 1 AND heartbeat > :recentTime 
    ORDER BY players DESC 
    LIMIT :startRow, 10
");
$stmt->bindValue(":placeId", $placeId, PDO::PARAM_INT);
$stmt->bindValue(":recentTime", time() - 60, PDO::PARAM_INT);
$stmt->bindValue(":startRow", (int)$startRow, PDO::PARAM_INT);
$stmt->execute();

foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $job) {
    $playerList = [];
    $decodedPlayers = json_decode($job['playerList'], true);

    if (is_array($decodedPlayers)) {
        foreach ($decodedPlayers as $playerId) {
            if ((int)$playerId <= 0)
                $playerName = "Guest User";
            else
                $playerName = getuserinfo($playerId)['Username'];

            $playerList[] = [
                "Id" => $playerId,
                "Username" => $playerName,
                "Thumbnail" => [
                    "rowId" => 0,
                    "rowHash" => null,
                    "rowTypeId" => 0,
                    "Url" => "https://devopstest1.aftwld.xyz/Thumbs/Avatar.ashx?userId=" . $playerId ."&x=75&y=75",
                    "IsFinal" => true
                ]
            ];
        }
    }

    $joinScript = (!str_contains($_SERVER['HTTP_USER_AGENT'], "ROBLOX"))
        ? "Roblox.GameLauncher.joinGameInstance(" . $job["placeId"] . ", '" . $job["jobid"] . "');"
        : "window.location.href = 'https://devopstest1.aftwld.xyz/games/start?placeid=" . $job["placeId"] . "&gameInstanceId=" . $job["jobid"] . "';";

    $result["Collection"][] = [
        "Capacity" => $row['MaxPlayers'],
        "Ping" => 0,
        "Fps" => 60,
        "ShowSlowGameMessage" => false,
        "Guid" => $job["jobid"],
        "PlaceId" => $job["placeId"],
        "CurrentPlayers" => $playerList,
        "UserCanJoin" => false,
        "ShowShutdownButton" => $hasPermission,
        "JoinScript" => $joinScript
    ];
}

die(json_encode($result));
?>
