<?php
include_once $_SERVER['DOCUMENT_ROOT'] . '/config/main.php';

header("Content-Type: application/json");
use TrashBlx\Core\SoapUtils;

$soapUtils = new SoapUtils();
if (!isset($_GET["placeId"])) {
    exit(json_encode(['Error' => "Place ID not provided."]));
}

$placeId = (int)$_GET["placeId"];
$FindGames = $pdo->prepare('SELECT * FROM assets WHERE AssetID = :placeId AND AssetType = 9 AND isPrivate = 0');
$getid = (int) $_GET['placeId'];
$FindGames->execute(['placeId' => $placeId]);
$row = $FindGames->fetch(PDO::FETCH_ASSOC);
if (!$FindGames) {
    print_r($pdo->errorInfo());
}
// Lookup an active job for this place
$FindActiveServer = $pdo->prepare('select * from jobs where placeId= :place');
$FindActiveServer->execute(["place" => $placeId]);
$jobrow = $FindActiveServer->fetch(PDO::FETCH_ASSOC);
$status = 1;
if ($jobrow)
{
    $jobId = $jobrow['jobid'];
    $lastHeartbeat = time() - $jobrow["heartbeat"];

    if (!$soapUtils->isPortInUse($jobrow["port"]) && $lastHeartbeat > 5 || $jobrow["heartbeat"] == null)
    {
        Sleep(5);
        $FindActiveServer = $pdo->prepare('select * from jobs where placeId= :placeId');
        $FindActiveServer->execute(['placeId' => $placeId]);
        $jobrow = $FindActiveServer->fetch(PDO::FETCH_ASSOC);
        $jobId = $jobrow['jobid'];
        $lastHeartbeat = time() - $jobrow["heartbeat"];
        if (!$soapUtils->isPortInUse($jobrow["port"]) && $lastHeartbeat > 5 || $jobrow["heartbeat"] == null)
        {
            $stmt = $pdo->prepare('DELETE FROM `jobs` WHERE `jobid` = :jobid');
            $stmt->bindParam(':jobid', $jobId, PDO::PARAM_STR);
            $stmt->execute();
            $soapUtils->killRcc($jobrow["pid"]);
            $port = rand(61500, 62000);
            $soapport = rand(6100, 7000);

            while ($soapUtils->isPortInUse($port))
            {
                $port = rand(61500, 62000);
            }
            while ($soapUtils->isPortInUse($soapport))
            {
                $soapport = rand(6100, 7000);
            }
            $code = $soapUtils->openJobGS($port, (int)$_GET['placeId'], $soapport, $row['CreatorID'],$row['ClientYear']);
            if ($code == false)
            {
                exit();
            } elseif($code['isRunning'] == true)
            {
                $jobId = $code['JobId'];
				
                $query = $pdo->prepare("INSERT INTO jobs (jobid, placeId, isRunning, port, soapport, startTime, pid, playerList) VALUES (:jobid, :gameId, :isRunning, :port, :soapport, :startTime, :pid, \"[]\")");
                $query->execute(["jobid" => $code['JobId'], "gameId" => (int)$_GET['placeId'], "isRunning" => $code['isRunning'], "port" => $port, "soapport" => $code['soapport'], "startTime" => time(), "pid" => $code['pid']]);
            }
        } 
    } else {
        $status = 2;
    }
    $jobId = $jobrow['jobid'];
} else {
    $port = rand(5300, 5400);
    $soapport = rand(6100, 7000);
    while ($soapUtils->isPortInUse($port))
    {
        $port = rand(5300, 5400);
    }
    while ($soapUtils->isPortInUse($soapport))
    {
        $soapport = rand(6100, 7000);
    }

    $code = $soapUtils->openJobGS($port, (int)$_GET['placeId'], $soapport, $row['CreatorID'], $row['ClientYear']);

    if ($code == false)
    {
        exit();
    } elseif($code['isRunning'] == true)
    {
        $jobId = $code['JobId'];
        $query = $pdo->prepare("INSERT INTO jobs (jobid, placeId, isRunning, port, soapport, startTime, pid, playerList) VALUES (:jobid, :gameId, :isRunning, :port, :soapport, :startTime, :pid, '[]')");
        $query->execute(["jobid" => $code['JobId'], "gameId" => (int)$_GET['placeId'], "isRunning" => $code['isRunning'], "port" => $port, "soapport" => $code['soapport'], "startTime" => time(), "pid" => $code['pid']]);
    }

    $status = 1;
}
// Set default URLs
$authUrl = "https://devopstest1.aftwld.xyz/Login/Negotiate.ashx";
$joinScriptUrl = "https://devopstest1.aftwld.xyz/Game/Join.ashx";
$authTicket = "1";

// Determine how to construct the Join URL
if (isset($_COOKIE["_ROBLOSECURITY"])) {
    $userinfo = getuserinfo($_COOKIE["_ROBLOSECURITY"]);

    if (!is_array($userinfo)) {
        exit(http_response_code(403));
    }

    $joinScriptUrl .= "?placeId=". $placeId;

} elseif (isset($_GET["useTokendatabase"]) && $_GET["useTokendatabase"] === "true" && !isset($_COOKIE["_ROBLOSECURITY"])) {
    $joinScriptUrl .= "?placeId=". $placeId ."&UseTicket=true";
} else {
    $joinScriptUrl .= "?placeId=". $placeId;
}

// Build join script response
$script = [
    "jobId" => $jobId,
    "status" => $status,
    "joinScriptUrl" => $joinScriptUrl,
    "authenticationUrl" => $authUrl,
    "authenticationTicket" => $authTicket,
    "message" => null
];

echo json_encode($script, JSON_UNESCAPED_SLASHES);
